<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'category_id', 'crawler_id', 'image', 'location', 'name', 'price', 'type', 'created_at', 'updated_at'];

}
