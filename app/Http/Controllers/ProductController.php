<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $products = Product::query()
            ->where('status', 'active')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::whereNull('parent_id')->where('enabled', true)->orderBy('sort_order')->get();
        $title = $search ? 'Search: '.$search : 'Shop All';

        return view('products.index', compact('products', 'categories', 'title'));
    }

    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $categoryIds = $category->children()->pluck('id')->push($category->id);

        $products = Product::query()
            ->whereIn('category_id', $categoryIds)
            ->where('status', 'active')
            ->latest()
            ->paginate(12);

        $categories = Category::whereNull('parent_id')->where('enabled', true)->orderBy('sort_order')->get();
        $title = $category->name;

        return view('products.index', compact('products', 'categories', 'title', 'category'));
    }

    public function show(string $slug)
    {
        $product = Product::with(['images', 'variants', 'category', 'reviews' => fn ($q) => $q->where('approved', true)])
            ->where('slug', $slug)
            ->firstOrFail();

        $product->increment('views');

        $related = Product::query()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }
}
