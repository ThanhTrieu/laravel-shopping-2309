<div class="wrap-header-cart js-panel-cart">
    <div class="s-full js-hide-cart"></div>

    <div class="header-cart flex-col-l p-l-65 p-r-25">
        <div class="header-cart-title flex-w flex-sb-m p-b-8">
            <span class="mtext-103 cl2">
                Your Cart
            </span>

            <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
                <i class="zmdi zmdi-close"></i>
            </div>
        </div>
        
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
                                ${{ number_format($cart->price) }}
                            </span>
                            <input style="width: 60%;" value="{{ $cart->qty }}" class="form-control border p-1 qty-{{ $cart->rowId }}" type="number" min="1" max="10" />
                            <button id="{{ $cart->rowId }}" class="btn btn-sm btn-danger mt-1 js-remove-cart"> Remove </button>
                            <button id="{{ $cart->rowId }}" class="btn btn-sm btn-primary mt-1 js-update-cart"> Update </button>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="w-full">
                <div class="header-cart-total w-full p-tb-40">
                    Total: $ {{ number_format($totalMoney) }}
                </div>
                @if ($data['countCart'] > 0)
                    <div class="header-cart-buttons flex-w w-full">
                        <a href="{{ route('frontend.order.checkout') }}" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
                            Check Out
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@push('javascripts')
<script>
    $(function(){
        $('.js-update-cart').click(function(){
            let self = $(this);
            let rowId = self.attr('id').trim();
            let qtyPd = $(`.qty-${rowId}`).val().trim();
            if($.isNumeric(qtyPd) && rowId !== ''){
                $.ajax({
                    url: "{{ route('frontend.cart.update') }}",
                    type: "post",
                    data: {rowId, qtyPd},
                    beforeSend: function () {
                        self.text('Processing ...');
                    },
                    success: function(result){
                        self.text('Update');
                        if(result.cod === 200){
                            swal("Message", result.mess, "success");
                            window.location.reload(true);
                        } else {
                            swal("Message", result.error, "error");
                        }
                    }
                })
            }
        });

        $('.js-remove-cart').click(function(){
            let self = $(this);
            let rowId = self.attr('id').trim();
            $.ajax({
                url: "{{ route('frontend.cart.remove') }}",
                type: "post",
                data: { rowId },
                beforeSend: function () {
                    self.text('Processing ...');
                },
                success: function(result){
                    self.text('Remove');
                    if(result.cod === 200){
                        swal("Message", result.mess, "success");
                        window.location.reload(true);
                    } else {
                        swal("Message", result.error, "error");
                    }
                }
            })
        });
    });
</script>
@endpush