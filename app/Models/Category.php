<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id', 'name', 'scrap_link', 'link_image', 'created_at', 'updated_at'];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')->withCount('products');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id')->withCount('products');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
