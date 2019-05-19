<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/colleges';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo() {

        Session::put('toggle_sb', 1);

        if(Auth::user()->user_type_id == 'dean') {
            Session::put('college_id', Auth::user()->getFaculty()->college_id);
            return url('/colleges/' . Auth::user()->getFaculty()->college_id . '/dashboard');
        } else if(Auth::user()->user_type_id == 's_admin') {
            Session::put('college_id', 'all');
            return url('/colleges');
        } else if(Auth::user()->user_type_id == 'prof') {
            Session::put('college_id', Auth::user()->getFaculty()->college_id);
            return url('/colleges/' . Auth::user()->getFaculty()->college_id . '/dashboard');
        }
    }

    // protected function loggedOut(Request $request) {
    //     return redirect('/login');
    // }

    // public function logout(Request $request) {
    //     $this->performLogout($request);
    //     return redirect()->route('/login');
    //     //return redirect('/login');
    // }
    public function logout() {
        Auth::logout();
        Session::flush();
        return redirect('/login');
    }
}
