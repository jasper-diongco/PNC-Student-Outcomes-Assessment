<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\UserProfile;
use Gate;

class UserProfilesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function show(User $user) {
        if($user->id != auth()->user()->id) {
            return abort(404, 'Page Not Found');
        }

        //check if theres a profile
        $user_profile = UserProfile::where('user_id', $user->id)->first();

        if(!$user_profile) {
            //create new user profile
            $user_profile = UserProfile::create([
                'user_id' => $user->id
            ]);
        }

        if($user->user_type_id == 'stud') {
            $student = $user->getStudent();
            $student->load('program');
            return view('s.user_profile', compact('user', 'student', 'user_profile'));
        } else if ($user->user_type_id == 'dean' || $user->user_type_id == 'prof') {
            $faculty = $user->getFaculty();
            $faculty->load('college');
            return view('faculties.profile', compact('user', 'user_profile', 'faculty'));
        } else if ($user->user_type_id == 's_admin') {
            return view('users.super_admin_profile', compact('user', 'user_profile'));
        }
    }

    public function updateAccountInformation(User $user) {
        if($user->id != auth()->user()->id) {
            return abort(404, 'Page Not Found');
        }

        $data = request()->validate([
            'username' => 'required|min:6|max:20|alpha_dash|unique:users,username,' . $user->id,
            'email' => 'required|email|max:40|unique:users,email,' . $user->id
        ]);


        $user->username = $data['username'];
        $user->email = $data['email'];

        $user->save();

        return $user;
    }

    public function updateMainInfo(User $user) {
        if($user->id != auth()->user()->id) {
            return abort(404, 'Page Not Found');
        }

        $data = request()->validate([
            'last_name' => 'required|regex:/^[\pL\s]+$/u',
            'first_name' => 'required|regex:/^[\pL\s]+$/u',
            'middle_name' => 'nullable|regex:/^[\pL\s]+$/u',
            'sex' => 'required',
            'date_of_birth' => 'required|date'
        ]);


        $user->first_name = strtoupper($data['first_name']);
        $user->last_name = strtoupper($data['last_name']);
        $user->middle_name = strtoupper($data['middle_name']);
        $user->sex = $data['sex'];
        $user->date_of_birth = $data['date_of_birth'];

        $user->save();

        return $user;
    }

    public function updatePassword(User $user) {
        if($user->id != auth()->user()->id) {
            return abort(404, 'Page Not Found');
        }

        $data = request()->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|max:20',
            'confirm_password' => 'required|same:password'
        ]);

        //check if password is equal
        if (!Hash::check($data['current_password'], $user->password)) {
            return response()->json([
                'current_password' => ['Current password does not match to the password in database']
            ], 422);
        }


        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }

    public function updateBasicInformation(User $user) {
        $user_profile = UserProfile::where('user_id', $user->id)->first();
            /*
            house_no: '',
            barangay: '',
            town_city: '',
            province: '',
            country: 'Philippines',
            zip_code: '',
            place_of_birth: '',
            civil_status: '',
            nationality: '',
            religion: '',
            contact_no: ''*/
        $data = request()->validate([
            'house_no' => 'nullable|max:191|string',
            'barangay' => 'nullable|max:191|string',
            'town_city' => 'nullable|max:191|string',
            'province' => 'nullable|max:191|string',
            'country' => 'nullable|max:191|string',
            'zip_code' => 'nullable|max:191|string',
            'place_of_birth' => 'nullable|max:191|string',
            'civil_status' => 'nullable|max:191|string',
            'nationality' => 'nullable|max:191|string',
            'religion' => 'nullable|max:191|string',
            'contact_no' => 'nullable|max:191|string'
        ]);


        $user_profile->update([
            'house_no' => $data['house_no'],
            'barangay' => $data['barangay'],
            'town_city' => $data['town_city'],
            'province' => $data['province'],
            'country' => $data['country'],
            'zip_code' => $data['zip_code'],
            'place_of_birth' => $data['place_of_birth'],
            'civil_status' => $data['civil_status'],
            'nationality' => $data['nationality'],
            'religion' => $data['religion'],
            'contact_no' => $data['contact_no']
        ]);

        return $user_profile;
    }
}
