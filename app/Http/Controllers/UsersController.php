<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Gate;

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
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin')) {
            return abort('401', 'Unauthorized');
        }

        $user->is_active = false;
        $user->update();


        Session::flash('message', 'Faculty successfully deactivated');

        if(request('user_type') == 'faculty') {
           return redirect('/faculties/deactivated'); 
        }

        

        return redirect('/users/deactivated');
    }

    public function activate(User $user) {
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin')) {
            return abort('401', 'Unauthorized');
        }

        $user->is_active = true;
        $user->update();

        Session::flash('message', 'Faculty successfully activated');

        if(request('user_type') == 'faculty') {
           return redirect('/faculties'); 
        }

        return redirect('/users/deactivated');
    }

    public function activateSelected(Request $request) {
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin')) {
            return abort('401', 'Unauthorized');
        }

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
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $user = null;

        if(request('email') != '') {
            $user = User::where('email', request('email'))->first();
        }

        if($user != null) {
            if(auth()->user()->user_type_id == 'prof') {
                if($user->user_type_id != 'stud') {
                    $user = null;
                }
            } else if (auth()->user()->user_type_id == 'dean') {
                if($user->user_type_id == 's_admin' || $user->user_type_id == 'dean') {
                    $user = null;
                }
            }
        }
        

        return view('users.reset_password', compact('user'));
    }

    public function resetPassword(User $user) {
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }




        $user->password = Hash::make('DefaultPass123');
        $user->save();

        Session::flash('message', '<b>' . $user->email . '</b> your password is successfully reset. You can now login to your account.');

        return redirect('/users/reset_password?email='. $user->email . '&reset=successful');
    }
}
