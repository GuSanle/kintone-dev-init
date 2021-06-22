<?php

namespace App\Http\Controllers;

use App\Models\DemoStatus as DemoStatusModel;

class DemoStatusController extends Controller
{
    /**
     * @param string $domain
     * @return json status
     */

    public function get($domain)
    {
        $DemoStatusModel = new DemoStatusModel();
        return $DemoStatusModel::get($domain);
    }

    public function set($domain)
    {
        $DemoStatusModel = new DemoStatusModel();
        return $DemoStatusModel::set($domain);
    }
}
