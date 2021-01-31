<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function product($slug) {
        $product = Product::where('slug', $slug)->firstOrFail();
        $manufacturer = $product->manufacturer;
        return view('product.show', [
            'product' => $product,
            'manufacturer' => $manufacturer,
        ]);
    }
}
