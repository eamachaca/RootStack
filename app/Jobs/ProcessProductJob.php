<?php

namespace App\Jobs;

use App\Crawler\ProductCrawler;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ProcessProductJob implements ShouldQueue
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
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');
        $categories = Category::withCount('products')->get()->where('products_count', '0');
        $products = collect();
        $tags = collect();
        foreach ($categories as $category) {
            $crawler = new ProductCrawler($category->scrap_link, $category->id);
            $this->addProducts($products, $tags, $crawler->getAll());
        }
        Product::insert($products->toArray());
        Tag::insert($tags->toArray());
    }

    private function addProducts(Collection $products, Collection $tags, Collection $crawlers)
    {
        $now = Carbon::now();
        foreach ($crawlers as $crawler) {
            $products->add([
                'name' => $crawler->name,
                'category_id' => $crawler->category_id,
                'price' => !is_numeric($crawler->price) ? null : $crawler->price,
                'image' => $crawler->firstImage,
                'body' => $crawler->body,
                'type' => $crawler->type,
                'crawler_id' => $crawler->id,
                'location' => $crawler->location,
                'created_at' => $now, 'updated_at' => $now
            ]);
            $productId = $products->count();
            foreach ($crawler->tags as $tag) {
                $tags->add(['name' => $tag, 'product_id' => $productId, 'created_at' => $now, 'updated_at' => $now]);
            }

        }
    }
}
