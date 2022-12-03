<?php

namespace Source\App\WaterApi;

use Source\Models\WaterApp\AppSubscription;

/**
 * Class Subscriptions
 * @package Source\App\WaterApi
 */
class Subscriptions extends WaterApi {

    /**
     * Subscriptions constructor.
     * @throws \Exception
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * show subscription
     */
    public function index(): void {
        $subscription = (new AppSubscription())->find("user_id = :user_id AND status != :status",
                        "user_id={$this->user->id}&status=canceled")->fetch();

        if (!$subscription) {
            $this->call(
                    404,
                    "not_found",
                    "Você ainda não tem uma assinatura"
            )->back();
            return;
        }

        $response["subscription"] = $subscription->data();

        $this->back($response);
    }

}
