<?php

namespace CybozuHttp\Api\Kintone;

use CybozuHttp\Client;
use CybozuHttp\Api\KintoneApi;
use CybozuHttp\Middleware\JsonStream;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

/**
 * @author ochi51 <ochiai07@gmail.com>
 */
class NonpublicApi
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Update System Setting
     *
     * @param array $setting
     * @return array
     */
    public function updateSystemSetting(array $setting): array
    {
        $options = [
            'json' => [
                "useMailNotification" => $setting['useMailNotification'],
                "mailFormat" => $setting['mailFormat'],
                "mailFormatSelectableByUser" => $setting['mailFormatSelectableByUser'],
                "mailPersonalSetting" =>  $setting['mailPersonalSetting'],
                "includeOfficialApi" =>  $setting['includeOfficialApi'],
                "useSpace" =>  $setting['useSpace'],
                "useGuest" =>  $setting['useGuest'],
                "usePeople" =>  $setting['usePeople']
            ]
        ];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->post('k/api/system/setting/update.json', $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Post Space Template
     *
     * @param array $item
     * @return array
     */
    public function postSpaceTemplate($item): array
    {
        $options = ['json' => ["item" => $item]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->post('k/api/space/template/import.json', $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Update Customize Setting
     *
     * @param $jsScope
     * @param array $jsFiles
     * @return array
     */
    public function updateCustomizeSetting($jsScope, array $jsFiles): array
    {
        $options = ['json' => ["jsScope" => $jsScope, "jsFiles" => $jsFiles]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->post('k/api/js/updateSystemSetting.json', $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Get App List
     *
     * @param  $excludeUnrelatedApps
     * @param  $includeCreatorInfo
     * @param  $size
     * @param  $offset
     * @param  $includeGuestInfo
     * @return array
     */
    public function getAppList($excludeUnrelatedApps, $includeCreatorInfo, $size, $offset, $includeGuestInfo): array
    {
        $options = ['json' => ["excludeUnrelatedApps" => $excludeUnrelatedApps, "includeCreatorInfo" => $includeCreatorInfo, "size" => $size, "offset" => $offset, "includeGuestInfo" => $includeGuestInfo]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->post('k/api/app/list.json?', $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Install Apps From TemplateFile
     *
     * @param  $thread
     * @param  $fileKey
     * @return array
     */
    public function installFromTemplateFile($thread, $fileKey): array
    {
        $options = ['json' => ["thread" => $thread, "fileKey" => $fileKey]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->post('k/api/app/installFromTemplateFile.json', $options)
            ->getBody();

        return $stream->jsonSerialize();
    }


    public function exportSpaceTemplate($id)
    {
        $url = "k/api/space/template/export.do?id=" . intval($id);
        /** @var JsonStream $stream */
        $response = $this->client
            ->get($url);
        return (string) $response->getBody();
    }

    public function exportSpaceTemplates(array $ids): array
    {
        $result = [];
        $concurrency = $this->client->getConfig('concurrency');
        $headers = $this->client->getConfig('headers');
        $headers['Content-Type'] = 'application/json';
        $url = "k/api/space/template/export.do?id=";
        $requests = static function () use ($ids, $url, $headers) {
            foreach ($ids as $id) {
                $newurl =  $url  . intval($id);
                yield new Request('GET', $newurl, $headers);
            }
        };
        $pool = new Pool($this->client, $requests(), [
            'concurrency' => $concurrency ?: 1,
            'fulfilled' => static function (ResponseInterface $response, $index) use (&$result) {
                $result[$index] = (string) $response->getBody();
            }
        ]);
        $pool->promise()->wait();

        return $result;
    }

    public function exportAppTemplates($items)
    {
        $url = "k/api/template/export.do?".$items;
        /** @var JsonStream $stream */
        $response = $this->client
            ->get($url);
        return (string) $response->getBody();
    }

    public function getSpaceTemplateList($offset = 0, $size = 100): array
    {
        $options = ['json' => ["offset" => $offset, "size" => $size]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->post('k/api/space/template/list.json', $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    public function getAppTemplateList($offset = 0, $size = 100): array
    {
        $options = ['json' => ["offset" => $offset, "size" => $size]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->post('k/api/template/list.json', $options)
            ->getBody();

        return $stream->jsonSerialize();
    }
}
