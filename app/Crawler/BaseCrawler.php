<?php

namespace App\Crawler;

use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

class BaseCrawler
{
    protected $url = '';

    public function getCrawler()
    {

        $client = new Client(HttpClient::create(['timeout' => 120]));
        return $client->request('GET', "https://www.milanuncios.com$this->url");
    }

    protected function getAll()
    {
    }
}
