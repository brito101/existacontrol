<?php

namespace Source\Models\WaterApp;

use Source\Core\Model;
use Source\Models\User;
use Source\Models\WaterApp\AppSubscriptionCondominium;

/**
 * Class AppInvoiceCondominium
 * @package Source\Models\WarterApp
 */
class AppInvoiceCondominium extends Model
{

    /**
     * AppInvoice constructor.
     */
    public function __construct()
    {
        parent::__construct("app_invoices_condominium", ["id"], ["condominium_id"]);
    }

    /**
     * @param User $user
     * @return object
     */
    public function balanceCondominium(User $user): object
    {
        $subscription = (new AppSubscriptionCondominium())->find("user_id = :user_id", "user_id={$user->id}")->fetch();

        $balance = new \stdClass();
        $balance->consumption = 0;
        $balance->value = 0;


        if (!empty($subscription->id)) {
            $find = $this->find(
                "condominium_id = :condominium_id",
                "condominium_id={$subscription->condominium_id}",
                "
                (SELECT AVG(consumption) FROM app_invoices_condominium WHERE condominium_id = {$subscription->condominium_id}) AS consumption,
                (SELECT AVG(value) FROM app_invoices_condominium WHERE condominium_id = {$subscription->condominium_id}) AS value"
            )->fetch();

            if ($find) {
                $balance->consumption = abs($find->consumption);
                $balance->value = abs($find->value);
            }
        }

        return $balance;
    }

    /**
     * @param User $user
     * @return object
     */
    public function chartDataCondominium(User $user): object
    {
        $dateChart = [];
        for ($month = -5; $month <= 0; $month++) {
            $dateChart[] = date("m/Y", strtotime("{$month}month"));
        }

        $chartData = new \stdClass();
        $chartData->categories = "'" . implode("','", $dateChart) . "'";
        $chartData->expense = "0,0,0,0,0";
        $chartData->income = "0,0,0,0,0";
        $chartData->tax = "0,0,0,0,0";

        $subscription = (new AppSubscriptionCondominium())->find("user_id = :user_id", "user_id={$user->id}")->fetch();

        if (!empty($subscription->id)) {
            $chart = (new AppInvoiceCondominium())
                ->find(
                    "condominium_id = :condominium_id AND month_ref >= DATE(now() - INTERVAL 5 MONTH) GROUP BY year(month_ref) ASC, month(month_ref) ASC",
                    "condominium_id={$subscription->condominium_id}",
                    "
                    year(month_ref) AS due_year,
                    month(month_ref) AS due_month,
                    DATE_FORMAT(month_ref, '%m/%Y') AS due_date,
                    (SELECT value FROM app_invoices_condominium WHERE condominium_id = :condominium_id AND year(month_ref) = due_year AND month(month_ref) = due_month) AS expense,
                    (SELECT consumption FROM app_invoices_condominium WHERE condominium_id = :condominium_id AND year(month_ref) = due_year AND month(month_ref) = due_month) AS income,
                    (SELECT common_area FROM app_invoices_condominium WHERE condominium_id = :condominium_id AND year(month_ref) = due_year AND month(month_ref) = due_month) AS tax
                "
                )->limit(6)->fetch(true);
            if ($chart) {
                $chartCategories = [];
                $chartExpense = [];
                $chartIncome = [];

                foreach ($chart as $chartItem) {
                    $chartCategories[] = $chartItem->due_date;
                    $chartExpense[] = $chartItem->expense;
                    $chartIncome[] = $chartItem->income;
                    $chartTax[] = $chartItem->tax;
                }

                $chartData->categories = "'" . implode("','", $chartCategories) . "'";
                $chartData->expense = implode(",", array_map("abs", $chartExpense));
                $chartData->income = implode(",", array_map("abs", $chartIncome));
                $chartData->tax = implode(",", array_map("abs", $chartTax));
            }
        }

        return $chartData;
    }
}
