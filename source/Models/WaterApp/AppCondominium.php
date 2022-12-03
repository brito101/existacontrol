<?php

namespace Source\Models\WaterApp;

use Source\Core\Model;

/**
 * Class AppCondominium
 * @package Source\Models\WaterApp
 */
class AppCondominium extends Model
{

    /**
     * AppCondominium constructor.
     */
    public function __construct()
    {
        parent::__construct("app_condominium", ["id"], ["name", "status"]);
    }

    /**
     * @return int
     */
    public function subscribers(): int
    {
        $apartments = (new AppApartment())->find("condominium_id = {$this->id}")->fetch(true);
        if ($apartments) {
            foreach ($apartments as $apartment) {
                $id = $apartment->id;
            }
            return ((new AppSubscription())->find("apartment_id = :id AND status = 'active'", "id={$id}"))->count();
        } else {
            return 0;
        }
    }
}
