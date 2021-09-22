<?php

namespace App\Crawler;

use Illuminate\Support\Collection;

class BaseCrawler
{
    protected $url = '';

    public function getCrawler()
    {
        return \Goutte::request('GET', "https://www.milanuncios.com$this->url");
    }

    protected function getAll()
    {
    }
}
