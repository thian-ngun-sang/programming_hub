<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoggedInUser
{
    public function handle(Request $request, Closure $next)
    {   
        if(!empty(Auth::user())){
            return redirect()->route('user-account-page');
        }
        return $next($request);
    }
}
