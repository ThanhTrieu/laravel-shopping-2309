<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminLogined
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionUsername = $request->session()->get('username');
        if(empty($sessionUsername)){
            // bat quay ve trang dang nhap cua admin
            return redirect()->route('admin.login');
        }
        // cho phep chay cac routing khac
        return $next($request);
    }
}
