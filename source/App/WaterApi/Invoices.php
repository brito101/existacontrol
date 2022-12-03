<?php

namespace Source\App\WaterApi;

use Source\Models\WaterApp\AppInvoice;
use Source\Models\WaterApp\AppSubscription;
use Source\Support\Pager;

/**
 * Class Invoices
 * @package Source\App\WaterApi
 */
class Invoices extends WaterApi {

    /**
     * Invoices constructor.
     * @throws \Exception
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * list all invoices
     */
    public function index(): void {
        $values = $this->headers;

        $subscription = (new AppSubscription())->find("user_id = :user_id",
                "user_id={$this->user->id}");

        if (!$subscription->count()) {
            $this->call(
                    404,
                    "not_found",
                    "Assinatura não encontrada"
            )->back(["results" => 0]);
            return;
        }

        $subscription = $subscription->fetch();
        //get invoices
        $invoices = (new AppInvoice())->find("subscription_id = :subscription_id",
                "subscription_id={$subscription->id}");

        if (!$invoices->count()) {
            $this->call(
                    404,
                    "not_found",
                    "Nada encontrado para sua pesquisa. Tente outros termos"
            )->back(["results" => 0]);
            return;
        }

        $page = (!empty($values["page"]) ? $values["page"] : 1);
        $pager = new Pager(url("/invoices/"));
        $pager->pager($invoices->count(), 10, $page);

        $response["results"] = $invoices->count();
        $response["page"] = $pager->page();
        $response["pages"] = $pager->pages();

        foreach ($invoices->limit($pager->limit())->offset($pager->offset())->order("due_at ASC")->fetch(true) as $invoice) {
            $response["invoices"][] = $invoice->data();
        }

        $this->back($response);
        return;
    }

    /**
     * @param array $data
     */
    public function read(array $data): void {
        if (empty($data["invoice_id"]) || !$invoice_id = filter_var($data["invoice_id"], FILTER_VALIDATE_INT)) {
            $this->call(
                    400,
                    "invalid_data",
                    "É preciso informar o ID da fatura que deseja consultar"
            )->back();
            return;
        }

        $subscription = (new AppSubscription())->find("user_id = :user_id",
                "user_id={$this->user->id}");

        if (!$subscription->count()) {
            $this->call(
                    404,
                    "not_found",
                    "Assinatura não encontrada"
            )->back(["results" => 0]);
            return;
        }

        $subscription = $subscription->fetch();

        $invoice = (new AppInvoice())->find("subscription_id = :subscription_id AND id = :id",
                        "subscription_id={$subscription->id}&id={$invoice_id}")->fetch();

        if (!$invoice) {
            $this->call(
                    404,
                    "not_found",
                    "Você tentou acessar uma fatura que não existe"
            )->back();
            return;
        }

        $response["invoice"] = $invoice->data();

        $this->back($response);
    }

}
