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
        $links = $this->filterWithAdItem('.ProfesionalCardTestABClass');
        $links = array_merge($links, $this->filterWithAdCard());
        $links = array_merge($links, $this->filterWithAdItem('.ParticularCardTestABClass'));
        $links = array_merge($links, $this->filterWithAdItem('.CardTestABClass'));
        return collect($links);
    }

    private function getRemote($id)
    {
        $id = substr($id, 1);
        return "https://img.milanuncios.com/fp/" . substr($id, 0, 4) . '/' . substr($id, 4, 2) . '/' . "{$id}_1.jpg";
    }

    private function getPrice($priceCard, $node)
    {
        try {
            return $node->filter($priceCard)->text();
        } catch (\Exception $ignored) {
            return null;
        }
    }

    private function filterWithAdItem($card)
    {
        return $this->filter($card, '.x3', '.x5', '.aditem-detail-title',
            '.list-location-region', '.tx', '.aditem-price', '.tag-mobile');
    }

    private function filter($card, $typeCard, $idCard, $nameCard, $locationCard, $bodyCard, $priceCard, $tagsCard)
    {
        $links = [];
        $this->getCrawler()->filter($card)->each(function ($node) use (&$links, $typeCard, $idCard, $nameCard, $locationCard, $bodyCard, $priceCard, $tagsCard) {
            try {
                $type = $node->filter($typeCard)->text();
                $id = $node->filter($idCard)->text();
                $name = $node->filter($nameCard)->text();
                $location = $node->filter($locationCard)->text();
                $body = $node->filter($bodyCard)->text();
                $price = str_replace('€', '', str_replace('.', '', $this->getPrice($priceCard, $node)));
                $firstImage = $this->getRemote($id);
                $category_id = $this->categoryId;
                $tags = [];
                $node->filter($tagsCard)->each(function ($node) use (&$tags) {
                    $tags[] = $node->text();
                });
                $links[] = (object)compact('type', 'id', 'body', 'firstImage', 'price', 'name', 'category_id', 'tags', 'location');
            } catch (\Exception $exception) {
                Log::info($exception);
            }
        });
        return $links;
    }

    private function filterWithAdCard()
    {
        return $this->filter('.ma-AdCard', '.ma-AdCard-sellType', '.ma-AdCard-adId', '.ma-AdCard-title-text',
            '.ma-AdCard-location', '.ma-AdCardDescription-text', '.ma-AdPrice-value--default', 'ma-AdTag-label');
    }

}
