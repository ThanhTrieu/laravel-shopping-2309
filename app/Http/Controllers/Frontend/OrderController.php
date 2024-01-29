<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController as Controller;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Http\Requests\StorePostCustomerPaymentOrder as PaymentOrder;
use Illuminate\Support\Str;
use App\Models\Order;

class OrderController extends Controller
{
    public function checkout()
    {
        if(Cart::count() == 0 ){
            return redirect()->route('frontend.home');
        }
        return view('frontend.order.checkout');
    }

    public function payment(PaymentOrder $request)
    {
        if(Cart::count() > 0){
            $dataProduct = [];
            foreach(Cart::content() as $item){
                $dataProduct[] = [
                    'extra_code' => Str::random(80),
                    'product_id' => $item->id,
                    'color_name' => $item->options->color,
                    'size_name' => $item->options->size,
                    'full_name' => $request->full_name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'qty' => $item->qty,
                    'payment_type' => 1,
                    'status' => 1,
                    'order_date' => date('Y-m-d H:i:s'),
                    'shipping_address' => $request->shipping_address,
                    'note' => $request->note,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
            $insert = Order::insert($dataProduct);
            if($insert){
                // xoa san pham trong gio hang
                Cart::destroy();
                return redirect()->route('frontend.home');
            }
            return redirect()->back()->with('error_payment','payment invalid');
        }
        return redirect()->back()->with('error_payment','payment invalid');
    }
}
