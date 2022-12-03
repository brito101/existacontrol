<?php

namespace Source\Models\WaterApp;

use Source\Core\Model;
use Source\Models\User;
use Source\Models\WaterApp\AppSubscription;

/**
 * Class AppInvoice
 * @package Source\Models\WarterApp
 */
class AppViewInvoice extends Model
{

    /**
     * AppViewInvoice constructor.
     */
    public function __construct()
    {
        parent::__construct(
            "view_app_invoices",
            ["id"],
            [
                "subscription_id", "description", "due_at"
            ]
        );
    }
}
