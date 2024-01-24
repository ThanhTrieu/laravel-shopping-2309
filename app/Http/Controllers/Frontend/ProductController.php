<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController as Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;

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

            // xu ly lay du lieu color theo product
            $colorProducts = Color::select('colors.*')
                                    ->join('product_color','colors.id', '=', 'product_color.color_id')
                                    ->where('product_color.product_id',$id)
                                    ->get();
            // xu ly lay du lieu size theo product 
            $sizeProducts = Size::select('sizes.*')
                                ->join('product_size','sizes.id', '=', 'product_size.size_id')
                                ->where('product_size.product_id',$id)
                                ->get();

            // xu ly lay du lieu san pham cung the loai
            $relatedProducts = Product::where('categories_id',$infoProduct->categories_id)->where('id','!=', $id)
                                        ->skip(config('constants.pagination.related_product.skip'))
                                        ->take(config('constants.pagination.related_product.take'))
                                        ->get();
            return view('frontend.product.detail',[
                'infoProduct' => $infoProduct,
                'arrImages' => $arrImages,
                'colorProducts' => $colorProducts,
                'sizeProducts' => $sizeProducts,
                'relatedProducts' => $relatedProducts,
            ]);
        } else {
            return view('frontend.product.error');
        }
    }
}
