<?php

namespace Source\Models\WaterApp;

use Source\Core\Model;
use Source\Models\User;

/**
 * Class AppSubscription
 * @package Source\Models\WaterApp
 */
class AppSubscription extends Model
{

    /**
     * AppSubscription constructor.
     */
    public function __construct()
    {
        parent::__construct(
            "app_subscription",
            ["id"],
            ["user_id", "apartment_id", "status"]
        );
    }

    /**
     * @return mixed|Model|null
     */
    public function searchCondominium()
    {
        $apartment = (new AppApartment())->find("condominium_id = :condominium_id", "condominium_id={$this->condominium_id}")->fetch();
        return (new AppCondominium())->find($apartment)->fetch();
    }

    /**
     * @return mixed|Model|null
     */
    public function searchApartment()
    {
        return (new AppApartment())->findById($this->apartment_id);
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
        $apartment = (new AppApartment())->findById($this->apartment_id);
        return (new AppCondominium())->findById($apartment->condominium_id);
    }

    /**
     * @return mixed|Model|null
     */
    public function apartment()
    {
        if ($this->apartment_id) {
            return (new AppApartment())->findById($this->apartment_id);
        } else {
            return null;
        }
    }
}
