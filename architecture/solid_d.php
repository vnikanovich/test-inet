<?php

interface HttpService
{
    public function request(string $url, string $method, ?array $options = null);
}

class XMLHttpService extends XMLHTTPRequestService implements HttpService
{
}

class Http
{
    public function __construct(
        private HttpService $service
    ) {
    }

    public function get(string $url, array $options)
    {
        $this->service->request($url, 'GET', $options);
    }

    public function post(string $url)
    {
        $this->service->request($url, 'GET');
    }
}
