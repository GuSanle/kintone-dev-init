<?php

namespace App\Http\Controllers\Kintone;

class SpaceController extends KintoneApiController
{
    public function addSpace($id, $name, array $members)
    {
        $space = $this->kintoneSpace->post($id, $name, $members);
        return $space;
    }

    public function get($id)
    {
        $space = $this->kintoneSpace->get($id);
        return $space;
    }
}
