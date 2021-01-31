<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function categories() {
        $categories = Category::all();
        $products_all = Product::all();
        return view('category.index', compact('categories', 'products_all'));
    }

    public function category($slug) {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = $category->products()->get();
        return view('category.show', [
            'category' => $category,
            'products' => $products
        ]);
    }

    public function ajax($id){
        $product= Product::findOrfail($id, ['id', 'name', 'slug', 'image', 'gallery', 'price', 'old_price', 'sku']);
        return view('ajax.quick_view', compact('product'));
    }
}
