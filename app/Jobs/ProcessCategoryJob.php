<?php

namespace App\Jobs;

use App\Crawler\CategoryCrawler;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCategoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $crawler = new CategoryCrawler();
        $categories = $crawler->getAll();
        $auxCategories = [];
        $now = Carbon::now();
        foreach ($categories as $category) {
            $auxCategories[] = ['parent_id' => null, 'link_image' => $category->image, 'name' => $category->name, 'scrap_link' => $category->link, 'created_at' => $now, 'updated_at' => $now];
            $id = count($auxCategories);
            foreach ($category->sons as $son) {
                $auxCategories[] = ['parent_id' => $id, 'link_image' => null, 'name' => $son->name, 'scrap_link' => $son->link, 'created_at' => $now, 'updated_at' => $now];
            }
        }
        Category::insert($auxCategories);
    }
}
