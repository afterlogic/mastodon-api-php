<?php

namespace Colorfield\Mastodon;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

/**
 * MastodonAPI
 *
 * PHP version >= 5.6.0
 *
 * @category Third party
 * @package  Mastodon-API-PHP
 * @author   Christophe Jossart <christophe@colorfield.eu>
 * @license  Apache License 2.0
 * @version  Release: <0.0.1>
 * @link     https://github.com/r-daneelolivaw/mastodon-api-php
 */
class MastodonAPI
{

    /**
     * @var \Colorfield\Mastodon\ConfigurationVO
     */
    private $config;

    /**
     * Creates the API object.
     *
     * @param array $config
     */
    public function __construct(ConfigurationVO $config)
    {
        /** @var \GuzzleHttp\Client client */
        $this->client = new Client();
        $this->config = $config;
    }

    /**
     * Request to an endpoint.
     *
     * @param $endpoint
     * @param array $json
     *
     * @return mixed|null
     */
    public function getResponse($endpoint, $operation, array $json, $token = null)
    {
        $result = null;
        $uri = $this->config->getBaseUrl() . '/api/';
        $uri .= ConfigurationVO::API_VERSION . $endpoint;
        if ($token === null)
        {
            $token = $this->config->getBearer();
        }
        $response = $this->client->{$operation}($uri, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'json' => $json,
            'http_errors' => false
        ]);
        // @todo $request->getHeader('content-type')
        if($response instanceof ResponseInterface)
        {
            $result = json_decode($response->getBody(), true);
        }
        return $result;
    }
}
