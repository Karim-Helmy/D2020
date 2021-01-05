<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::namespace('api')->group(function () {
    Route::post('site/login','UsersController@login');
    Route::post('site/profile','UsersController@profile');
});
###################################### Father ############################
Route::namespace('api\father')->prefix('father')->group(function () {
    //login
    Route::post('login','UsersController@login');
    //Setting Page
    Route::get('contact','SettingsController@contact');
    Route::get('about','SettingsController@about');


    Route::group(['middleware' => 'jwt.verify'], function (){
        Route::get('check','UsersController@check');
        //users
        Route::get('profile','UsersController@profile');
        Route::post('profile/update','UsersController@update');

        //messages
        Route::get('messages/{user_id}','MessagesController@index');
        Route::get('messages/show/details','MessagesController@show');
        Route::post('messages/send','MessagesController@send');

        //Activities And Reports
        Route::get('reports/{user_id}','ReportsController@index');
    });
});



###################################### Student ############################
Route::namespace('api\student')->prefix('student')->group(function () {
    //login
    Route::post('login','UsersController@login');
    //Setting Page
    Route::get('contact','SettingsController@contact');
    Route::get('about','SettingsController@about');
    Route::get('scorms/play/{id}','MediaController@play')->name('api.student.scorm.play');

    Route::group(['middleware' => 'jwt.verify'], function (){
        Route::get('check','UsersController@check');
        //users
        Route::get('profile','UsersController@profile')->name('api.student.profile');
        Route::post('profile/update','UsersController@update')->name('api.student.profile.update');

        //messages
        Route::get('messages','MessagesController@index');
        Route::get('messages/show/details','MessagesController@show')->name('api.student.messages.show');
        Route::post('messages/send','MessagesController@send')->name('api.student.messages.send');

        //Courses
        Route::get('courses','CoursesController@index');
        Route::get('courses/show/{course_id}','CoursesController@show')->name('api.student.courses');

        //Class Rooms
        Route::get('rooms/{course_id}','CoursesController@rooms')->name('api.student.rooms');

        //Discussions
        Route::get('discussions/{course_id}','CoursesController@discussions')->name('api.student.discussions');
        Route::post('discussions/send','CoursesController@send');

        //Photos
        Route::get('photos/{level_id}','MediaController@photo')->name('api.student.photo');

        //Videos
        Route::get('videos/{level_id}','MediaController@video')->name('api.student.video');

        //Attachments
        Route::get('attachments/{level_id}','MediaController@attachment')->name('api.student.attachment');

        //Scorms
        Route::get('scorms/{level_id}','MediaController@scorm')->name('api.student.scorm');

        //Projects
        Route::get('projects/{level_id}','ProjectsController@index')->name('api.student.project');
        Route::post('projects/send','ProjectsController@send')->name('api.student.project.send');

        //Exams
        Route::get('exams/{course_id}','ExamsController@index')->name('api.student.exam');
        Route::get('exams/best/{id}','ExamsController@best')->name('api.student.exam.best');
        Route::get('exams/question/{id}','ExamsController@question')->name('api.student.exam.question');
        Route::get('exams/start/{id}','ExamsController@start')->name('api.student.exam.start');
        Route::get('exams/check/{id}','ExamsController@check')->name('api.student.exam.check');
        Route::post('exams/answer/{id}','ExamsController@send')->name('api.student.exam.answer');

        //Appointments
        Route::get('appointments','AppointmentsController@index')->name('api.student.appointment');
    });
});
