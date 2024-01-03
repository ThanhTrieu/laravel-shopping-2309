<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\Tag;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.product.index');
    }

    public function add()
    {
        $categories = Category::where(['status' => 1])->get();
        $sizes  = Size::where(['status' => 1])->get();
        $colors = Color::where(['status' => 1])->get();
        $tags   = Tag::where(['status' => 1])->get();

        return view('admin.product.add', [
            'categories' => $categories,
            'sizes' => $sizes,
            'colors' => $colors,
            'tags' => $tags
        ]);
    }

    public function create(Request $request)
    {
        dd($request->all());
    }
}
