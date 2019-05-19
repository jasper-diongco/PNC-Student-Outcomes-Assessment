<?php

use Illuminate\Support\Facades\Session;

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

Route::get('/test', function() {
  return view('layouts.sb_admin');
});

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/toggle_sb/{value}', function($value) {
    Session::put('toggle_sb', $value);
});


/*
 * Colleges Routes
 */
Route::resource('/colleges', 'CollegesController');
Route::get('/colleges/{college}/dashboard', 'CollegesController@dashboard');
/*
 * /End Colleges Routes
 */




Route::resource('/faculties', 'FacultiesController');
Route::put('/faculties/{faculty}/update_account', 'FacultiesController@updateAccount');

Route::resource('/programs', 'ProgramsController');
Route::post('/programs/check_code', 'ProgramsController@check_code');


/*
 * Curricula Routes
 */
Route::get('/curricula', 'CurriculaController@index');
Route::post('/curricula', 'CurriculaController@store');
Route::post('/curricula/{curriculum}/save_curriculum', 'CurriculaController@saveCurriculum');
Route::post('/curricula/{curriculum}/revise', 'CurriculaController@revise');
Route::post('/curricula/{curriculum}/edit', 'CurriculaController@edit');
Route::get('/curricula/{curriculum}/deactivated_courses', 'CurriculaController@deactivatedCourses');
Route::get('/curricula/{curriculum}', 'CurriculaController@show');
/*
 * /End Curricula Routes
 */


/*
 * StudentOutcomes Routes
 */
Route::get('/student_outcomes', 'StudentOutcomesController@index');

Route::get('/student_outcomes/list_program', 'StudentOutcomesController@listProgram');
Route::get('/student_outcomes/{student_outcome}', 'StudentOutcomesController@show');
Route::put('/student_outcomes/{student_outcome}', 'StudentOutcomesController@update');
Route::post('/student_outcomes', 'StudentOutcomesController@store');

/*
 * /StudentOutcomes Routes
 */




/*
 * CurriculumCourses Routes
 */

//Route::resource('/curriculum_courses', 'CurriculumCoursesController');
Route::post('/curriculum_courses', 'CurriculumCoursesController@store');
Route::put('/curriculum_courses/{curriculum_course}', 'CurriculumCoursesController@update');
Route::delete('/curriculum_courses/{curriculum_course}', 'CurriculumCoursesController@destroy');
Route::get('/curriculum_courses/{curriculum_course}', 'CurriculumCoursesController@show');
Route::post('/curriculum_courses/{curriculum_course}/activate', 'CurriculumCoursesController@activate');
Route::post('/curriculum_courses/activate_selected', 'CurriculumCoursesController@activateSelected');
/*
 * End CurriculumCourses Routes
 */

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
// Route::get('/test', function() {
//   return view('layouts.test');
// });

Route::get('/profile/faculty/{id}', 'ProfileController@faculty');
