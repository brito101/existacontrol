<?php

namespace Source\Models\WaterApp;

use Source\Core\Model;
use Source\Models\User;

/**
 * Class AppSubscriptionCondominium
 * @package Source\Models\WaterApp
 */
class AppSubscriptionCondominium extends Model
{

    /**
     * AppSubscription constructor.
     */
    public function __construct()
    {
        parent::__construct(
            "app_subscription_condominium",
            ["id"],
            ["user_id", "condominium_id", "status"]
        );
    }

    /**
     * @return type
     */
    public function user()
    {
        return (new User())->findById($this->user_id);
    }

    /**
     * @return mixed|Model|null
     */
    public function plan()
    {
        $condominium = (new AppCondominium())->findById($this->condominium_id);
        return $condominium;
    }
}
