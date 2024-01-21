<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function add(Request $request)
    {
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
