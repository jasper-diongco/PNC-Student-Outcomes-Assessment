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

Route::get('/faculties/search_faculties', 'FacultiesController@searchFaculties');

Route::get('/faculties/dashboard', 'FacultiesController@dashboard');

Route::get('/faculties/create', 'FacultiesController@create');

Route::post('/faculties', 'FacultiesController@store');

Route::get('/faculties/get_faculty/{faculty}', 'FacultiesController@getFaculty');

Route::get('/faculties/deactivated', 'FacultiesController@deactivated');

Route::get('/faculties/{faculty}', 'FacultiesController@show');

Route::get('/faculties/{faculty}/edit', 'FacultiesController@edit');

Route::put('/faculties/{faculty}', 'FacultiesController@update');

Route::put('/faculties/{faculty}/update_account', 'FacultiesController@updateAccount');


// End Faculties Routes


Route::post('/programs/{program}/save_student_outcomes', 'ProgramsController@save_student_outcomes');
Route::post('/programs/{program}/revise_student_outcomes', 'ProgramsController@revise_student_outcomes');

Route::get('/programs/{program}/get_learning_levels', 'ProgramsController@get_learning_levels');
Route::post('/programs/{program}/add_learning_level', 'ProgramsController@add_learning_level');
Route::put('/programs/{program}/update_learning_level/{learning_level}', 'ProgramsController@update_learning_level');
Route::resource('/programs', 'ProgramsController');
Route::post('/programs/check_code', 'ProgramsController@check_code');



 // Curricula Routes

Route::get('/curricula', 'CurriculaController@index');
Route::post('/curricula', 'CurriculaController@store');
Route::post('/curricula/{curriculum}/save_curriculum', 'CurriculaController@saveCurriculum');
Route::post('/curricula/{curriculum}/revise', 'CurriculaController@revise');
Route::post('/curricula/{curriculum}/edit', 'CurriculaController@edit');
Route::get('/curricula/{curriculum}/deactivated_courses', 'CurriculaController@deactivatedCourses');
Route::get('/curricula/{curriculum}/print_curriculum', 'CurriculaController@print_curriculum');
Route::get('/curricula/{curriculum}', 'CurriculaController@show');

// End Curricula Routes




// StudentOutcomes Routes

Route::get('/student_outcomes', 'StudentOutcomesController@index');
Route::post('/student_outcomes', 'StudentOutcomesController@store');
Route::get('/student_outcomes/list_program', 'StudentOutcomesController@listProgram');
Route::get('/student_outcomes/{student_outcome}', 'StudentOutcomesController@show');
Route::put('/student_outcomes/{student_outcome}', 'StudentOutcomesController@update');
Route::delete('/student_outcomes/{student_outcome}', 'StudentOutcomesController@delete');
Route::post('/student_outcomes/{student_outcome}/activate', 'StudentOutcomesController@activate');

Route::post('/student_outcomes/{student_outcome}/change_assessment_type', 'StudentOutcomesController@change_assessment_type');


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

Route::get('/curriculum_mapping/{curriculum}/print_curriculum_mapping', 'CurriculumMapsController@print_curriculum_mapping');

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

// Route::get('/test_questions/set_choices_order', 'TestQuestionsController@set_choices_order');

Route::get('/test_questions/search_deactivated', 'TestQuestionsController@search_deactivated');

Route::get('/test_questions/create', 'TestQuestionsController@create');

Route::post('/test_questions', 'TestQuestionsController@store');

Route::get('/test_questions/list_program', 'TestQuestionsController@listProgram');

Route::get('/test_questions/get_creators', 'TestQuestionsController@getCreators');

Route::get('/test_questions/{program}/list_student_outcomes', 'TestQuestionsController@listStudentOutcome');

Route::get('/test_questions/get_test_question/{test_question}', 'TestQuestionsController@get_test_question');

Route::get('/test_questions/{test_question}/edit', 'TestQuestionsController@edit');

Route::get('/test_questions/{test_question}/preview', 'TestQuestionsController@preview');

Route::post('/test_questions/{test_question}/archive', 'TestQuestionsController@archive');

Route::post('/test_questions/{test_question}/activate', 'TestQuestionsController@activate');



Route::put('/test_questions/{test_question}', 'TestQuestionsController@update');

Route::get('/test_questions/{test_question}', 'TestQuestionsController@show');



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
Route::get('/test_bank', 'TestBankController@index');
Route::get('/test_bank/show_programming_test_bank', 'TestBankController@show_programming_test_bank');
Route::get('/test_bank/list_programs', 'TestBankController@listProgram');
Route::get('/test_bank/{program}/get_curricula', 'TestBankController@get_curricula');
Route::get('/test_bank/{program}/list_student_outcomes', 'TestBankController@listStudentOutcome');
Route::get('/test_bank/{program}/get_student_outcomes', 'TestBankController@get_student_outcomes');
Route::get('/test_bank/get_curriculum_courses_mapped/{student_outcome_id}', 'TestBankController@get_curriculum_courses_mapped');
// End Test bank routes

// Exam routes

Route::get('/exams', 'ExamsController@index');
// Route::get('/exams/generate_pos_order', 'ExamsController@generate_pos_order');
Route::get('/exams/get_exams', 'ExamsController@get_exams');
Route::get('/exams/create', 'ExamsController@create');
Route::post('/exams/revise_exam/{exam}', 'ExamsController@revise_exam');
Route::post('/exams', 'ExamsController@store');

Route::get('/exams/{exam}/preview', 'ExamsController@preview');
Route::get('/exams/{exam}/item_analysis', 'ExamsController@item_analysis');
Route::get('/exams/{exam}/print_answer_key', 'ExamsController@print_answer_key');
Route::post('/exams/{exam}/deactivate', 'ExamsController@deactivate');

Route::post('/exams/{exam}/activate', 'ExamsController@activate');
Route::post('/exams/{exam}/start_item_analysis', 'ExamsController@start_item_analysis');
Route::get('/exams/item_analysis_result/{item_analysis}', 'ExamsController@item_analysis_result');
Route::get('/exams/{exam}', 'ExamsController@show');

// End Exam routes

//OBE Routes

Route::get('/students/{student}/obe_curriculum', 'OBEController@show');
Route::get('/students/{student}/obe_curriculum_print', 'OBEController@print_obe');
Route::get('/students/{student}/refresh_student_grades', 'OBEController@refreshStudentGrades');

//Grade Routes
Route::post('/grades', 'GradesController@store');



//Item Analysis Routes
Route::post('/item_analysis/{test_question}/reject_test_question', 'ItemAnalysisController@reject_test_question');

Route::post('/item_analysis/{test_question}/retain_test_question', 'ItemAnalysisController@retain_test_question');

Route::post('/item_analysis/{test_question}/change_level_of_difficulty', 'ItemAnalysisController@change_level_of_difficulty');

// Route::get('/item_analysis/result/{item_analysis}', 'ItemAnalysisController@item_analysis_result');



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



/*********************   Students routes    ************************/

//home route

Route::get('/s/home', 's\HomeController@index');

//obe route

Route::get('/s/{student}/obe_curriculum', 's\StudentOBEController@show');

//assessments routes

Route::get('/s/assessments', 's\AssessmentsController@index');

Route::get('/s/assessments/show_score', 's\AssessmentsController@show_score');

Route::get('/s/assessments/{student_outcome}', 's\AssessmentsController@show');

Route::post('/s/assessments/retake_assessment/{student_outcome}', 's\AssessmentsController@retake_assessment');

Route::post('/s/assessments/{answer_sheet}', 's\AssessmentsController@store');



Route::post('/s/assessments/select_choice/{answer_sheet_test_question}', 's\AssessmentsController@select_choice');


//assessment results routes

Route::get('/assessment_results', 'AssessmentResultsController@index');
Route::get('/assessment_results/get_assessments', 'AssessmentResultsController@get_assessments');
Route::get('/assessment_results/{assessment}/print_assessment_result', 'AssessmentResultsController@print_assessment_result');
Route::get('/assessment_results/{assessment}/print_answer_key', 'AssessmentResultsController@print_answer_key');
Route::get('/assessment_results/{assessment}', 'AssessmentResultsController@show');



//user profile routes

Route::get('/s/my_profile/{user}', 'UserProfilesController@show');



Route::post('/user_profiles/{user}/update_account_information', 'UserProfilesController@updateAccountInformation');

Route::post('/user_profiles/{user}/update_password', 'UserProfilesController@updatePassword');

Route::post('/user_profiles/{user}/update_basic_info', 'UserProfilesController@updateBasicInformation');

Route::post('/user_profiles/{user}/update_main_info', 'UserProfilesController@updateMainInfo');

//for faculties

Route::get('/faculties/{user}/profile', 'UserProfilesController@show');


Route::get('/users/{user}/super_admin_profile', 'UserProfilesController@show');


//faculty_course routes
Route::get('/faculty_courses', 'FacultyCoursesController@index');


Route::post('/faculty_courses', 'FacultyCoursesController@store');

Route::get('/faculty_courses/{faculty_course}/get_faculty_course_test_questions', 'FacultyCoursesController@getFacultyCourseTestQuestions');

Route::post('/faculty_courses/{faculty_course}/activate', 'FacultyCoursesController@activate');

Route::post('/faculty_courses/{faculty_course}/deactivate', 'FacultyCoursesController@deactivate');


//test questions problem routes
Route::get('/test_question_problems', 'TestQuestionProblemsController@index');

Route::get('/test_question_problems/get_test_question_problems', 'TestQuestionProblemsController@get_test_question_problems');

Route::post('/test_question_problems/{test_question_problem}/resolve', 'TestQuestionProblemsController@resolve');

Route::post('/test_question_problems', 'TestQuestionProblemsController@store');


//custom recorded assessment routes

Route::get('/custom_recorded_assessments', 'CustomRecordedAssessmentsController@index');

Route::get('/custom_recorded_assessments/get_records/{custom_recorded_assessment}', 'CustomRecordedAssessmentsController@get_records');

Route::get('/custom_recorded_assessments/{custom_recorded_assessment}', 'CustomRecordedAssessmentsController@show');

Route::post('/custom_recorded_assessments', 'CustomRecordedAssessmentsController@store');


//custom recorded assessment records routes
Route::get('/custom_recorded_assessment_records/get_courses_grade', 'CustomRecordedAssessmentRecordsController@getCoursesGrade');

Route::post('/custom_recorded_assessment_records/{record}', 'CustomRecordedAssessmentRecordsController@update');

Route::post('/custom_recorded_assessment_records', 'CustomRecordedAssessmentRecordsController@store');


//settings routes

Route::get('/application_settings', 'SettingsController@index');

Route::get('/application_settings/get_show_assessment_details_to_student', 'SettingsController@get_show_assessment_details_to_student');

Route::post('/application_settings/update_settings', 'SettingsController@update');


//backup and restore routes
Route::get('/maintenance', 'BackupRestoreController@index');
Route::get('/maintenance/backup', 'BackupRestoreController@backup');













