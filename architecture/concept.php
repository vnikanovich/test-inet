<?php

abstract class Store
{
    abstract function getStore(): IStore;

    public function getSecretKey(): string
    {
        $store = $this->getStore();
        return $store->getContent();
    }
}

interface IStore
{
    public function getContent(): string;
}

class FileCreator extends Store
{
    public function getStore(): IStore
    {
        return new File();
    }
}

class DBCreator extends Store
{
    public function getStore(): IStore
    {
        return new DB();
    }
}

class File implements IStore
{
    public function getContent(): string
    {
        return 'file_content';
    }
}

class DB implements IStore
{
    public function getContent(): string
    {
        return 'db_content';
    }
}

class Concept
{
    private $client;

    private Store $store;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
        $this->store = new File();
    }

    public function getUserData()
    {
        $params = [
            'auth' => ['user', 'pass'],
            'token' => $this->store->getSecretKey()
        ];

        $request = new \Request('GET', 'https://api.method', $params);
        $promise = $this->client->sendAsync($request)->then(function ($response) {
            $result = $response->getBody();
        });

        $promise->wait();
    }
}
