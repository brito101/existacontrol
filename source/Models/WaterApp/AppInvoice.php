<?php

namespace Source\Models\WaterApp;

use Source\Core\Model;
use Source\Models\User;
use Source\Models\WaterApp\AppSubscription;

/**
 * Class AppInvoice
 * @package Source\Models\WarterApp
 */
class AppInvoice extends Model
{

    /**
     * AppInvoice constructor.
     */
    public function __construct()
    {
        parent::__construct(
            "app_invoices",
            ["id"],
            [
                "subscription_id", "description", "due_at"
            ]
        );
    }

    /**
     * @param User $user
     * @return object
     */
    public function balance(User $user): object
    {
        $subscription = (new AppSubscription())->find("user_id = :user_id", "user_id={$user->id}")->fetch();

        $balance = new \stdClass();
        $balance->consumption = 0;
        $balance->individual = 0;
        $balance->value = 0;


        if (!empty($subscription->id)) {
            $find = $this->find(
                "subscription_id = :subscription_id",
                "subscription_id={$subscription->id}",
                "
                (SELECT AVG(consumption) FROM app_invoices WHERE subscription_id = {$subscription->id}) AS consumption,
                (SELECT AVG(individual) FROM app_invoices WHERE subscription_id = {$subscription->id}) AS individual,
                (SELECT AVG(value) FROM app_invoices WHERE subscription_id = {$subscription->id}) AS value"
            )->fetch();

            if ($find) {
                $balance->consumption = abs($find->consumption);
                $balance->individual = abs($find->individual);
                $balance->value = abs($find->value);
            }
        }

        return $balance;
    }

    /**
     * @param User $user
     * @return object
     */
    public function chartData(User $user): object
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


        $subscription = (new AppSubscription())->find("user_id = :user_id", "user_id={$user->id}")->fetch();
        if (!empty($subscription->id)) {
            $chart = (new AppInvoice())
                ->find(
                    "subscription_id = :subscription_id AND due_at >= DATE(now() - INTERVAL 5 MONTH) GROUP BY year(due_at) ASC, month(due_at) ASC",
                    "subscription_id={$subscription->id}",
                    "
                    year(due_at) AS due_year,
                    month(due_at) AS due_month,
                    DATE_FORMAT(due_at, '%m/%Y') AS due_date,
                    (SELECT individual FROM app_invoices WHERE subscription_id = :subscription_id AND year(due_at) = due_year AND month(due_at) = due_month) AS expense,
                    (SELECT consumption FROM app_invoices WHERE subscription_id = :subscription_id AND year(due_at) = due_year AND month(due_at) = due_month) AS income,
                    (SELECT (tax + common_area) FROM app_invoices WHERE subscription_id = :subscription_id AND year(due_at) = due_year AND month(due_at) = due_month) AS tax
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
