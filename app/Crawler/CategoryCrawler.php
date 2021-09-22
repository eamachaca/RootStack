<?php

namespace App\Crawler;

class CategoryCrawler extends BaseCrawler
{
    protected $url = '';

    public function getAll(): \Illuminate\Support\Collection
    {
        $links = [];
        $this->getCrawler()->filter('.ma-CategoriesCategory')->each(function ($node) use (&$links) {
            $principal = $node->filter('.ma-MainCategory-mainCategoryNameLink');
            $sons = [];
            $node->filter('.ma-SharedCrosslinks-link')->each(function ($node) use (&$sons) {
                $sons[] = (object)['name' => $node->text(), 'link' => $node->attr('href')];
            });
            $links[] = (object)[
                'name' => $principal->text(),
                'image' => $node->filter('.ma-MainCategory-icon')->filter('img')->attr('src'),
                'link' => $principal->attr('href'),
                'sons' => $sons
            ];
        });
        return collect($links);
    }

}
