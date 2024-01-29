@extends('frontend_layout')

@section('content')
<div class="container">
    <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
        <a href="{{ route('frontend.home') }}" class="stext-109 cl8 hov-cl1 trans-04">
            Home
            <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
        </a>
        <a href="#" class="stext-109 cl8 hov-cl1 trans-04">
            Product
            <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
        </a>
        <span class="stext-109 cl4">
            Checkout
        </span>
    </div>
</div>
<section class="sec-product-detail bg0 p-t-65 p-b-60">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <h5> Customer's informations. </h5>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="border p-3" method="POST" action="{{ route('frontend.order.payment') }}">
                    @csrf
                    <div class="mb-3">
                        <label>Full name (*)</label>
                        <input type="text" name="full_name" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label>Phone (*)</label>
                        <input type="text" name="phone" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label>Email (*)</label>
                        <input type="email" name="email" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label>Shipping Address (*)</label>
                        <textarea rows="5" class="form-control" name="shipping_address"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Note</label>
                        <textarea rows="3" class="form-control" name="note"></textarea>
                    </div>
                    <div class="mb-3">
                        <p class="text-success">Thanh toan tien khi nhan san pham </p>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block"> Payment</button>
                </form>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="header-cart-content flex-w js-pscroll">
                    <ul class="header-cart-wrapitem w-full">
                        @php
                            $totalMoney = 0;
                        @endphp
                        @foreach ($data['cart'] as $cart )
                            @php
                                $totalMoney += ($cart->price * $cart->qty);
                            @endphp
                            <li class="header-cart-item flex-w flex-t m-b-12">
                                <div class="header-cart-item-img">
                                    <img src="{{ URL::to('/') }}/uploads/images/products/{{ $cart->options->image }}" alt="{{ $cart->name }}">
                                </div>
                                <div class="header-cart-item-txt p-t-8">
                                    <a href="#" class="header-cart-item-name bold hov-cl1 trans-04">
                                        {{ $cart->name }}
                                    </a>
                                    <span class="header-cart-item-info">
                                        Size: {{ $cart->options->size }}
                                    </span>
                                    <span class="header-cart-item-info">
                                        Color: {{ $cart->options->color }}
                                    </span>
                                    <span class="header-cart-item-info">
                                        Price: {{ number_format($cart->price) }}
                                    </span>
                                    <span class="header-cart-item-info">
                                        quantity : {{ number_format($cart->qty) }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="w-full">
                        <div class="header-cart-total w-full p-tb-40">
                            Total: $ {{ number_format($totalMoney) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection