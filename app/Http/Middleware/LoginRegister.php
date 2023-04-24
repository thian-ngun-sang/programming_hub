<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LoginRegister
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle($request, Closure $next){
        if (Auth::check()) {
            return $next($request);
        }elseif (Cookie::get('remember_token')) {
            $user = User::where('remember_token', hash('sha256', Cookie::get('remember_token')))->first();
            if ($user) {
                Auth::login($user);
                return $next($request);
            }
        }
        return redirect()->route('login');
    }
}
