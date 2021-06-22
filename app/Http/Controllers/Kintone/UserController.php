<?php

namespace App\Http\Controllers\Kintone;

class UserController extends CybozuUserApiController
{
    public function getUsers(array $userids)
    {
        $userInfo = $this->cybozuUser->get($userids);

        return $userInfo;
    }


    public function postUsers(array $users)
    {
        $status = $this->cybozuUser->post($users);

        return $status;
    }

    public function postUsersServices(array $services)
    {
        $status = $this->cybozuUserServices->post($services);

        return $status;
    }

    public function getOrganizations()
    {
        $organizations = $this->cybozuOrganizations->get();

        return $organizations;
    }

    public function postOrganizations(array $organizations)
    {
        $status = $this->cybozuOrganizations->post($organizations);

        return $status;
    }

    public function postUserOrganizations($organizationsCSV)
    {
        $status = $this->cybozuUserOrganizations->postByCsv($organizationsCSV);
        return $status;
    }
}
