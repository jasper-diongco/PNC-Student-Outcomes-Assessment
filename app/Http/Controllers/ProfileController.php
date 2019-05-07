<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Faculty;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct() {
      $this->middleware('auth');
    }
    public function faculty($id) {
      if(Auth::user()->getFaculty()->id != $id) {
        return abort('401', 'Unauthorized');
      }

      $faculty = Faculty::findOrFail($id);

      return view('profile.faculty')->with('faculty', $faculty);
    }
}
