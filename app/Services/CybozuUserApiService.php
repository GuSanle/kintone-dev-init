<?php

namespace App\Services;

use CybozuHttp\Client;
use CybozuHttp\Api\UserApi;

class  CybozuUserApiService

{
    public function connect($config)
    {
        return new UserApi(new Client($config));
    }
    public function users($config)
    {
        return $this->connect($config)->users();
    }
    public function csv($config)
    {
        return $this->connect($config)->csv();
    }
    public function groups($config)
    {
        return $this->connect($config)->groups();
    }
    public function organizations($config)
    {
        return $this->connect($config)->organizations();
    }
    public function titles($config)
    {
        return $this->connect($config)->titles();
    }
    public function userServices($config)
    {
        return $this->connect($config)->userServices();
    }
    public function organizationUsers($config)
    {
        return $this->connect($config)->organizationUsers();
    }
    public function userOrganizations($config)
    {
        return $this->connect($config)->userOrganizations();
    }
    public function userGroups($config)
    {
        return $this->connect($config)->userGroups();
    }
}
