<?php
// For Language
Route::get('setlocale/{locale}', function ($locale) {
    if (in_array($locale, \Config::get('app.locales'))) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
});
Auth::routes();
Route::group(['prefix' => 'trainer','namespace'=>'trainer'], function () {


    // Must Login
    Route::group(['middleware' => ['Trainer', 'auth']], function(){
        //Home
        Route::get('/home', 'HomeController@index');
        Route::get('/index', 'HomeController@index');
        Route::get('/', 'HomeController@index');

        //Edit And Show Profile
        Route::get('/show', 'ProfileController@show');
        Route::get('/edit', 'ProfileController@edit');
        Route::get('/password', 'ProfileController@password');
        Route::patch('/update', 'ProfileController@update')->name('trainer.user.update');

        //Courses
        Route::get('/courses', 'CoursesController@index');
        Route::get('/courses/overview/{course_id}', 'CoursesController@overview');
        Route::get('/courses/show/{course_id}', 'CoursesController@show');

        //levels
        Route::get('/levels/edit/{id}', 'LevelsController@edit');
        Route::patch('/levels/update/{id}', 'LevelsController@update')->name('trainer.level.update');
        Route::delete('/levels/destroy/{id}', 'LevelsController@destroy')->name('trainer.level.destroy');
        Route::get('/levels/media/{id}', 'LevelsController@media');

        //Videos
        Route::get('/videos/levels/{level_id}', 'VideosController@index');
        Route::get('/videos/create/{level_id}', 'VideosController@create');
        Route::post('/videos/store/{level_id}', 'VideosController@store');
        Route::get('/videos/choose/{level_id}', 'VideosController@choose');
        Route::post('/videos/store/choose/{level_id}', 'VideosController@storeChoose');
        Route::get('/videos/edit/{id}', 'VideosController@edit');
        Route::patch('/videos/update/{id}', 'VideosController@update')->name('trainer.video.update');
        Route::post('/videos/destroy', 'VideosController@destroy')->name('trainer.video.destroy');

        //Photos
        Route::get('/photos/levels/{level_id}', 'PhotosController@index');
        Route::get('/photos/create/{level_id}', 'PhotosController@create');
        Route::post('/photos/store/{level_id}', 'PhotosController@store');
        Route::get('/photos/choose/{level_id}', 'PhotosController@choose');
        Route::post('/photos/store/choose/{level_id}', 'PhotosController@storeChoose');
        Route::get('/photos/edit/{id}', 'PhotosController@edit');
        Route::patch('/photos/update/{id}', 'PhotosController@update')->name('trainer.photo.update');
        Route::post('/photos/destroy', 'PhotosController@destroy')->name('trainer.photo.destroy');

        //Attachment
        Route::get('/attachments/levels/{level_id}', 'AttachmentsController@index');
        Route::get('/attachments/create/{level_id}', 'AttachmentsController@create');
        Route::post('/attachments/store/{level_id}', 'AttachmentsController@store');
        Route::get('/attachments/choose/{level_id}', 'AttachmentsController@choose');
        Route::post('/attachments/store/choose/{level_id}', 'AttachmentsController@storeChoose');
        Route::get('/attachments/edit/{id}', 'AttachmentsController@edit');
        Route::patch('/attachments/update/{id}', 'AttachmentsController@update')->name('trainer.attachment.update');
        Route::post('/attachments/destroy', 'AttachmentsController@destroy')->name('trainer.attachment.destroy');

        //Scorm
        Route::get('/scorms/levels/{level_id}', 'ScormsController@index');
        Route::get('/scorms/play/{id}', 'ScormsController@play');
        Route::get('/scorms/choose/{level_id}', 'ScormsController@choose');
        Route::post('/scorms/store/choose/{level_id}', 'ScormsController@storeChoose');
        Route::post('/scorms/destroy', 'ScormsController@destroy')->name('trainer.scorm.destroy');


        //Virtual Classroom
        Route::get('/rooms/{course_id}', 'ClassroomsController@index');
        Route::get('/rooms/create/{course_id}', 'ClassroomsController@create');
        Route::post('/rooms/store/{course_id}', 'ClassroomsController@store');
        Route::get('/rooms/edit/{id}', 'ClassroomsController@edit');
        Route::patch('/rooms/update/{id}', 'ClassroomsController@update')->name('trainer.room.update');
        Route::post('/rooms/destroy', 'ClassroomsController@destroy')->name('trainer.room.destroy');

        //discussions
        Route::get('/discussions/{course_id}', 'DiscussionsController@index');
        Route::get('/discussions/create/{course_id}', 'DiscussionsController@create');
        Route::post('/discussions/store/{course_id}', 'DiscussionsController@store');
        Route::get('/discussions/edit/{id}', 'DiscussionsController@edit');
        Route::patch('/discussions/update/{id}', 'DiscussionsController@update')->name('trainer.discussion.update');
        Route::post('/discussions/destroy', 'DiscussionsController@destroy')->name('trainer.discussion.destroy');
        Route::get('/discussions/show/{id}', 'DiscussionsController@show');
        Route::post('/discussions/comment/{id}', 'DiscussionsController@comment');

        //users
        Route::get('/users/{course_id}', 'UsersController@index');
        Route::get('autocomplete/course/users', 'UsersController@autocompleteCourse')->name('autocomplete.user');
        Route::get('users/create/{course_id}', 'UsersController@create');
        Route::post('users/store/{course_id}', 'UsersController@store');
        Route::get('/users/type/{id}', 'UsersController@type');
        Route::patch('/users/type/update/{id}', 'UsersController@typeUpdate')->name('trainer.users.update');
        Route::post('/users/destroy', 'UsersController@destroy')->name('trainer.users.destroy');

        //Messages
        Route::get('messages', 'MessagesController@index');
        Route::get('messages/message/{sender_id}', 'MessagesController@message');
        Route::get('messages/show/{sender_id?}', 'MessagesController@show');
        Route::post('messages/send/{sender_id?}', 'MessagesController@send');

        //Projects
        Route::get('/projects/{level_id}', 'ProjectsController@index');
        Route::get('/projects/create/{level_id}', 'ProjectsController@create');
        Route::post('/projects/store/{level_id}', 'ProjectsController@store');
        Route::get('/projects/edit/{id}', 'ProjectsController@edit');
        Route::patch('/projects/update/{id}', 'ProjectsController@update')->name('trainer.project.update');
        Route::post('/projects/destroy', 'ProjectsController@destroy')->name('trainer.project.destroy');
        Route::get('/projects/compelete/{id}', 'ProjectsController@compelete');
        Route::get('/projects/compelete/show/{id}', 'ProjectsController@compeleteShow');
        Route::post('/projects/rate/{id}', 'ProjectsController@rate');
        Route::post('/projects/rate/destroy', 'ProjectsController@destroyRate')->name('trainer.rate.destroy');

        //Exams
        //First:Banks
        Route::get('/banks/{course_id}', 'BanksController@index');
        Route::get('/banks/create/{course_id}', 'BanksController@create');
        Route::post('/banks/store/{course_id}', 'BanksController@store');
        Route::get('/banks/edit/{id}', 'BanksController@edit');
        Route::patch('/banks/update/{id}', 'BanksController@update')->name('trainer.bank.update');
        Route::post('/banks/destroy', 'BanksController@destroy')->name('trainer.bank.destroy');
        Route::get('/banks/show/{id}', 'BanksController@show');

        //Second:Exams
        Route::get('/exams/{course_id}', 'ExamsController@index');
        Route::get('/exams/create/{course_id}', 'ExamsController@create');
        Route::post('/exams/store/{course_id}', 'ExamsController@store');
        Route::get('/exams/edit/{id}', 'ExamsController@edit');
        Route::patch('/exams/update/{id}', 'ExamsController@update')->name('trainer.exam.update');
        Route::post('/exams/destroy', 'ExamsController@destroy')->name('trainer.exam.destroy');
        Route::get('/exams/show/{id}', 'ExamsController@show');
        Route::get('/exams/receive/{id}', 'ExamsController@receive');
        Route::get('/exams/correct/{id}', 'ExamsController@receiveCreate');
        Route::get('/exams/try/{exam_user_id}', 'ExamsController@addTry');
        Route::post('/exams/receive/store/{id}', 'ExamsController@receiveStore');
        Route::post('/exams/receive/destroy', 'ExamsController@destroyReceive')->name('trainer.receive.destroy');
    });
});
