<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginPostRequest;

class LoginController extends Controller
{
    public function index()
    {
        // tra ve 1 giao dien
        return view("admin.login.index");
    }

    public function handleLogin(LoginPostRequest $request)
    {
        $username = strip_tags($request->input('username'));
        $password = strip_tags($request->input('password'));

        if($username === 'admin' && $password === '123456789'){
            // dang nhap thanh cong
            // $_SESSION['username'] = $username;
            $request->session()->put('username', $username);
            // cho vao trang dashboard
            return redirect()->route('admin.dashboard');
        } else {
            // dang nhap sai
            return redirect()->back()->with('error_login', 'Tai khoan khong ton tai');
        }
    }

    public function logout(Request $request)
    {
        // unset($_SESSION['username']);
        $request->session()->forget('username');
        // quay ve giao dien dang nhap
        // goi vao routing login
        return redirect()->route('admin.login');
    }
}
