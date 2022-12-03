<?php

namespace Source\Models\WaterApp;

use Source\Core\Model;

/**
 * Class AppApartment
 * @package Source\Models\WaterApp
 */
class AppApartment extends Model
{

    /**
     * AppCondominium constructor.
     */
    public function __construct()
    {
        parent::__construct("app_apartment", ["id"], ["condominium_id", "block", "number", "status"]);
    }

    /**
     * @param string|null $status
     * @return AppCondominium|null
     */
    public function condominiumName(): ?AppCondominium
    {
        return (new AppCondominium())->find("id = :condominium_id AND status = 'active'", "condominium_id={$this->condominium_id}")->fetch();
    }

    /**
     * @return AppSubscription|null
     */
    public function subscribers(): ?AppSubscription
    {
        return (new AppSubscription())->find("apartment_id = :id AND status = 'active'", "id={$this->id}");
    }
}
