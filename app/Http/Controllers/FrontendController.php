<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Gloudemans\Shoppingcart\Facades\Cart;

class FrontendController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $carts = Cart::content();
            $cartTotal = Cart::total();
            $itemCarts = Cart::count();
            View::share('data', [
                'cart' => $carts,
                'total' => $cartTotal,
                'countCart' => $itemCarts,
            ]);
            
            return $next($request);
        });
    }
}
