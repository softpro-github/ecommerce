<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $newArrivals = Product::query()->where('status', 'active')->latest()->take(8)->get();
        $featured = Product::query()->where('status', 'active')->where('featured', true)->take(8)->get();
        $bestsellers = Product::query()->where('status', 'active')->where('bestseller', true)->take(8)->get();
        $soldOut = Product::query()->where('status', 'sold_out')->take(8)->get();
        $categories = Category::whereNull('parent_id')->where('enabled', true)->orderBy('sort_order')->get();

        return view('home', compact('newArrivals', 'featured', 'bestsellers', 'soldOut', 'categories'));
    }
}
