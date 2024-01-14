<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\Tag;
use App\Http\Requests\StorePostProductRequest;
use App\Http\Requests\UpdatePostProductRequest;
use Illuminate\Support\Facades\Validator;
use App\Rules\SalePriceValidator;
use App\Models\Product;
use function App\Helpers\slugVietnamese;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductTag;
use Illuminate\Support\Facades\URL;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->query('s');
        $products = Product::where('name', 'LIKE', "%{$keyword}%")
                            ->orWhere('description', 'LIKE', "%{$keyword}%")
                            ->paginate(30);
        return view('admin.product.index',[
            'products' => $products,
            'keyword' => $keyword
        ]);
    }

    public function add()
    {
        $categories = Category::where(['status' => 1])->get();
        $sizes  = Size::where(['status' => 1])->get();
        $colors = Color::where(['status' => 1])->get();
        $tags   = Tag::where(['status' => 1, 'type' => 1])->get();

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
        // xu ly xong viec insert san pham
        // xu ly tiep cac viec
        // insert color - poroduct
        // insert size - product
        // insert tag - product
        if(is_numeric($lastInsertIdProduct) && $lastInsertIdProduct > 0){
            // insert color - poroduct
            $arrColorId = $request->color_id;
            if(is_array($arrColorId) && !empty($arrColorId)){
                $dataProductColor = [];
                foreach($arrColorId as $colorId){
                    $dataProductColor[] = [
                        'product_id' => $lastInsertIdProduct,
                        'color_id' => $colorId,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
                if(!empty($dataProductColor)){
                    ProductColor::insert($dataProductColor);
                }
            }
            // insert product size
            $arrSizeId = $request->size_id;
            if(is_array($arrSizeId) && !empty($arrSizeId)){
                $dataProductSize = [];
                foreach($arrSizeId as $sizeId){
                    $dataProductSize[] = [
                        'product_id' => $lastInsertIdProduct,
                        'size_id' => $sizeId,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
                if(!empty($dataProductSize)){
                    ProductSize::insert($dataProductSize);
                }
            }
            // insert product tag
            $arrTagId = $request->tag_id;
            if(is_array($arrTagId) && !empty($arrTagId)){
                $dataProductTag = [];
                foreach($arrTagId as $tagId){
                    $dataProductTag[] = [
                        'product_id' => $lastInsertIdProduct,
                        'tag_id' => $tagId,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
                if(!empty($dataProductTag)){
                    ProductTag::insert($dataProductTag);
                }
            }

            return redirect()->route('admin.products')->with('insert_success', 'Insert Successful');

        } else {
            return redirect()->back()->with('error_insert_product', 'Insert product fail');
        }
    }

    public function delete(Request $request)
    {
        $idProduct = $request->id;
        $infoPd = Product::find($idProduct);
        if(!empty($infoPd)){
            $infoPd->delete(); // tu dong update truong deleted_at, khong xoa mat du lieu
            return redirect()->back()->with('delete_success', 'Delete Successful');
        }
        return redirect()->back()->with('delete_fail', 'Delete Failure');
    }

    public function edit(Request $request)
    {
        $idProduct = $request->id;
        $idProduct = is_numeric($idProduct) ? $idProduct : 0;

        $infoPd = Product::find($idProduct);
        if(empty($infoPd)){
            return view('admin.product.error');
        } else {
            $categories = Category::where(['status' => 1])->get();
            $sizes  = Size::where(['status' => 1])->get();
            $colors = Color::where(['status' => 1])->get();
            $tags   = Tag::where(['status' => 1, 'type' => 1])->get();

            // xu ly hien thi danh sach sanh
            $galleryImage = $infoPd->list_image;
            $arrGalleryImage = [];
            $arrGalleryImageView = [];
            if(!empty($galleryImage)){
                $arrGalleryImage = json_decode($galleryImage, true);
                foreach($arrGalleryImage as $img){
                    $arrGalleryImageView[] = [
                        'id' => $img,
                        'src' => URL::to('/')."/uploads/images/products/".$img
                    ];
                }
            }
            //dd(json_encode($arrGalleryImageView));
            // xu ly hien thi product - color
            $arrColors = ProductColor::select('color_id')->where('product_id',$idProduct)->get();
            $arrColors = !empty($arrColors) ? array_column($arrColors->toArray(), 'color_id') : [];
            // xu ly hien thi product - size
            $arrSizes = ProductSize::select('size_id')->where('product_id', $idProduct)->get();
            $arrSizes = !empty($arrSizes) ? array_column($arrSizes->toArray(), 'size_id') : [];
            // xu ly hien thi Product - Tag
            $arrTags = ProductTag::select('tag_id')->where('product_id', $idProduct)->get();
            $arrTags = !empty($arrTags) ? array_column($arrTags->toArray(), 'tag_id') : [];

            return view('admin.product.edit',[
                'categories' => $categories,
                'sizes' => $sizes,
                'colors' => $colors,
                'tags' => $tags,
                'infoPd' => $infoPd,
                'arrGalleryImage' => $arrGalleryImage,
                'arrColors' => $arrColors,
                'arrSizes' => $arrSizes,
                'arrTags' => $arrTags,
                'viewImages' => json_encode($arrGalleryImageView)
            ]);
        }
    }

    public function update(UpdatePostProductRequest $request)
    {
        $idProduct = $request->id;
        $idProduct = is_numeric($idProduct) ? $idProduct : 0;
        $infoPd = Product::find($idProduct);
        if(empty($infoPd)){
            return redirect()->back()->with('error_update_product', 'Update product Failure');
        } else {
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
                    return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
                }
                $price = $request->input('price');
                $salePrice = $request->input('sale_price');
                if($salePrice > $price){
                    return redirect()->back()->with('error_sale_price', 'Gia sale nho hon gia goc');
                }
            }

            // tien hanh upload danh sach anh
            $arrayImages = [];
            if($request->hasFile('list_image')){

                // nguoi dung co upload lai danh sach anh
                // kiem tra xem cac anh co dung ko?
                $validatorImg = Validator::make($request->all(),[
                    'list_image' => ['required', 'max:2048'],
                    'list_image.*' => 'mimes:jpg,png,jpeg,gif,svg'
                ],[
                    'list_image.required' => 'Vui long chon anh san pham',
                    'list_image.*.mimes' => 'Anh chi chap nhan : jpg,png,jpeg,gif,svg',
                    'list_image.max' => 'Kich thuoc anh khong vuot qua 2mb',
                ]);

                if ($validatorImg->fails()) {
                    return redirect()->back()
                            ->withErrors($validatorImg)
                            ->withInput();
                }

                // anh cu
                $arrOldImage = $request->old_list_image;

                foreach($request->file('list_image') as $img){
                    $nameImg = $img->getClientOriginalName();
                    $img->move(public_path() . '/uploads/images/products/', $nameImg);
                    $arrayImages[] = $nameImg;
                }
                // lay ca anh cu va anh moi
                $arrayImages = array_merge($arrOldImage, $arrayImages);
            } else {
                $arrayImages = $request->old_list_image;
            }


            // kiem tra image
            $imageUpdate = $infoPd->image; // lay lai cai anh cu
            if($request->hasFile('image')){
                $validatorImgPd = Validator::make($request->all(),[
                    'image' => ['required', 'max:2048', 'mimes:jpg,png,jpeg,gif,svg'],
                ],[
                    'image.required' => 'Vui long chon anh san pham',
                    'image.mimes' => 'Anh chi chap nhan : jpg,png,jpeg,gif,svg',
                    'image.max' => 'Kich thuoc anh khong vuot qua 2mb',
                ]);
                if ($validatorImgPd->fails()) {
                    return redirect()->back()
                            ->withErrors($validatorImgPd)
                            ->withInput();
                }
                // tien hanh upload anh - cap nhat lai anh vao database
                $imageUpdate = $request->file('image')->getClientOriginalName();
                $request->file('image')->move(public_path() . '/uploads/images/products/', $imageUpdate);
            }
            // cap nhat du lieu vao bang product.
            Product::where('id',$idProduct)
                    ->update([
                        'name' => $request->name,
                        'slug' => slugVietnamese($request->name),
                        'description' => $request->description,
                        'summary' => $request->summary,
                        'image' => $imageUpdate,
                        'list_image' => empty($arrayImages) ? $infoPd->list_image : json_encode($arrayImages),
                        'price' => $request->price,
                        'sale_price' => $request->sale_price,
                        'is_sale' => $checkIsSale === 'on' ? 1 : 0,
                        'quantity' => $request->quantity,
                        'status' => $request->status,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            // tien hanh update color - product
            $arrColorProducts = $request->color_id;
            if(!empty($arrColorProducts) && is_array($arrColorProducts)){
                ProductColor::where('product_id',$idProduct)->delete();
                $dataUpdateColor = [];
                foreach($arrColorProducts as $color){
                    $dataUpdateColor[] = [
                        'product_id' => $idProduct,
                        'color_id' => $color,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }
                ProductColor::insert($dataUpdateColor);
            }
            // tien hanh update Size - Product
            $arrSizeProducts = $request->size_id;
            if(!empty($arrSizeProducts) && is_array($arrSizeProducts)){
                ProductSize::where('product_id',$idProduct)->delete();
                $dataUpdateSize = [];
                foreach($arrSizeProducts as $size){
                    $dataUpdateSize[] = [
                        'product_id' => $idProduct,
                        'size_id' => $size,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }
                ProductSize::insert($dataUpdateSize);
            }
            // tien hanh update Tag - Product
            $arrTagProducts = $request->tag_id;
            if(!empty($arrTagProducts) && is_array($arrTagProducts)){
                ProductTag::where('product_id',$idProduct)->delete();
                $dataUpdateTag = [];
                foreach($arrTagProducts as $tag){
                    $dataUpdateTag[] = [
                        'product_id' => $idProduct,
                        'tag_id' => $tag,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }
                ProductTag::insert($dataUpdateTag);
            }
            return redirect()->route('admin.products')->with('update_success', 'Update Successful');
        }
    }
}
