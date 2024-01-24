<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Gloudemans\Shoppingcart\Facades\Cart;

class FrontendController extends Controller
{
    public function __construct()
    {
        $carts = Cart::content();
        View::share('data', [
            'cart' => $carts
        ]);
    }
}
