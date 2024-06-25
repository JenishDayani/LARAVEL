<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

class WebAuthUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$role)
    {
        if(Auth::check())
        {
            if(Auth::user()->role == $role)
            {
                return $next($request);
            }
            else
            {
                return redirect()->back()->with("error","You don't have permission to access this Page!");
            }
        }
        else
        {
            // return redirect()->route('Login');
            return response()->json([
                'message' => 'User is not Login',
                'file' => 'WebAuthUser'
            ]);
        }
    }
}
