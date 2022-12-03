<?php

namespace Source\App\Admin;

use Source\Models\WaterApp\AppCondominium;
use Source\Models\WaterApp\AppApartment;
use Source\Models\WaterApp\AppSubscription;
use Source\Models\WaterApp\AppSubscriptionCondominium;
use Source\Support\Pager;
use Source\Models\User;
use Source\Models\WaterApp\AppInvoice;
use Source\Models\WaterApp\AppInvoiceCondominium;
use Source\Support\Upload;
use Ddeboer\DataImport\Reader\CsvReader;
use Source\Support\Thumb;

/**
 * Class Control
 * @package Source\App\Admin
 */
class Control extends Admin {

    /**
     * Control constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     *
     */
    public function home(): void {
        $head = $this->seo->render(
                CONF_SITE_NAME . " | Controle",
                CONF_SITE_DESC,
                url("/admin"),
                theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
                false
        );

        echo $this->view->render("widgets/control/home", [
            "app" => "control/home",
            "head" => $head,
            "stats" => (object) [
                "condominiuns" => (new AppCondominium())->find("status = :s", "s=active")->count(),
                "apartments" => (new AppApartment())->find("status = :s", "s=active")->count(),
                "subscriptions" => (new AppSubscription())->find("status = :s", "s=active")->count(),
                "invoices" => (new AppInvoice())->find()->count()
            ],
            "subscriptions" => (new AppSubscription())->find()->order("created_at")->limit(10)->fetch(true)
        ]);
    }

    /**
     * @param array|null $data
     */
    public function subscriptions(?array $data): void {
        //search redirect
        if (!empty($data["s"])) {
            $s = str_search($data["s"]);
            echo json_encode(["redirect" => url("/admin/controle/assinaturas/{$s}/1")]);
            return;
        }

        $search = null;
        $subscriptions = (new AppSubscription())->find();

        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
            $search = str_search($data["search"]);
            $subscriptions = (new AppSubscription())
                    ->find("user_id IN(SELECT id FROM users WHERE MATCH(first_name, last_name, email) AGAINST(:s))", "s=%{$search}%");
            if (!$subscriptions->count()) {
                $this->message->info("Sua pesquisa não retornou resultados")->flash();
                redirect("/admin/controle/assinaturas");
            }
        }

        $all = ($search ?? "all");
        $pager = new Pager(url("/admin/controle/assinaturas/{$all}/"));
        $pager->pager($subscriptions->count(), 12, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
                CONF_SITE_NAME . " | Assinaturas",
                CONF_SITE_DESC,
                url("/admin"),
                theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
                false
        );

        echo $this->view->render("widgets/control/subscriptions", [
            "app" => "control/subscriptions",
            "head" => $head,
            "subscriptions" => $subscriptions->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render(),
            "search" => $search
        ]);
    }

    /**
     * @param array $data
     */
    public function subscription(array $data): void {
        //create subscription
        if (!empty($data["action"]) && $data["action"] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $subscriptionCreate = new AppSubscription();
            $subscriptionCreate->apartment_id = $data["apartment_id"];
            $subscriptionCreate->user_id = $data["user_id"];
            $subscriptionCreate->status = "active";

            if (!$subscriptionCreate->save()) {
                $json["message"] = $subscriptionCreate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Assinatura criada com sucesso. Confira...")->flash();
            $json["redirect"] = url("/admin/controle/assinatura/{$subscriptionCreate->id}");

            echo json_encode($json);
            return;
        }

        //update subscription
        if (!empty($data["action"]) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $subscriptionUpdate = (new AppSubscription())->findById($data["id"]);

            if (!$subscriptionUpdate) {
                $this->message->error("Você tentou atualizar uma assinatura que não existe")->flash();
                echo json_encode(["redirect" => url("/admin/controle/assinaturas")]);
                return;
            }

            $subscriptionUpdate->apartment_id = ($data["apartment_id"] ?? null);
            $subscriptionUpdate->user_id = ($data["user_id"] ?? null);
            $subscriptionUpdate->status = $data["status"];

            if (!$subscriptionUpdate->save()) {
                $json["message"] = $subscriptionUpdate->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("Assinatura atualizada com sucesso")->render();
            echo json_encode($json);
            return;
        }

        //delete subscription
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $subscriptionDelete = (new AppSubscription())->findById($data["subscription_id"]);

            if (!$subscriptionDelete) {
                $this->message->error("Você tentou excluir um Assinate que não existe ou já foi removido")->flash();
                echo json_encode(["redirect" => url("/admin/controle/assinatuas")]);
                return;
            }

            $subscriptionDelete->destroy();
            $this->message->success("Assinatura removida com sucesso...")->flash();
            $json["redirect"] = url("/admin/controle/assinaturas");

            echo json_encode($json);
            return;
        }

        //read subscription
        $subscription = null;
        if (!empty($data["id"])) {
            $subscriptionId = filter_var($data["id"], FILTER_VALIDATE_INT);
            $subscription = (new AppSubscription())->findById($subscriptionId);
            $user = (new User())->findById($subscription->user_id);
        }

        $head = $this->seo->render(
                CONF_SITE_NAME . " | " . ($subscription ? $subscription->user()->fullName() : "Nova Assinatura"),
                CONF_SITE_DESC,
                url("/admin"),
                theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
                false
        );

        echo $this->view->render("widgets/control/subscription", [
            "app" => "control/subscriptions",
            "head" => $head,
            "subscription" => $subscription,
            "users" => (new User())->find("status = :status AND level = 1", "status=confirmed")->order("first_name, last_name")->fetch(true),
            "apartments" => (new AppApartment())->find("status = :status", "status=active")->order("condominium_id, block, number")->fetch(true),
            "user" => $user ?? null,
            "candidate_users" => (new User())->find("status = 'confirmed' AND level = 1 AND id NOT IN (SELECT user_id FROM app_subscription WHERE status = 'active')")->order("first_name, last_name")->fetch(true)
        ]);
    }

    /**
     * @param array|null $data
     */
    public function subscriptionsCondominium(?array $data): void {
        //search redirect
        if (!empty($data["s"])) {
            $s = str_search($data["s"]);
            echo json_encode(["redirect" => url("/admin/controle/gerentes/{$s}/1")]);
            return;
        }

        $search = null;
        $subscriptions = (new AppSubscriptionCondominium())->find();

        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
            $search = str_search($data["search"]);
            $subscriptions = (new AppSubscriptionCondominium())
                    ->find("user_id IN(SELECT id FROM users WHERE MATCH(first_name, last_name, email) AGAINST(:s))", "s=%{$search}%");
            if (!$subscriptions->count()) {
                $this->message->info("Sua pesquisa não retornou resultados")->flash();
                redirect("/admin/controle/gerentes");
            }
        }

        $all = ($search ?? "all");
        $pager = new Pager(url("/admin/controle/gerentes/{$all}/"));
        $pager->pager($subscriptions->count(), 12, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
                CONF_SITE_NAME . " | Gerentes",
                CONF_SITE_DESC,
                url("/admin"),
                theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
                false
        );

        echo $this->view->render("widgets/control/subscriptionsCondominium", [
            "app" => "control/subscriptionsCondominium",
            "head" => $head,
            "subscriptions" => $subscriptions->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render(),
            "search" => $search
        ]);
    }

    /**
     * @param array $data
     */
    public function subscriptionCondominium(array $data): void {
        //create subscription
        if (!empty($data["action"]) && $data["action"] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $subscriptionCreate = new AppSubscriptionCondominium();
            $subscriptionCreate->condominium_id = $data["condominium_id"];
            $subscriptionCreate->user_id = $data["user_id"];
            $subscriptionCreate->status = "active";

            if (!$subscriptionCreate->save()) {
                $json["message"] = $subscriptionCreate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Gerente criado com sucesso. Confira...")->flash();
            $json["redirect"] = url("/admin/controle/gerente/{$subscriptionCreate->id}");

            echo json_encode($json);
            return;
        }

        //update subscription
        if (!empty($data["action"]) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $subscriptionUpdate = (new AppSubscriptionCondominium())->findById($data["id"]);

            if (!$subscriptionUpdate) {
                $this->message->error("Você tentou atualizar um gerente que não existe")->flash();
                echo json_encode(["redirect" => url("/admin/controle/gerentes")]);
                return;
            }

            $subscriptionUpdate->condominium_id = ($data["condominium_id"] ?? null);
            $subscriptionUpdate->user_id = ($data["user_id"] ?? null);
            $subscriptionUpdate->status = $data["status"];

            if (!$subscriptionUpdate->save()) {
                $json["message"] = $subscriptionUpdate->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("Gerente atualizado com sucesso")->render();
            echo json_encode($json);
            return;
        }

        //delete subscription
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $subscriptionDelete = (new AppSubscriptionCondominium())->findById($data["subscription_id"]);

            if (!$subscriptionDelete) {
                $this->message->error("Você tentou excluir um Gerente que não existe ou já foi removido")->flash();
                echo json_encode(["redirect" => url("/admin/controle/gerentes")]);
                return;
            }

            $subscriptionDelete->destroy();
            $this->message->success("Gerente removido com sucesso...")->flash();
            $json["redirect"] = url("/admin/controle/gerentes");

            echo json_encode($json);
            return;
        }

        //read subscription
        $subscription = null;
        if (!empty($data["id"])) {
            $subscriptionId = filter_var($data["id"], FILTER_VALIDATE_INT);
            $subscription = (new AppSubscriptionCondominium())->findById($subscriptionId);
            $user = (new User())->findById($subscription->user_id);
        }

        $head = $this->seo->render(
                CONF_SITE_NAME . " | " . ($subscription ? $subscription->user()->fullName() : "Novo Gerente"),
                CONF_SITE_DESC,
                url("/admin"),
                theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
                false
        );

        echo $this->view->render("widgets/control/subscriptionCondominium", [
            "app" => "control/subscriptionsCondominium",
            "head" => $head,
            "subscription" => $subscription,
            "users" => (new User())->find("status = :status AND level = 2", "status=confirmed")->order("first_name, last_name")->fetch(true),
            "condominiuns" => (new AppCondominium())->find("status = :status", "status=active")->order("name")->fetch(true),
            "user" => $user ?? null,
            "candidate_users" => (new User())->find("status = :status AND level = 2 AND id NOT IN (SELECT user_id FROM app_subscription_condominium WHERE status = 'active')", "status=confirmed")->order("first_name, last_name")->fetch(true),
        ]);
    }

    /**
     * @param array|null $data
     */
    public function invoices(?array $data): void {
        //search redirect
        if (!empty($data["s"])) {
            $s = str_search($data["s"]);
            echo json_encode(["redirect" => url("/admin/controle/demonstrativos/{$s}/1")]);
            return;
        }

        $search = null;
        $invoices = (new AppInvoice())->find();

        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
            $search = str_search($data["search"]);

            $subscriptions = (new AppSubscription())
                    ->find("user_id IN(SELECT id FROM users WHERE MATCH(first_name, last_name, email) AGAINST(:s))", "s=%{$search}%");

            if (!$subscriptions->count()) {
                $this->message->info("Sua pesquisa não retornou resultados")->flash();
                redirect("/admin/controle/demonstrativos");
            }

            $invoices = (new AppInvoice())->find("subscription_id = :subscription_id", "subscription_id={$subscriptions->fetch()->id}");

            if (!$invoices->count()) {
                $this->message->info("Sua pesquisa não retornou resultados")->flash();
                redirect("/admin/controle/demonstrativos");
            }
        }

        $all = ($search ?? "all");
        $pager = new Pager(url("/admin/controle/demonstrativos/{$all}/"));
        $pager->pager($invoices->count(), 12, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
                CONF_SITE_NAME . " | Demonstrativos",
                CONF_SITE_DESC,
                url("/admin"),
                theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
                false
        );

        echo $this->view->render("widgets/control/invoices", [
            "app" => "control/invoices",
            "head" => $head,
            "invoices" => $invoices->order("id DESC")->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render(),
            "search" => $search
        ]);
    }

    /**
     * @param array|null $data
     */
    public function invoicesCondominium(?array $data): void {
        //search redirect
        if (!empty($data["s"])) {
            $s = str_search($data["s"]);
            echo json_encode(["redirect" => url("/admin/controle/balancos/{$s}/1")]);
            return;
        }
        $search = null;
        $invoices = (new AppInvoiceCondominium())->find();
        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
            $search = str_search($data["search"]);
            $invoices = (new AppInvoiceCondominium())->find("condominium_id IN(SELECT id FROM app_condominium WHERE name LIKE :s)", "s=%{$search}%");
            if (!$invoices->count()) {
                $this->message->info("Sua pesquisa não retornou resultados")->flash();
                redirect("/admin/controle/balancos");
            }
        }

        $all = ($search ?? "all");
        $pager = new Pager(url("/admin/controle/balancos/{$all}/"));
        $pager->pager($invoices->count(), 12, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
                CONF_SITE_NAME . " | Balanços",
                CONF_SITE_DESC,
                url("/admin"),
                theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
                false
        );

        echo $this->view->render("widgets/control/invoicesCondominium", [
            "app" => "control/invoicesCondominium",
            "head" => $head,
            "invoices" => $invoices->order("id DESC")->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render(),
            "search" => $search
        ]);
    }

    /**
     * @param array $data
     */
    public function invoice(array $data): void {
        //update
        if (!empty($data["action"]) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $invoiceUpdate = (new AppInvoice())->findById($data["invoice_id"]);

            if (!$invoiceUpdate) {
                $this->message->error("Você tentou atualizar um demonstrativo que não existe")->flash();
                echo json_encode(["redirect" => url("/admin/controle/demonstrativos")]);
                return;
            }
            //upload photo
            if (!empty($_FILES["cover"])) {
                if ($invoiceUpdate->photo && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$invoiceUpdate->cover}")) {
                    unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$invoiceUpdate->cover}");
                    (new Thumb())->flush($invoiceUpdate->cover);
                }

                $files = $_FILES["cover"];
                $upload = new Upload();
                $image = $upload->image($files, $invoiceUpdate->id, 600);

                if (!$image) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $invoiceUpdate->cover = $image;
            }

            if (!$invoiceUpdate->save()) {
                $json["message"] = $userUpdate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Demonstrativo atualizado com sucesso...")->flash();
            echo json_encode(["reload" => true]);
            return;
        }

        $invoiceEdit = null;
        if (!empty($data["invoice_id"])) {
            $invoiceId = filter_var($data["invoice_id"], FILTER_VALIDATE_INT);
            $invoiceEdit = (new AppInvoice())->findById($invoiceId);
        }

        $head = $this->seo->render(
                CONF_SITE_NAME . " | " . ($invoiceEdit ? "Demonstrativo nº {$invoiceEdit->id}" : "Novo Demonstrativo"),
                CONF_SITE_DESC,
                url("/admin"),
                url("/admin/assets/images/image.jpg"),
                false
        );

        echo $this->view->render("widgets/control/invoice", [
            "app" => "invoices/invoice",
            "head" => $head,
            "invoice" => $invoiceEdit
        ]);
    }

    /**
     * @param array $data
     */
    public function invoiceCondominium(array $data): void {

        //create invoiceCondominium
        if (!empty($data["action"]) && $data["action"] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $invoiceCondominiumCreate = new AppInvoiceCondominium();
            $invoiceCondominiumCreate->condominium_id = $data["condominium_id"];
            $invoiceCondominiumCreate->month_ref = date_fmt_back($data["month_ref"]);
            $invoiceCondominiumCreate->due_at = date_fmt_back($data["due_at"]);
            $invoiceCondominiumCreate->report_at = date_fmt_back($data["report_at"]);
            $invoiceCondominiumCreate->description = $data["description"];
            $invoiceCondominiumCreate->description_dealership = $data["description_dealership"];
            $invoiceCondominiumCreate->expiration = date_fmt_back($data["expiration"]);
            $invoiceCondominiumCreate->consumption = str_replace(",", ".", $data["consumption"]);
            $invoiceCondominiumCreate->value = str_replace(",", ".", $data["value"]);
            $invoiceCondominiumCreate->value_per_m3 = str_replace(",", ".", $data["value_per_m3"]);
            $invoiceCondominiumCreate->sum_consumption = str_replace(",", ".", $data["sum_consumption"]);
            $invoiceCondominiumCreate->individual_value = str_replace(",", ".", $data["individual_value"]);
            $invoiceCondominiumCreate->reading_common_area = str_replace(",", ".", $data["reading_common_area"]);
            $invoiceCondominiumCreate->common_area = str_replace(",", ".", $data["common_area"]);
            $invoiceCondominiumCreate->charge = $data["sewer"] == null ? "0.00" : str_replace(",", ".", $data["sewer"]);
            $invoiceCondominiumCreate->charge = $data["charge"] == null ? "0.00" : str_replace(",", ".", $data["charge"]);
            $invoiceCondominiumCreate->budget = $data["budget"] == null ? "0.00" : str_replace(",", ".", $data["budget"]);
            $invoiceCondominiumCreate->tax_revenues = $data["tax_revenues"] == null ? "0.00" : str_replace(",", ".", $data["tax_revenues"]);
            $invoiceCondominiumCreate->observation = $data["observation"];

            if (!$invoiceCondominiumCreate->save()) {
                $json["message"] = $invoiceCondominiumCreate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Balanço criado com sucesso. Confira...")->flash();
            $json["redirect"] = url("/admin/controle/balanco/{$invoiceCondominiumCreate->id}");

            echo json_encode($json);
            return;
        }

        //update invoiceCondominium
        if (!empty($data["action"]) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $invoiceCondominiumUpdate = (new AppInvoiceCondominium())->findById($data["id"]);
            if (!$invoiceCondominiumUpdate) {
                $this->message->error("Você tentou atualizar um balanço que não existe")->flash();
                echo json_encode(["redirect" => url("/admin/controle/balancos")]);
                return;
            }
            $invoiceCondominiumUpdate->month_ref = date_fmt_back($data["month_ref"]);
            $invoiceCondominiumUpdate->due_at = date_fmt_back($data["due_at"]);
            $invoiceCondominiumUpdate->report_at = date_fmt_back($data["report_at"]);
            $invoiceCondominiumUpdate->description = $data["description"];
            $invoiceCondominiumUpdate->description_dealership = $data["description_dealership"];
            $invoiceCondominiumUpdate->expiration = date_fmt_back($data["expiration"]);
            $invoiceCondominiumUpdate->consumption = str_replace(",", ".", $data["consumption"]);
            $invoiceCondominiumUpdate->value = str_replace(",", ".", $data["value"]);
            $invoiceCondominiumUpdate->value_per_m3 = str_replace(",", ".", $data["value_per_m3"]);
            $invoiceCondominiumUpdate->sum_consumption = str_replace(",", ".", $data["sum_consumption"]);
            $invoiceCondominiumUpdate->individual_value = str_replace(",", ".", $data["individual_value"]);
            $invoiceCondominiumUpdate->reading_common_area = str_replace(",", ".", $data["reading_common_area"]);
            $invoiceCondominiumUpdate->common_area = str_replace(",", ".", $data["common_area"]);
            $invoiceCondominiumUpdate->sewer = str_replace(",", ".", $data["sewer"]);
            $invoiceCondominiumUpdate->charge = str_replace(",", ".", $data["charge"]);
            $invoiceCondominiumUpdate->budget = str_replace(",", ".", $data["budget"]);
            $invoiceCondominiumUpdate->tax_revenues = str_replace(",", ".", $data["tax_revenues"]);
            $invoiceCondominiumUpdate->observation = $data["observation"];

            if (!$invoiceCondominiumUpdate->save()) {
                $json["message"] = $invoiceCondominiumUpdate->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("Balanço atualizado com sucesso")->render();
            echo json_encode($json);
            return;
        }

        //delete invoiceCondominium
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $invoiceCondominiumDelete = (new AppInvoiceCondominium())->findById($data["subscription_id"]);

            if (!$invoiceCondominiumDelete) {
                $this->message->error("Você tentou excluir um Balanço que não existe ou já foi removido")->flash();
                echo json_encode(["redirect" => url("/admin/controle/balancos")]);
                return;
            }

            $invoiceCondominiumDelete->destroy();
            $this->message->success("Balanço removido com sucesso...")->flash();
            $json["redirect"] = url("/admin/controle/balancos");

            echo json_encode($json);
            return;
        }

        //read invoiceCondominium
        $invoiceCondominium = null;
        if (!empty($data["id"])) {
            $invoiceCondominiumId = filter_var($data["id"], FILTER_VALIDATE_INT);
            $invoiceCondominium = (new AppInvoiceCondominium())->findById($invoiceCondominiumId);
        }

        $head = $this->seo->render(
                CONF_SITE_NAME . " | " . ($invoiceCondominium ? "Balanço nº {$invoiceCondominium->id}" : "Novo Balanço"),
                CONF_SITE_DESC,
                url("/admin"),
                theme("/assets/images/image.jpg", CONF_VIEW_ADMIN),
                false
        );

        $condominiuns = (new AppCondominium())->find("status = 'active'")->fetch(true);

        echo $this->view->render("widgets/control/invoiceCondominium", [
            "app" => "control/invoicesCondominium",
            "head" => $head,
            "invoiceCondominium" => $invoiceCondominium,
            "condominiuns" => $condominiuns
        ]);
    }

    /**
     * @param array|null $data
     */
    public function condominiums(?array $data): void {
        $condominiums = (new AppCondominium())->find();
        $pager = new Pager(url("/admin/controle/condominios/"));
        $pager->pager($condominiums->count(), 5, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
                CONF_SITE_NAME . " | Condomínios",
                CONF_SITE_DESC,
                url("/admin"),
                url("/admin/assets/images/image.jpg"),
                false
        );

        echo $this->view->render("widgets/control/condominiums", [
            "app" => "control/condominiums",
            "head" => $head,
            "condominiums" => $condominiums->order("id")->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * @param array|null $data
     */
    public function condominium(?array $data): void {
        //create condominium
        if (!empty($data["action"]) && $data["action"] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $condominiumCreate = new AppCondominium();
            $condominiumCreate->name = $data["name"];
            $condominiumCreate->address = $data["address"];
            $condominiumCreate->dealership = $data["dealership"];
            $condominiumCreate->status = $data["status"];
            $condominiumCreate->alias = $data["alias"];

            if (!$condominiumCreate->save()) {
                $json["message"] = $condominiumCreate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Condomínio criado com sucesso. Confira...")->flash();
            $json["redirect"] = url("/admin/controle/condominio/{$condominiumCreate->id}");

            echo json_encode($json);
            return;
        }

        //update condominium
        if (!empty($data["action"]) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $condominiumEdit = (new AppCondominium())->findById($data["condominium_id"]);

            if (!$condominiumEdit) {
                $this->message->error("Você tentou editar um condomínio que não existe ou foi removido")->flash();
                echo json_encode(["redirect" => url("/admin/controle/condominios")]);
                return;
            }

            $condominiumEdit->name = $data["name"];
            $condominiumEdit->address = $data["address"];
            $condominiumEdit->dealership = $data["dealership"];
            $condominiumEdit->status = $data["status"];
            $condominiumEdit->alias = ($data["alias"] ?? null);

            if (!$condominiumEdit->save()) {
                $json["message"] = $condominiumEdit->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("Condomínio atualizado com sucesso...")->render();
            echo json_encode($json);

            return;
        }

        //delete condominium
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $condominiumDelete = (new AppCondominium())->findById($data["condominium_id"]);

            if (!$condominiumDelete) {
                $this->message->error("Você tentou excluir um condomínio que não existe ou já foi removido")->flash();
                echo json_encode(["redirect" => url("/admin/controle/condominios")]);
                return;
            }

            if ($condominiumDelete->subscribers()) {
                $json["message"] = $this->message->warning("Não é possível remover condominios com usuários...")->render();
                echo json_encode($json);
                return;
            }

            $condominiumDelete->destroy();
            $this->message->success("Condomínio removido com sucesso...")->flash();
            $json["redirect"] = url("/admin/controle/condominios");

            echo json_encode($json);
            return;
        }

        //read condominium
        $condominiumEdit = null;
        if (!empty($data["condominium_id"])) {
            $condominiumId = filter_var($data["condominium_id"], FILTER_VALIDATE_INT);
            $condominiumEdit = (new AppCondominium())->findById($condominiumId);
        }

        $head = $this->seo->render(
                CONF_SITE_NAME . " | Gerenciar Condomínio",
                CONF_SITE_DESC,
                url("/admin"),
                url("/admin/assets/images/image.jpg"),
                false
        );

        echo $this->view->render("widgets/control/condominium", [
            "app" => "control/condominiums",
            "head" => $head,
            "condominium" => $condominiumEdit,
            "subscribers" => ($condominiumEdit ? $condominiumEdit->subscribers() : null)
        ]);
    }

    /**
     * @param array|null $data
     */
    public function apartments(?array $data): void {
        $apartments = (new AppApartment())->find();
        $pager = new Pager(url("/admin/controle/apartamentos/"));
        $pager->pager($apartments->count(), 5, (!empty($data["page"]) ? $data["page"] : 1));

        $head = $this->seo->render(
                CONF_SITE_NAME . " | Apartamentos",
                CONF_SITE_DESC,
                url("/admin"),
                url("/admin/assets/images/image.jpg"),
                false
        );

        echo $this->view->render("widgets/control/apartments", [
            "app" => "control/apartments",
            "head" => $head,
            "apartments" => $apartments->order("created_at DESC, condominium_id, block, number")->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render()
        ]);
    }

    /**
     * @param array|null $data
     */
    public function apartment(?array $data): void {
        //create apartment
        if (!empty($data["action"]) && $data["action"] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $aparmentCreate = new AppApartment();
            $aparmentCreate->block = $data["block"];
            $aparmentCreate->number = $data["number"];
            $aparmentCreate->hydrometer = $data["hydrometer"];
            $aparmentCreate->location = $data["location"];
            $aparmentCreate->condominium_id = $data["condominium_id"];
            $aparmentCreate->status = $data["status"];

            if (!$aparmentCreate->save()) {
                $json["message"] = $aparmentCreate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Apartamento criado com sucesso. Confira...")->flash();
            $json["redirect"] = url("/admin/controle/apartamento/{$aparmentCreate->id}");

            echo json_encode($json);
            return;
        }

        //update apartment
        if (!empty($data["action"]) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $apartmentEdit = (new AppApartment())->findById($data["apartment_id"]);

            if (!$apartmentEdit) {
                $this->message->error("Você tentou editar um apartamento que não existe ou foi removido")->flash();
                echo json_encode(["redirect" => url("/admin/controle/apartamentos")]);
                return;
            }

            $apartmentEdit->block = $data["block"];
            $apartmentEdit->number = $data["number"];
            $apartmentEdit->hydrometer = $data["hydrometer"];
            $apartmentEdit->location = $data["location"];
            $apartmentEdit->condominium_id = $data["condominium_id"];
            $apartmentEdit->status = $data["status"];

            if (!$apartmentEdit->save()) {
                $json["message"] = $apartmentEdit->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("Apartamento atualizado com sucesso...")->render();
            echo json_encode($json);
            return;
        }

        //delete apartment
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            if ($data["apartment_id"] == null) {
                $this->message->error("Você tentou excluir um apartamento que não existe ou já foi removido")->flash();
                echo json_encode(["redirect" => url("/admin/controle/apartamentos")]);
                return;
            }

            $apartmentDelete = (new AppApartment())->findById($data["apartment_id"]);

            if (!$apartmentDelete) {
                $this->message->error("Você tentou excluir um apartamento que não existe ou já foi removido")->flash();
                echo json_encode(["redirect" => url("/admin/controle/apartamentos")]);
                return;
            }

            if ($apartmentDelete->subscribers(null)->count()) {
                $json["message"] = $this->message->warning("Não é possível remover apartamento com usuários...")->render();
                echo json_encode($json);
                return;
            }

            $apartmentDelete->destroy();
            $this->message->success("Apartamento removido com sucesso...")->flash();
            $json["redirect"] = url("/admin/controle/apartamentos");

            echo json_encode($json);
            return;
        }

        //read apartment
        $apartmentEdit = null;
        if (!empty($data["apartment_id"])) {
            $apartmentId = filter_var($data["apartment_id"], FILTER_VALIDATE_INT);
            $apartmentEdit = (new AppApartment())->findById($apartmentId);
        }

        $head = $this->seo->render(
                CONF_SITE_NAME . " | Gerenciar Apartamento",
                CONF_SITE_DESC,
                url("/admin"),
                url("/admin/assets/images/image.jpg"),
                false
        );

        echo $this->view->render("widgets/control/apartment", [
            "app" => "control/apartments",
            "head" => $head,
            "apartment" => $apartmentEdit,
            "condominiuns" => (new AppCondominium())->find("status = :status", "status=active")->order("id")->fetch(true),
            "subscribers" => ($apartmentEdit ? $apartmentEdit->subscribers(null)->count() : null)
        ]);
    }

    public function import($data) {
        $folder = __DIR__ . "/upload";
        if (!file_exists($folder) || !is_dir($folder)) {
            mkdir($folder, 0755);
        }
        $getPost = filter_input(INPUT_GET, "post", FILTER_VALIDATE_BOOLEAN);
        if ($_FILES && !empty($_FILES['file']['name'])) {
            $fileUpload = $_FILES['file'];
            $allowedtype = ['text/csv', 'application/vnd.ms-excel'];
            $newFilename = 'import' . mb_strstr($fileUpload['name'], ".");
            if (in_array($fileUpload['type'], $allowedtype)) {
                if (move_uploaded_file($fileUpload['tmp_name'], __DIR__ . "/upload/{$newFilename}")) {
                    $file = new \SplFileObject(__DIR__ . "/upload/import.csv");
                    $reader = new CsvReader($file, ';');
                    $reader->setHeaderRowNumber(0);
                    foreach ($reader as $row) {
                        $invoices = new AppInvoice();
                        $invoices->subscription_id = $row["assinatura"];
                        $invoices->due_at = date_fmt_back($row["data_da_leitura"], "Y-m-d");
                        $invoices->description = $row["descricao"];
                        $invoices->reading = $row["leitura"] == null ? "0.00" : str_replace(",", ".", $row["leitura"]);
                        $invoices->estimated_reading = $row["leitura_estimada"] == null ? "0.00" : str_replace(",", ".", $row["leitura_estimada"]);
                        $invoices->consumption = $row["consumo_individual"] == null ? "0.00" : str_replace(",", ".", $row["consumo_individual"]);
                        $invoices->consumption_condominium = $row["consumo_do_condominio"] == null ? "0.00" : str_replace(",", ".", $row["consumo_do_condominio"]);
                        $invoices->individual = $row["valor_individual"] == null ? "0.00" : str_replace(",", ".", $row["valor_individual"]);
                        $invoices->common_area = $row["area_comum"] == null ? "0.00" : str_replace(",", ".", $row["area_comum"]);
                        $invoices->value_per_m3 = $row["valor_por_m3"] == null ? "0.00" : str_replace(",", ".", $row["valor_por_m3"]);
                        $invoices->tax = $row["taxa"] == null ? "0.00" : str_replace(",", ".", $row["taxa"]);
                        $invoices->value = $row["valor_total"] == null ? "0.00" : str_replace(",", ".", $row["valor_total"]);
                        $invoices->value_condominium = $row["valor_total_condominio"] == null ? "0.00" : str_replace(",", ".", $row["valor_total_condominio"]);
                        $invoices->observation = $row["observacao"] == null ? null : $row["observacao"];
                        $invoices->detail_1 = $row["hidrometro_1"] == null ? null : str_replace(",", ".", $row["hidrometro_1"]);
                        $invoices->detail_2 = $row["hidrometro_2"] == null ? null : str_replace(",", ".", $row["hidrometro_2"]);
                        $invoices->detail_3 = $row["hidrometro_3"] == null ? null : str_replace(",", ".", $row["hidrometro_3"]);
                        $invoices->detail_4 = $row["hidrometro_4"] == null ? null : str_replace(",", ".", $row["hidrometro_4"]);
                        $invoices->detail_5 = $row["hidrometro_5"] == null ? null : str_replace(",", ".", $row["hidrometro_5"]);
                        $invoices->detail_6 = $row["hidrometro_6"] == null ? null : str_replace(",", ".", $row["hidrometro_6"]);
                        $invoices->detail_7 = $row["hidrometro_7"] == null ? null : str_replace(",", ".", $row["hidrometro_7"]);
                        $invoices->detail_8 = $row["hidrometro_8"] == null ? null : str_replace(",", ".", $row["hidrometro_8"]);
                        $invoices->detail_9 = $row["hidrometro_9"] == null ? null : str_replace(",", ".", $row["hidrometro_9"]);
                        $invoices->detail_10 = $row["hidrometro_10"] == null ? null : str_replace(",", ".", $row["hidrometro_10"]);
                        $invoices->save();
                    }
                    $this->message->success("Demonstrativos importados com sucesso!")->flash();
                    $json["redirect"] = url("/admin/controle/demonstrativos");
                    echo json_encode($json);
                    return;
                }
            } else {
                $json["message"] = $this->message->error("Tipo de arquivo não permitido")->render();
                echo json_encode($json);
                return;
            }
        } elseif ($getPost) {
            $json["message"] = $this->message->error("Tamanho  do arquivo acima da capacidade do servidor")->render();
            echo json_encode($json);
            return;
        } else {
            $json["message"] = $this->message->warning("Nenhum arquivo válido foi selecionado")->render();
            echo json_encode($json);
            return;
        }
    }

    public function photos() {
        $files = $_FILES;
        $upload = new Upload();

        if (!empty($files['images'])) {
            $images = $files['images'];

            for ($i = 0; $i < count($images['type']); $i++) {
                foreach (array_keys($images) as $keys) {
                    $imagesFile[$i][$keys] = $images[$keys][$i];
                }
            }

            foreach ($imagesFile as $file) {
                if (empty($file['type'])) {
                    $json["message"] = $this->message->error("Selecione um tipo imagem válida")->render();
                    echo json_encode($json);
                    return;
                } else {
                    $search = array_values(array_filter(explode(" ", pathinfo($file['name'], PATHINFO_FILENAME))));

                    if (count($search) != 3) {
                        $json["message"] = $this->message->error("Imagens com nome fora do padrão")->render();
                        echo json_encode($json);
                        return;
                    }
                    
                    $data_alias = $search[0];
                    $data_block = $search[1];
                    $data_number = $search[2];

                    $condominum = (new AppCondominium())->find("alias = '{$data_alias}'")->fetch();

                    if (empty($condominum)) {
                        $json["message"] = $this->message->error("Condomínio não encontrado para o arquivo {$file['name']}")->render();
                        echo json_encode($json);
                        return;
                    }

                    $apartment = (new AppApartment())
                            ->find("condominium_id = '{$condominum->id}' AND block = '{$data_block}'  AND number = '{$data_number}'")
                            ->fetch();

                    if (empty($apartment)) {
                        $json["message"] = $this->message->error("Apartamento não encontrado para o arquivo {$file['name']}")->render();
                        echo json_encode($json);
                        return;
                    }

                    $subscription = (new AppSubscription())->find("apartment_id = '{$apartment->id}'")->fetch();

                    if (empty($subscription)) {
                        $json["message"] = $this->message->error("Assinatura não encontrada para o arquivo {$file['name']}")->render();
                        echo json_encode($json);
                        return;
                    }

                    $invoice = (new AppInvoice())->find("subscription_id = '{$subscription->id}'")->order("id DESC")->limit(1)->fetch();

                    if (empty($invoice)) {
                        $json["message"] = $this->message->error("Demonstrativo não encontrado para o arquivo {$file['name']}")->render();
                        echo json_encode($json);
                        return;
                    }

                    $image = $upload->image($file, $invoice->id, 600);

                    if (!$image) {
                        $json["message"] = $upload->message()->render();
                        echo json_encode($json);
                        return;
                    }

                    if ($invoice->cover && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$invoice->cover}")) {
                        unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$invoice->cover}");
                        (new Thumb())->flush($invoice->cover);
                    }

                    $invoice->cover = $image;
                    $invoice->save();
                }
            }
            $json["message"] = $this->message->success("Imagens armazenadas com sucesso!")->render();
            echo json_encode($json);
            return;
        } else {
            $json["message"] = $this->message->warning("Nenhum arquivo válido foi selecionado")->render();
            echo json_encode($json);
            return;
        }
    }

}
