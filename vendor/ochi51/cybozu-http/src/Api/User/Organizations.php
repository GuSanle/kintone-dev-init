<?php

namespace CybozuHttp\Api\User;

use CybozuHttp\Client;
use CybozuHttp\Api\UserApi;
use CybozuHttp\Middleware\JsonStream;

/**
 * @author ochi51 <ochiai07@gmail.com>
 */
class Organizations
{
    /**
     * @var Csv
     */
    private $csv;
    private $client;

    public const MAX_GET_ORGANIZATIONS = 100;


    /**
     * Organizations constructor.
     * @param Csv $csv
     */
    public function __construct(Client $client, Csv $csv)
    {
        $this->client = $client;
        $this->csv = $csv;
    }

    /**
     * Get organizations by csv
     * https://cybozudev.zendesk.com/hc/ja/articles/202124754
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getByCsv(): string
    {
        return $this->csv->get('organization');
    }

    /**
     * Post organizations by csv
     * https://cybozudev.zendesk.com/hc/ja/articles/202350640
     *
     * @param $filename
     * @return int
     * @throws \InvalidArgumentException
     */
    public function postByCsv($filename): int
    {
        return $this->csv->post('organization', $filename);
    }

    /**
     * Get organizations by json
     *
     * @param array $ids
     * @param array $codes
     * @param integer $offset
     * @param integer $size
     * @return array
     */
    public function get(array $ids = [], array $codes = [], $offset = 0, $size = self::MAX_GET_ORGANIZATIONS): array
    {
        $options = ['json' => compact('ids', 'codes')];
        $options['json']['size'] = $size;
        $options['json']['offset'] = $offset;

        /** @var JsonStream $stream */
        $stream = $this->client
            ->get(UserApi::generateUrl('organizations.json'), $options)
            ->getBody();

        return $stream->jsonSerialize()['organizations'];
    }

    /**
     * Post organizations by json
     *
     * @param array $organizations
     * @return array
     */
    public function post(array $organizations)
    {
        $options = ['json' => ['organizations' => $organizations]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->post(UserApi::generateUrl('organizations.json'), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }
}
