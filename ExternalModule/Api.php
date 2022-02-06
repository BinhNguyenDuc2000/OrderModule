<?php

class Api
{
    private $client;

    public function __construct()
    {
        $this->client = new GuzzleHttp\Client;
    }

    public function get($uri)
    {
        $response = $this->client->request("GET", $uri);
        return $response->getBody();
    }

}
