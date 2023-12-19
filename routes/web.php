<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
    return "Hello word";
});
// localhost:8000/hoc-laravel
Route::get('hoc-laravel', function() { 
    return "Chao mung ban den voi khoa hoc laravel";
});
// localhost:8000/demo-method-post 
// sai ko dung
Route::post('demo-method-post', function() {
    return "Method Post";
});
Route::put('demo-method-put', function() {
    return "Method PUT";
});
// chay tren trinh duyet : localhost:8000/get-or-post
// dung postman test voi method la post
Route::match(['get','post'], 'get-or-post', function(){
    return "Method Match";
});
// uri
Route::any('all-method', function(){
    return "All in method";
});
// localhost:8000/product/10
Route::get('product/{id}/{name?}', function($id, $name = null){
    // $id : bien dai dien cho tham so
    // {id} : khai bao tham so bat buoc trong routing
    // {name?}: khai bao tham so khong bat buoc trong routing
    return "San pham co ma la {$id} voi ten san pham : {$name}";
})->where(['id' => '[0-9]+', 'name' => '[aA-zZ]+']);
