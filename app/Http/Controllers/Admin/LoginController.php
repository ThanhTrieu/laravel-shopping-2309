<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginPostRequest;
//use Illuminate\Support\Facades\DB;
use App\Models\Account;

class LoginController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('is.login.admin')->except(['logout']);
    // }

    public function index()
    {
        // tra ve 1 giao dien
        return view("admin.login.index");
    }

    public function handleLogin(LoginPostRequest $request)
    {
        $username = strip_tags($request->input('username'));
        $password = strip_tags($request->input('password'));

        /*
        $infoUser = DB::table('users')
                        ->where([
                            'username' => $username,
                            'password' => $password,
                            'status' => 1
                        ])->first();
        */
        $infoUser = Account::where([
            'username' => $username,
            'password' => $password,
            'status' => 1]
        )->first();
        
        if(!empty($infoUser)){
            // dang nhap thanh cong
            // $_SESSION['username'] = $username;
            $request->session()->put('idAdmin', $infoUser->id);
            $request->session()->put('username', $infoUser->username);
            $request->session()->put('emailAdmin', $infoUser->email);
            $request->session()->put('roleAdmin', $infoUser->role_id);
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
        $request->session()->forget('idAdmin');
        $request->session()->forget('emailAdmin');
        $request->session()->forget('roleAdmin');

        // quay ve giao dien dang nhap
        // goi vao routing login
        return redirect()->route('admin.login');
    }
}
