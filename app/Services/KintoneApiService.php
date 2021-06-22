<?php

namespace App\Services;

use CybozuHttp\Client;
use CybozuHttp\Api\KintoneApi;

class  KintoneApiService

{
    // public $kintoneApi;

    public function connect($config)
    {

        return new KintoneApi(new Client($config));
    }
    public function app($config)
    {
        return $this->connect($config)->app();
    }
    public function apps($config)
    {
        return $this->connect($config)->apps();
    }
    public function record($config)
    {
        return $this->connect($config)->record();
    }
    public function records($config)
    {
        return $this->connect($config)->records();
    }
    public function file($config)
    {
        return $this->connect($config)->file();
    }
    public function preview($config)
    {
        return $this->connect($config)->preview();
    }
    public function cursor($config)
    {
        return $this->connect($config)->cursor();
    }
    public function comment($config)
    {
        return $this->connect($config)->comment();
    }
    public function comments($config)
    {
        return $this->connect($config)->comments();
    }
    public function graph($config)
    {
        return $this->connect($config)->graph();
    }
    public function space($config)
    {
        return $this->connect($config)->space();
    }
    public function thread($config)
    {
        return $this->connect($config)->thread();
    }
    public function guests($config)
    {
        return $this->connect($config)->guests();
    }
    public function nonpublicApi($config)
    {
        return $this->connect($config)->nonpublicApi();
    }
}
