<?php

namespace App\Crawler;

use Illuminate\Support\Facades\Log;

class ProductCrawler extends BaseCrawler
{
    private $categoryId;

    public function __construct($url, $categoryId)
    {
        $this->url = $url;
        $this->categoryId = $categoryId;
    }

    public function getAll(): \Illuminate\Support\Collection
    {
        $links = [];
        $this->getCrawler()->filter('.ProfesionalCardTestABClass')->each(function ($node) use (&$links, &$firstImage) {
            $type = $node->filter('.x3')->text();
            $id = $node->filter('.x5')->text();
            $name = $node->filter('.aditem-detail-title')->text();
            $location = $node->filter('.list-location-region')->text();
            $body = $node->filter('.tx')->text();
            $price = str_replace('€', '', str_replace('.', '', $this->getPrice($type, $node)));
            $firstImage = $this->getRemote($id);
            $category_id = $this->categoryId;
            $tags = [];
            $node->filter('.tag-mobile')->each(function ($node) use (&$tags) {
                $tags[] = $node->text();
            });
            $links[] = (object)compact('type', 'id', 'body', 'firstImage', 'price', 'name', 'category_id', 'tags', 'location');
        });
        return collect($links);
    }

    private function getRemote($id)
    {
        $id = substr($id, 1);
        return "https://img.milanuncios.com/fp/" . substr($id, 0, 4) . '/' . substr($id, 4, 2) . '/' . "{$id}_1.jpg";
    }

    private function getPrice($type, $node)
    {
        try {
            return $node->filter('.aditem-price')->text();
        } catch (\Exception $ignored) {
            return null;
        }
    }

}
