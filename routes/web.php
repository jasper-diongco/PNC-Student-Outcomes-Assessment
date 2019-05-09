<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});


Route::resource('/colleges', 'CollegesController');
Route::get('/colleges/{college}/dashboard', 'CollegesController@dashboard');

Route::resource('/faculties', 'FacultiesController');
Route::put('/faculties/{faculty}/update_account', 'FacultiesController@updateAccount');

Route::resource('/programs', 'ProgramsController');
Route::post('/programs/check_code', 'ProgramsController@check_code');

Route::resource('/curricula', 'CurriculaController');


//Route::resource('/courses', 'CoursesController');
//Route::get('/courses', 'CoursesController@index');
//Route::get('/courses/{course}', 'CoursesController@show');
//Route::post('/courses', 'CoursesController@store');
//Route::get('/courses/get_courses', 'CoursesController@get_courses');
Route::resource('/courses', 'CoursesController');

// Auth::routes(['register' => false]);
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/test', function() {
  return view('layouts.test');
});

Route::get('/profile/faculty/{id}', 'ProfileController@faculty');
