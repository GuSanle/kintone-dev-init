<?php

namespace App\Http\Controllers\Kintone;

use App\Facades\CybozuUserApi;
use App\Http\Controllers\Controller;

class CybozuUserApiController extends controller
{
    protected $cybozuUser;
    protected $cybozuUserServices;
    protected $cybozuOrganizations;
    protected $cybozuUserOrganizations;

    public function __construct($config)
    {
        $this->cybozuUser = CybozuUserApi::users($config);
        $this->cybozuUserServices = CybozuUserApi::userServices($config);
        $this->cybozuOrganizations = CybozuUserApi::organizations($config);
        $this->cybozuUserOrganizations = CybozuUserApi::userOrganizations($config);
    }
}
