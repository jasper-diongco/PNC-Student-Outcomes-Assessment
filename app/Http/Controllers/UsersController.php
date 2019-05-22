<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }


    public function changePassword(User $user) {
        $data = request()->validate([
            'email' => 'required|email|max:255|unique:users,email,'. $user->id,
            'password' => 'required|min:8|max:20',
            'confirm_password' => 'required|same:password'
        ]);

        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);

        $user->update();

        return $user;

    }

    public function deactivate(User $user) {
        $user->is_active = false;
        $user->update();


        Session::flash('message', 'Faculty successfully deactivated');

        if(request('user_type') == 'faculty') {
           return redirect('/faculties/deactivated'); 
        }

        

        return redirect('/users/deactivated');
    }

    public function activate(User $user) {
        $user->is_active = true;
        $user->update();

        Session::flash('message', 'Faculty successfully activated');

        if(request('user_type') == 'faculty') {
           return redirect('/faculties'); 
        }

        return redirect('/users/deactivated');
    }

    // public function activateSelected(User $user) {
    //     $user->is_active = true;
    //     $user->update();

    //     Session::flash('message', 'Faculty successfully deactivated');

    //     if(request('user_type') == 'faculty') {
    //        return redirect('/faculties'); 
    //     }
        
    //     return redirect('/users/deactivated');
    // }

    public function activateSelected(Request $request) {
        DB::beginTransaction();

        try {
            foreach (json_decode(request('checked_users')) as $user_id) {
                $user = User::findOrFail($user_id);
                $user->is_active = true;
                $user->update();
            }

            DB::commit();
            // all good
            Session::flash('message', 'Faculties successfully activated');

            if(request('user_type') == 'faculty') {
               return redirect('/faculties'); 
            }

            return redirect('/users');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong

            return abort(500, 'Internal Server Error');
        }
    }

    public function resetPasswordView() {
        $user = null;

        if(request('email') != '') {
            $user = User::where('email', request('email'))->first();
        }

        return view('users.reset_password', compact('user'));
    }

    public function resetPassword() {

    }
}
