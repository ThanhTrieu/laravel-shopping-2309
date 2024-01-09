@extends('admin_layout')
@section('title', 'Add products')
@section('breadcrumb-item-1','Products')
@section('breadcrumb-item-2','Add')

@push('stylesheets')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@2.0.1/dist/css/multi-select-tag.css">
    <style>
        .mult-select-tag .wrapper { padding-left: 0 !important; }
        .ck-editor__editable { min-height: 800px; }
    </style>
@endpush

@push('javascripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@2.0.1/dist/js/multi-select-tag.js"></script> 
    <script>
        new MultiSelectTag('color_id', {
            rounded: true,    // default true
            shadow: false,      // default false
            placeholder: 'Search',  // default Search...
            tagColor: {
                textColor: '#327b2c',
                borderColor: '#92e681',
                bgColor: '#eaffe6',
            },
            onChange: function(values) {
                console.log(values)
            }
        });

        new MultiSelectTag('size_id', {
            rounded: true,    // default true
            shadow: false,      // default false
            placeholder: 'Search',  // default Search...
            tagColor: {
                textColor: '#327b2c',
                borderColor: '#92e681',
                bgColor: '#eaffe6',
            },
            onChange: function(values) {
                console.log(values)
            }
        });

        new MultiSelectTag('tag_id', {
            rounded: true,    // default true
            shadow: false,      // default false
            placeholder: 'Search',  // default Search...
            tagColor: {
                textColor: '#327b2c',
                borderColor: '#92e681',
                bgColor: '#eaffe6',
            },
            onChange: function(values) {
                console.log(values)
            }
        });
    </script>
    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>
    <script>
        $(function(){
            $('#sale_price').prop('disabled', true);

            $('input[name="is_sale"]').change(function(){
                if($(this).is(':checked')){
                    $('#sale_price').prop('disabled', false);
                } else {
                    $('#sale_price').prop('disabled', true);
                }
            });
            
        });
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <h5 class="text-center"> Add Products !</h5>
            <a href="{{ route('admin.products') }}" class="btn btn-primary my-3"> Back to list products</a>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (Session::has('error_sale_price'))
                <div class="alert alert-danger">
                    <p class="mb-0">{{ Session::get('error_sale_price') }}</p>
                </div>
            @endif

            @if (Session::has('error_image_product'))
                <div class="alert alert-danger">
                    <p class="mb-0">{{ Session::get('error_image_product') }}</p>
                </div>
            @endif

            <form class="border p-3" action="{{ route('admin.product.create') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label>Name</label>
                            <input class="form-control" name="name" />
                        </div>
                        <div class="mb-3">
                            <label> Category </label>
                            <select class="form-control" name="categories_id">
                                <option value=""> -- Choose -- </option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Summary</label>
                            <textarea rows="5" class="form-control" name="summary"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Price</label>
                            <input class="form-control" name="price" />
                        </div>
                        <div class="mb-3">
                            <label>Is Sale</label>
                            <input type="checkbox" name="is_sale" />
                        </div>
                        <div class="mb-3">
                            <label>Sale price</label>
                            <input id="sale_price" class="form-control" name="sale_price" />
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label>Quantity</label>
                            <input class="form-control" name="quantity" />
                        </div>
                        <div class="mb-3">
                            <label>Image gallery (choose multiple image)</label>
                            <input type="file" name="image[]" class="form-control" multiple />
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-control" name="status">
                                <option value=""> -- Choose --</option>
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Colors</label>
                            <select name="color_id[]" id="color_id" multiple>
                                @foreach ($colors as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Sizes</label>
                            <select name="size_id[]" id="size_id" multiple>
                                @foreach ($sizes as $item)
                                    <option value="{{ $item->id }}">{{ $item->name_letter }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Tags</label>
                            <select name="tag_id[]" id="tag_id" multiple>
                                @foreach ($tags as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea class="form-control" name="description" rows="10" id="editor"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg"> Submit </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
