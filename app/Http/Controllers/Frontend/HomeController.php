<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\Tag;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $keyWord = $request->query('s');
        $categories = Category::all();
        $tags = Tag::where('type',config('constants.type.tag_product'))->get();
        $colors = Color::all();  


        $products = Product::select(
                            'products.*',
                            'categories.name as category_name',
                            'categories.slug as category_slug'
                        )->join(
                            'categories',
                            'products.categories_id', '=','categories.id'
                        )->where(function($query) use ($keyWord) {
                            $query->where('products.name', 'LIKE', "%{$keyWord}%");
                            $query->orWhere('products.description', 'LIKE', "%{$keyWord}%");
                        });              
        $fromPrice = $request->from_price;
        $toPrice   = $request->to_price;
        if(!empty($fromPrice) && !empty($toPrice)){
            $fromPrice = (int) $fromPrice;
            $toPrice   = (int) $toPrice;
            if($fromPrice < $toPrice){
                $products->whereBetween('price', [$fromPrice, $toPrice]);
            } else {
                $products->where('price' ,'>=', $toPrice);
            }
        }

        $color = $request->color;
        if(isset($color)){
            $infoColor = Color::where('slug', $color)->first();
            if($infoColor !== null){
                $productColors = ProductColor::where('color_id',$infoColor->id)->get();
                $arrProductIds = [];
                if($productColors !== null){
                    $arrProductIds = array_column($productColors->toArray(),'product_id');
                    $products->whereIn('products.id',$arrProductIds);
                }
            }
        }

        $tag = $request->tag;
        if(isset($tag)){
            $infoTag = Tag::where('slug', $tag)->first();
            if($infoTag !== null){
                $productTags = ProductTag::where('tag_id',$infoTag->id)->get();
                $arrProductId = [];
                if($productTags !== null){
                    $arrProductId = array_column($productTags->toArray(),'product_id');
                    $products->whereIn('products.id',$arrProductId);
                }
            }
        }

        $dataProducts = $products->paginate(config('constants.pagination.items_per_page'));
            
        return view('frontend.home.index',[
            'categories' => $categories,
            'products' => $dataProducts,
            'keyWord' => $keyWord,
            'tags' => $tags,
            'colors' => $colors,
        ]);
    }
}
