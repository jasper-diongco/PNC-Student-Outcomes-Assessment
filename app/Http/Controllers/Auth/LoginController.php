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
     * Login username to be used by the controller.
     *
     * @var string
     */
    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }

    public function redirectTo() {

        //Session::put('toggle_sb', 1);

        if(Auth::user()->is_active == false) {
            
            return url('/user_is_deactivated');
        }

        if(Auth::user()->user_type_id == 'dean') {
            Session::put('college_id', Auth::user()->getFaculty()->college_id);
            return url('/colleges/' . Auth::user()->getFaculty()->college_id . '/dashboard');
        } else if(Auth::user()->user_type_id == 's_admin') {
            Session::put('college_id', 'all');
            return url('/colleges');
        } else if(Auth::user()->user_type_id == 'prof') {
            Session::put('college_id', Auth::user()->getFaculty()->college_id);
            // return url('/colleges/' . Auth::user()->getFaculty()->college_id . '/dashboard');

            return url('/faculties/dashboard');
        } else if(Auth::user()->user_type_id == 'stud') {
            Session::put('college_id', Auth::user()->getStudent()->college_id);
            return url('/s/home');
        }
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function findUsername()
    {
        $login = request()->input('login_str');
 
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
 
        request()->merge([$fieldType => $login]);
 
        return $fieldType;
    }

    /**
     * Get username property.
     *
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    // protected function loggedOut(Request $request) {
    //     return redirect('/login');
    // }

    // public function logout(Request $request) {
    //     $this->performLogout($request);
    //     return redirect()->route('/login');
    //     //return redirect('/login');
    // }

    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        if ($request->ajax()){

            return response()->json([
                'auth' => auth()->check(),
                'user' => $user,
                'intended' => $this->redirectPath(),
            ]);

        }
    }
    
    public function logout() {
        Auth::logout();
        Session::flush();
        return redirect('/login');
    }
}
