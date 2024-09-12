<?php

namespace Ginov\CaldavPlugs;

use Sabre\HTTP\Client;
use Sabre\HTTP\Request;
use Sabre\DAV\Client as DAVClient;
use Sabre\HTTP\ResponseInterface;

class Http
{
    const DAV_CLIENT = 1;
    const HTTP_CLIENT = 0;

    private string $baseUrl;
    private \Sabre\HTTP\Client $client;

    public function __construct(string $url, string $verify = '', array $config = [])
    {
        array_unshift($config, $url);

        $this->baseUrl = $config[0];

        $this->client = $this->httpClient($verify);
    }

    public function request(
        string $method,
        string $url,
        array $headers = [],
        string $body = null): ResponseInterface 
    {
        return $this->client->send(new Request($method, $this->baseUrl . $url, $headers, $body));
    }

    /**
     * Undocumented function
     *
     * @param string $verify
     * @return self
     */
    private static function httpClient(string $verify = ''): \Sabre\HTTP\Client
    {
        $client = new \Sabre\HTTP\Client();
        
        if (!$verify) {
            $client->addCurlSetting(CURLOPT_SSL_VERIFYHOST, 0);
            $client->addCurlSetting(CURLOPT_SSL_VERIFYPEER, 0);
        }

        return $client;
    }
}

class Dav
{
    const DAV_CLIENT = 1;
    const HTTP_CLIENT = 0;

    private string $baseUrl;
    private \Sabre\DAV\Client $client;

    public function __construct(string $url, string $verify = '', array $config = [])
    {
        array_unshift($config, $url);

        $this->baseUrl = $config[0];

        $this->client = $this->davClient($config, $verify);
    }

    public function request(
        string $method,
        string $url,
        array $headers = [],
        string $body = null): ResponseInterface 
    {
        return $this->client->send(new Request($method, $this->baseUrl . $url, $headers, $body));
    }

    private static function davClient(array $config, string $verify = '')
    {
        $client = new \Sabre\DAV\Client($config);
        
        if (!$verify) {
            $client->addCurlSetting(CURLOPT_SSL_VERIFYHOST, 0);
            $client->addCurlSetting(CURLOPT_SSL_VERIFYPEER, 0);
        }

        return $client;
    }
}
