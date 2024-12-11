<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Show the application welcome page with a list of products.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        $priceRange = $request->input('price_range');
        $category = $request->input('category');

        $productsQuery = Product::query();

        if ($query) {
            $productsQuery->where('name', 'like', "%{$query}%");
        }

        if ($category) {
            $productsQuery->where('category_id', $category);
        }

        if ($priceRange) {
            list($min, $max) = explode('-', $priceRange);
            if ($max == '+') {
                $productsQuery->where('price', '>=', $min * 1000);
            } else {
                $productsQuery->whereBetween('price', [$min * 1000, $max * 1000]);
            }
        }

        $products = $productsQuery->paginate(12);
        $categories = Category::all();

        return view('welcome', compact('products', 'categories', 'query'));
    }
}
