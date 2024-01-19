<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function detail(Request $request)
    {
        $id = $request->id;
        //$slug = $request->slug;
        $infoProduct = Product::select('products.*','categories.name as category_name','categories.slug as category_slug')
            ->join('categories', 'categories.id', '=', 'products.categories_id')
            ->where('products.id',$id)
            ->first();

        if(!empty($infoProduct)){
            // hien thi anh
            $arrImages = json_decode($infoProduct->list_image,true);
            return view('frontend.product.detail',[
                'infoProduct' => $infoProduct,
                'arrImages' => $arrImages,
            ]);
        } else {
            return view('frontend.product.error');
        }
    }
}
