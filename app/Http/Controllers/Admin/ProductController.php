<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\Tag;
use App\Http\Requests\StorePostProductRequest;
use Illuminate\Support\Facades\Validator;
use App\Rules\SalePriceValidator;
use App\Models\Product;

use function App\Helpers\slugVietnamese;

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

    public function create(StorePostProductRequest $request)
    {
        // kiem tra gia sale
        $checkIsSale = $request->input('is_sale');
        if($checkIsSale === 'on'){
            // nguoi dung da tich - co nghia can nhap gia sale price
            $validator = Validator::make($request->all(),[
                'sale_price' => ['required','numeric', new SalePriceValidator]
            ],[
                'sale_price.required' => 'Vui long nhap gia khuyen mai',
                'sale_price.numeric' => 'Gia khuyen mai phai la so'
            ]);
            if ($validator->fails()) {
                return redirect()->route('admin.product.add')
                        ->withErrors($validator)
                        ->withInput();
            }
            $price = $request->input('price');
            $salePrice = $request->input('sale_price');
            if($salePrice > $price){
                return redirect()->back()->with('error_sale_price', 'Gia sale nho hon gia goc');
            }
        }

        // tien hanh upload anh
        $arrayImages = [];
        if($request->hasFile('image')){
            foreach($request->file('image') as $img){
                $nameImg = $img->getClientOriginalName();
                $img->move(public_path() . '/uploads/images/products/', $nameImg);
                $arrayImages[] = $nameImg;
            }
        }
        if(empty($arrayImages)){
            return redirect()->back()->with('error_image_product', 'Khong the upload duoc hinh anh san pham');
        }

        // tien hanh insert du lieu vao database
        $slugProduct = slugVietnamese($request->name);
        $product = new Product;
        $product->categories_id = $request->categories_id;
        $product->name = $request->name;
        $product->slug = $slugProduct;
        $product->description = $request->description;
        $product->summary = $request->summary;
        $product->image = array_shift($arrayImages);
        $product->list_image = json_encode($arrayImages);
        $product->price = $request->price;
        $product->sale_price = $request->sale_price;
        $product->is_sale = $checkIsSale === 'on' ? 1 : 0;
        $product->quantity = $request->quantity;
        $product->status = $request->status;
        $product->created_at = date('Y-m-d H:i:s');
        $product->save();
        $lastInsertIdProduct = $product->id;
        dd($lastInsertIdProduct);
    }
}
