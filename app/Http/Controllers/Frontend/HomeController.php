<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $keyWord = $request->query('s');
        $categories = Category::all();
        $products = Product::select(
                            'products.*',
                            'categories.name as category_name',
                            'categories.slug as category_slug'
                        )->join(
                            'categories',
                            'products.categories_id', '=','categories.id'
                        )->where('products.name', 'LIKE', "%{$keyWord}%")
                        ->orWhere('products.description', 'LIKE', "%{$keyWord}%")
                        ->paginate(config('constants.pagination.items_per_page'));
                        
        return view('frontend.home.index',[
            'categories' => $categories,
            'products' => $products,
            'keyWord' => $keyWord
        ]);
    }
}
