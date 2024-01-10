@extends('admin_layout')
@section('title', 'List products')
@section('breadcrumb-item-1','Products')
@section('breadcrumb-item-2','List')

@push('stylesheets')
<style>
    .pagination{
        float: right;
        margin-top: 10px;
    }
</style>
@endpush
@push('javascripts')
<script>
    $(function(){
        $('#btnSearch').click(function(){
            let keyword = $('#txtKeyword').val().trim();
            keyword = encodeURI(keyword);
            window.location.href = "{{ route('admin.products') }}" + "?s="+keyword;
        });

        $('#txtKeyword').bind("enterKey",function(e){
            let keyword = $('#txtKeyword').val().trim();
            keyword = encodeURI(keyword);
            window.location.href = "{{ route('admin.products') }}" + "?s="+keyword;
        });
        $('#txtKeyword').keyup(function(e){
            if(e.keyCode == 13){
                $(this).trigger("enterKey");
            }
        });
    });
</script>
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <h5 class="text-center"> Products !</h5>
            <a href="{{ route('admin.product.add') }}" class="btn btn-primary my-3"> Add product</a>

            <div class="input-group mb-3">
                <input id="txtKeyword" type="text" class="form-control" placeholder="Product's name" value="{{ $keyword }}">
                <button class="input-group-text" id="btnSearch">Search</button>
            </div>

            @if (Session::has('insert_success'))
                <div class="alert alert-success">
                    <p class="mb-0">{{ Session::get('insert_success') }}</p>
                </div>
            @endif
            @if (Session::has('delete_success'))
                <div class="alert alert-success">
                    <p class="mb-0">{{ Session::get('delete_success') }}</p>
                </div>
            @endif
            @if (Session::has('delete_fail'))
                <div class="alert alert-danger">
                    <p class="mb-0">{{ Session::get('delete_fail') }}</p>
                </div>
            @endif

            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th width="20%">Image</th>
                        <th>Price</th>
                        <th>Is sale</th>
                        <th>Sale price</th>
                        <th>Quantity</th>
                        <th colspan="3" width="5%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>
                                <img width="50%" class="img-fluid img-thumbnail" src="{{ URL::to('/') }}/uploads/images/products/{{ $product->image }}" />
                            </td>
                            <td>
                                {{ number_format($product->price) }}
                            </td>
                            <td>
                                {{ ($product->is_sale == 1) ? 'Yes' : 'No' }}
                            </td>
                            <td>
                                {{ number_format($product->sale_price) }}
                            </td>
                            <td>{{ $product->quantity }}</td>
                            <td>
                                <a class="btn btn-info btn-sm" href="#"> View </a>
                            </td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="#"> Edit </a>
                            </td>
                            <td>
                                <form method="post" action="{{ route('admin.product.delete',['id' => $product->id]) }}">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-danger btn-sm" type="submit"> Delete </button>
                                </form>
                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- phan trang --}}
            {{ $products->appends(request()->input())->links() }}
        </div>
    </div>
@endsection
