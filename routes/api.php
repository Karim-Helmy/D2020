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
Route::namespace('api\student')->prefix('user')->group(function () {
    //login
    Route::post('login','UsersController@login');
    //Setting Page
    Route::get('contact','SettingsController@contact');
    Route::get('about','SettingsController@about');
    Route::get('categories','HomeController@categories');
    Route::get('cities','HomeController@cities');
    Route::get('subscribers','HomeController@subscribers');
    Route::get('stores','HomeController@stores');
    Route::get('category_stores/{id}','HomeController@category');
    Route::get('store_details/{id}','HomeController@store_details');
    Route::post('add_store_review/{id}','HomeController@add_store_review');
    Route::post('add_store_location/{id}','HomeController@add_store_location');
    Route::post('register','UsersController@register');


    Route::group(['middleware' => 'jwt.verify'], function (){
        Route::get('check','UsersController@check');
        //users
        Route::get('profile','UsersController@profile')->name('api.student.profile');
        Route::post('profile/update','UsersController@update')->name('api.student.profile.update');

        //messages
        Route::get('messages','MessagesController@index');
        Route::get('messages/show/details','MessagesController@show')->name('api.student.messages.show');
        Route::post('messages/send','MessagesController@send')->name('api.student.messages.send');

    });
});
