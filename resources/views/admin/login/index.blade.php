@extends('admin_layout_login')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card-group d-block d-md-flex row">
        <div class="card col-md-7 p-4 mb-0">
          <div class="card-body">
            <h1>Login</h1>
            <p class="text-medium-emphasis">Sign In to your account</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (Session::has('error_login'))
                <div class="alert alert-danger">
                    <p>{{ Session::get('error_login') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.handle.login') }}">
                @csrf
                <div class="input-group mb-3"><span class="input-group-text">
                    <svg class="icon">
                    <use xlink:href="{{ asset('admin/vendors/@coreui/icons/svg/free.svg#cil-user') }}"></use>
                    </svg></span>
                    <input name="username" class="form-control" type="text" placeholder="Username">
                </div>
                <div class="input-group mb-4"><span class="input-group-text">
                    <svg class="icon">
                    <use xlink:href="{{ asset('admin/vendors/@coreui/icons/svg/free.svg#cil-lock-locked') }}"></use>
                    </svg></span>
                    <input name="password" class="form-control" type="password" placeholder="Password">
                </div>
                <div class="row">
                    <div class="col-6">
                        <button class="btn btn-primary px-4" type="submit">Login</button>
                    </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection