<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

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


// Navigation route
Route::get('/toggle_sb/{value}', function($value) {
    Session::put('toggle_sb', $value);
});
// End navigation route



// Colleges Routes

Route::resource('/colleges', 'CollegesController');
Route::get('/colleges/{college}/dashboard', 'CollegesController@dashboard');

// End Colleges Routes




// Faculties Routes

Route::get('/faculties', 'FacultiesController@index');

Route::get('/faculties/create', 'FacultiesController@create');

Route::post('/faculties', 'FacultiesController@store');

Route::get('/faculties/deactivated', 'FacultiesController@deactivated');

Route::get('/faculties/{faculty}', 'FacultiesController@show');

Route::get('/faculties/{faculty}/edit', 'FacultiesController@edit');

Route::put('/faculties/{faculty}', 'FacultiesController@update');

Route::put('/faculties/{faculty}/update_account', 'FacultiesController@updateAccount');


// End Faculties Routes



Route::resource('/programs', 'ProgramsController');
Route::post('/programs/check_code', 'ProgramsController@check_code');



 // Curricula Routes

Route::get('/curricula', 'CurriculaController@index');
Route::post('/curricula', 'CurriculaController@store');
Route::post('/curricula/{curriculum}/save_curriculum', 'CurriculaController@saveCurriculum');
Route::post('/curricula/{curriculum}/revise', 'CurriculaController@revise');
Route::post('/curricula/{curriculum}/edit', 'CurriculaController@edit');
Route::get('/curricula/{curriculum}/deactivated_courses', 'CurriculaController@deactivatedCourses');
Route::get('/curricula/{curriculum}', 'CurriculaController@show');

// End Curricula Routes




// StudentOutcomes Routes

Route::get('/student_outcomes', 'StudentOutcomesController@index');

Route::get('/student_outcomes/list_program', 'StudentOutcomesController@listProgram');
Route::get('/student_outcomes/{student_outcome}', 'StudentOutcomesController@show');
Route::put('/student_outcomes/{student_outcome}', 'StudentOutcomesController@update');
Route::post('/student_outcomes', 'StudentOutcomesController@store');

// StudentOutcomes Routes






// CurriculumCourses Routes

Route::post('/curriculum_courses', 'CurriculumCoursesController@store');

Route::put('/curriculum_courses/{curriculum_course}', 'CurriculumCoursesController@update');

Route::delete('/curriculum_courses/{curriculum_course}', 'CurriculumCoursesController@destroy');

Route::get('/curriculum_courses/{curriculum_course}', 'CurriculumCoursesController@show');

Route::post('/curriculum_courses/{curriculum_course}/activate', 'CurriculumCoursesController@activate');

Route::post('/curriculum_courses/activate_selected', 'CurriculumCoursesController@activateSelected');

// End CurriculumCourses Routes



// CurriculumMaps Routes

Route::get('/curriculum_mapping', 'CurriculumMapsController@index');

Route::post('/curriculum_mapping/save_maps', 'CurriculumMapsController@saveMaps');

Route::post('/curriculum_mapping/{curriculum}/edit', 'CurriculumMapsController@edit');

Route::get('/curriculum_mapping/{curriculum}', 'CurriculumMapsController@show');

Route::get('/curriculum_mapping', 'CurriculumMapsController@index');

// END CurriculumMaps Routes



//Route::resource('/courses', 'CoursesController');
//Route::get('/courses', 'CoursesController@index');
//Route::get('/courses/{course}', 'CoursesController@show');
//Route::post('/courses', 'CoursesController@store');
//Route::get('/courses/get_courses', 'CoursesController@get_courses');
Route::resource('/courses', 'CoursesController');


// Authentication Routes
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');

Route::post('login', 'Auth\LoginController@login');

Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/home', 'HomeController@index')->name('home');

// End Authentication Routes

// Profile Routes
Route::get('/profile/faculty/{id}', 'ProfileController@faculty');
// End Profile Routes


// Users routes
Route::get('/users/reset_password', 'UsersController@resetPasswordView');

Route::post('/users/activate_selected', 'UsersController@activateSelected');

Route::post('/users/{user}/change_password', 'UsersController@changePassword');

Route::post('/users/{user}/deactivate', 'UsersController@deactivate');

Route::post('/users/{user}/activate', 'UsersController@activate');

Route::post('/users/{user}/reset_password', 'UsersController@resetPassword');

// End Users routes

// Student Routes

Route::get('/students', 'StudentsController@index');

Route::get('/students/create', 'StudentsController@create');

Route::post('/students', 'StudentsController@store');

Route::get('/students/{student}/edit', 'StudentsController@edit');

Route::get('/students/{student}/edit_academic', 'StudentsController@editAcademic');

Route::put('/students/{student}/update_academic', 'StudentsController@updateAcademic');

Route::get('/students/{student}', 'StudentsController@show');

Route::put('/students/{student}', 'StudentsController@update');


// End Student Routes


// TestQuestions Routes

Route::get('/test_questions', 'TestQuestionsController@index');

Route::get('/test_questions/create', 'TestQuestionsController@create');

Route::post('/test_questions', 'TestQuestionsController@store');

Route::get('/test_questions/list_program', 'TestQuestionsController@listProgram');

Route::get('/test_questions/get_creators', 'TestQuestionsController@getCreators');

Route::get('/test_questions/{program}/list_student_outcomes', 'TestQuestionsController@listStudentOutcome');

Route::get('/test_questions/{test_question}/edit', 'TestQuestionsController@edit');

Route::put('/test_questions/{test_question}', 'TestQuestionsController@update');

Route::get('/test_questions/{test_question}', 'TestQuestionsController@show');

Route::get('/test_questions/{test_question}/preview', 'TestQuestionsController@preview');

// End TestQuestions Routes

// ImageObject Routes

Route::get('/image_objects', 'ImageObjectsController@index');


Route::post('/image_objects', 'ImageObjectsController@store');

Route::put('/image_objects/{image_object}', 'ImageObjectsController@update');

Route::get('/image_objects/{image_object}', 'ImageObjectsController@show');

// End ImageObject Routes


// CodeObject Routes

Route::get('/code_objects', 'CodeObjectsController@index');

Route::post('/code_objects', 'CodeObjectsController@store');

Route::get('/code_objects/{code_object}', 'CodeObjectsController@show');

Route::put('/code_objects/{code_object}', 'CodeObjectsController@update');

// End CodeObject Routes

// MathObject Routes

Route::get('/math_objects', 'MathObjectsController@index');

Route::post('/math_objects', 'MathObjectsController@store');

Route::get('/math_objects/{math_object}', 'MathObjectsController@show');

Route::put('/math_objects/{math_object}', 'MathObjectsController@update');

// End MathObject Routes


// Test bank Routes

Route::get('/test_bank/list_programs', 'TestBankController@listProgram');
Route::get('/test_bank/{program}/list_student_outcomes', 'TestBankController@listStudentOutcome');

// End Test bank routes

// Exam routes

Route::get('/exams', 'ExamsController@index');
Route::get('/exams/create', 'ExamsController@create');
Route::post('/exams', 'ExamsController@store');
Route::get('/exams/{exam}', 'ExamsController@show');
Route::get('/exams/{exam}/preview', 'ExamsController@preview');
// End Exam routes




//route for deactivated user
Route::get('/user_is_deactivated', function() {

    if(!Auth::check()) {
        return abort(404, 'Page Not Found');
    }

    if(Auth::user()->is_active == true) {
        return abort(404, 'Page Not Found');
    } 

    Auth::logout();
    return view('user_deactivated');
});
