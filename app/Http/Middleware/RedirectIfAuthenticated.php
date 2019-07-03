<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            //return redirect('/home');
            if(Auth::user()->user_type_id == 'dean') {
                Session::put('college_id', Auth::user()->getFaculty()->college_id);
                return redirect('/colleges/' . Auth::user()->getFaculty()->college_id . '/dashboard');
            } else if(Auth::user()->user_type_id == 's_admin') {
                Session::put('college_id', 'all');
                return redirect('/colleges');
            } else if(Auth::user()->user_type_id == 'prof') {
                Session::put('college_id', Auth::user()->getFaculty()->college_id);
                return redirect('/colleges/' . Auth::user()->getFaculty()->college_id . '/dashboard');
            } else if(Auth::user()->user_type_id == 'stud') {
                //Session::put('college_id', Auth::user()->getFaculty()->college_id);
                //exit("Test");
                //Auth::logout();
                //Session::flush();
            }
        }
        return $next($request);
    }
}
