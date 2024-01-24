<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController as Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{

    public function update(Request $request)
    {
        if($request->ajax()){
            $rowId = $request->rowId;
            $qtyPd = $request->qtyPd;
            if(!is_numeric($qtyPd) || $qtyPd < 0 || $qtyPd > 10){
                return response()->json([
                    'cod' => 500,
                    'mess' => null,
                    'error' => 'quantity of product more than 0 and less than 10',
                ]);
            }
            Cart::update($rowId, $qtyPd);
            return response()->json([
                'cod' => 200,
                'mess' => 'Update Item cart successfully',
                'error' => null
            ]);
        }
    }

    public function remove(Request $request)
    {
        if($request->ajax()){
            $rowId = $request->rowId;
            Cart::remove($rowId);
            return response()->json([
                'cod' => 200,
                'mess' => 'Remove Item cart successfully',
                'error' => null
            ]);
        }
    }

    public function add(Request $request)
    {
        if($request->ajax()){
            $idPd = $request->id;
            $idPd = is_numeric($idPd) ? $idPd : 0;
            $product = Product::find($idPd);

            if($product !== null){
                $idColor = $request->idColor;
                $idSize = $request->idSize;
                $color = Color::find($idColor);
                $size  = Size::find($idSize);
                $nameColor = $color->name ?? null;
                $nameSize  = $size->name_letter ?? null;
                $qty = $request->qty;

                if($qty <= 0 || $qty > 10){
                    return response()->json([
                        'cod' => 401,
                        'mess' => null,
                        'error' => 'quantity > 0 and quantity < 10'
                    ]);
                }
                if($nameColor === null || $nameSize === null){
                    return response()->json([
                        'cod' => 401,
                        'mess' => null,
                        'error' => 'Choose Size and Color'
                    ]);
                }

                Cart::add([
                    'id' => $idPd,
                    'name' => $product->name,
                    'qty' => $qty,
                    'price' => $product->price,
                    'options' => [
                        'size' => $nameSize,
                        'color' => $nameColor,
                        'image' => $product->image
                    ]
                ]);
                return response()->json([
                    'cod' => 200,
                    'mess' => 'Add cart success',
                    'error' => null
                ]);
            } 
            return response()->json([
                'cod' => 500,
                'mess' => null,
                'error' => 'Not found product'
            ]);
        }
    }
}
