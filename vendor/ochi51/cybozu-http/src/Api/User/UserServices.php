<?php

namespace CybozuHttp\Api\User;

use CybozuHttp\Client;
use CybozuHttp\Api\UserApi;
use CybozuHttp\Middleware\JsonStream;

/**
 * @author ochi51 <ochiai07@gmail.com>
 */
class UserServices
{
    /**
     * @var Csv
     */
    private $csv;
    private $client;
    /**
     * UserServices constructor.
     * @param Client $client
     * @param Csv $csv
     */
    public function __construct(Client $client, Csv $csv)
    {
        $this->client = $client;
        $this->csv = $csv;
    }

    /**
     * Get userServices by csv
     * https://cybozudev.zendesk.com/hc/ja/articles/202363070
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getByCsv(): string
    {
        return $this->csv->get('userServices');
    }

    /**
     * Post userServices by csv
     * https://cybozudev.zendesk.com/hc/ja/articles/202124734
     *
     * @param $filename
     * @return int
     * @throws \InvalidArgumentException
     */
    public function postByCsv($filename): int
    {
        return $this->csv->post('userServices', $filename);
    }

    /**
     * Post users services
     *
     * @param array $userservices
     * @return array
     */
    public function post(array $userservices)
    {
        $options = ['json' => ['users' => $userservices]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->put(UserApi::generateUrl('users/services.json'), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }
}
