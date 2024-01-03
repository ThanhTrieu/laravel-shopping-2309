@extends('admin_layout')
@section('title', 'List products')
@section('breadcrumb-item-1','Products')
@section('breadcrumb-item-2','List')

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <h5 class="text-center"> Products !</h5>
            <a href="{{ route('admin.product.add') }}" class="btn btn-primary my-3"> Add product</a>
        </div>
    </div>
@endsection
