<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessCategoryJob;
use App\Jobs\ProcessProductJob;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Category::with('children')
            ->withCount('products')
            ->whereNull('parent_id')
            ->paginate(5);
        return view('home', compact('categories'));
    }

    public function products($id)
    {
        $category = Category::with('products')->find($id);
        return view('products', compact('category'));
    }

    public function queueProducts()
    {
        ProcessProductJob::dispatch();
        return redirect()->route('home');
    }

    public function queueCategories()
    {
        ProcessCategoryJob::dispatch();
        return redirect()->route('home');
    }

    public function resetDatabase()
    {
        Artisan::call('migrate:fresh --seed');
        return redirect()->route('home');
    }
}
