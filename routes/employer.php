<?php
// For Language
Route::get('setlocale/{locale}', function ($locale) {
    if (in_array($locale, \Config::get('app.locales'))) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
});

Route::group(['prefix' => 'employer','namespace'=>'employer'], function () {
    // Must Login
    Route::group(['middleware' => 'admin:webAdmin'], function(){

        ######################### Subscribers #################################
        Route::get('/subscribers', 'SubscribersController@index');
        Route::get('subscribers/edit-password/{id}', 'SubscribersController@editPassword')->name('subscribers.password');
        Route::patch('subscribers/update-password/{id}', 'SubscribersController@updatePassword')->name('subscribers.update_password');

        ######################### Users #################################
        Route::get('/users/{id}', 'UsersController@index');
        Route::get('users/excel/{id}', 'UsersController@excel');
        Route::post('users/import/{id}', 'UsersController@import');
        Route::get('users/import/get/{id}', 'UsersController@importGet');
        Route::post('users/import/store/{id}', 'UsersController@importStore');
        Route::get('users/create/{id}', 'UsersController@create');
        Route::post('users/store/{id}', 'UsersController@store');
        Route::get('users/edit/{subcriber_id}/{id}', 'UsersController@edit');
        Route::patch('users/update/{subcriber_id}/{id}', 'UsersController@update')->name('employer.users.update');
        Route::post('users/destroy/{id}', 'UsersController@destroy')->name('employer.users.destroy');
        Route::get('fathers/{id}', 'UsersController@father');

        ######################### Courses #################################
        Route::get('courses/{id}', 'CoursesController@index');
        Route::get('courses/create/{course_id}', 'CoursesController@create');
        Route::post('courses/store/{course_id}', 'CoursesController@store');
        Route::get('courses/edit/{subcriber_id}/{id}', 'CoursesController@edit');
        Route::get('courses/show/{subcriber_id}/{id}', 'CoursesController@show');
        Route::post('courses/update/{subcriber_id}/{id}', 'CoursesController@update')->name('super.courses.update');
        Route::post('courses/destroy/{id}', 'CoursesController@destroy')->name('super.courses.destroy');
        Route::post('courses/assignuser', 'CoursesController@courseuser');

        ######################### Assign Users To Courses #################################
        Route::get('assign/create/{course_id}/{subscriber_id}', 'CourseUsersController@create');
        Route::get('autocomplete/course/{subscriber_id}', 'CourseUsersController@autocompleteCourse')->name('employer.autocomplete.course');
        Route::post('assign/store/{course_id}/{subscriber_id}', 'CourseUsersController@store');
        Route::get('/assign/{course_id}/{subscriber_id}', 'CourseUsersController@index');
        Route::get('assign/excel/{id}/{subscriber_id}', 'CourseUsersController@excel');
        Route::post('assign/import/{id}/{subscriber_id}', 'CourseUsersController@import');
        Route::post('/assign/destroy/{subscriber_id}', 'CourseUsersController@destroy')->name('employer.assign.destroy');

        ######################### Assign Users To Groups #################################
        Route::get('groups/create/{course_id}/{subscriber_id}', 'GroupUsersController@create');
        Route::post('groups/store/{course_id}/{subscriber_id}', 'GroupUsersController@store');
        Route::get('/groups/{course_id}/{subscriber_id}', 'GroupUsersController@index');
        Route::get('/groups/edit/{id}/{subscriber_id}', 'GroupUsersController@edit');
        Route::patch('/groups/update/{id}/{subscriber_id}', 'GroupUsersController@update')->name('employer.groups.update');
        Route::post('/groups/destroy/{subscriber_id}', 'GroupUsersController@destroy')->name('employer.groups.destroy');

        ######################### Class Rooms #################################
        Route::get('rooms/create/{course_id}/{subscriber_id}', 'ClassRoomsController@create');
        Route::post('rooms/store/{course_id}/{subscriber_id}', 'ClassRoomsController@store');
        Route::get('/rooms/{course_id}/{subscriber_id}', 'ClassRoomsController@index');
        Route::get('/rooms/edit/{id}/{subscriber_id}', 'ClassRoomsController@edit');
        Route::patch('/rooms/update/{id}/{subscriber_id}', 'ClassRoomsController@update')->name('employer.rooms.update');
        Route::post('/rooms/destroy/{subscriber_id}', 'ClassRoomsController@destroy')->name('employer.rooms.destroy');

        ######################### Discussions #################################
        Route::get('/discussions/{course_id}/{subscriber_id}', 'DiscussionsController@index');
        Route::get('/discussions/create/{course_id}/{subscriber_id}', 'DiscussionsController@create');
        Route::post('/discussions/store/{course_id}/{subscriber_id}', 'DiscussionsController@store');
        Route::get('/discussions/edit/{id}/{subscriber_id}', 'DiscussionsController@edit');
        Route::patch('/discussions/update/{id}/{subscriber_id}', 'DiscussionsController@update')->name('employer.discussion.update');
        Route::get('/discussions/show/{id}/{subscriber_id}', 'DiscussionsController@show');
        Route::post('/discussions/destroy/{subscriber_id}', 'DiscussionsController@destroy')->name('employer.discussion.destroy');
        Route::post('/discussions/destroy/item/{subscriber_id}', 'DiscussionsController@destroyItem');

        ######################### Photos #################################
        Route::get('/photos/levels/{level_id}/{subscriber_id}', 'PhotosController@index');
        Route::get('/photos/create/{level_id}/{subscriber_id}', 'PhotosController@create');
        Route::post('/photos/store/{level_id}/{subscriber_id}', 'PhotosController@store');
        Route::get('/photos/choose/{level_id}/{subscriber_id}', 'PhotosController@choose');
        Route::post('/photos/store/choose/{level_id}/{subscriber_id}', 'PhotosController@storeChoose');
        Route::get('/photos/edit/{id}/{subscriber_id}', 'PhotosController@edit');
        Route::patch('/photos/update/{id}/{subscriber_id}', 'PhotosController@update')->name('employer.photo.update');
        Route::post('/photos/destroy/{subscriber_id}', 'PhotosController@destroy')->name('employer.photo.destroy');

        ######################### Videos #################################
        Route::get('/videos/levels/{level_id}/{subscriber_id}', 'VideosController@index');
        Route::get('/videos/create/{level_id}/{subscriber_id}', 'VideosController@create');
        Route::post('/videos/store/{level_id}/{subscriber_id}', 'VideosController@store');
        Route::get('/videos/choose/{level_id}/{subscriber_id}', 'VideosController@choose');
        Route::post('/videos/store/choose/{level_id}/{subscriber_id}', 'VideosController@storeChoose');
        Route::get('/videos/edit/{id}/{subscriber_id}', 'VideosController@edit');
        Route::patch('/videos/update/{id}/{subscriber_id}', 'VideosController@update')->name('employer.video.update');
        Route::post('/videos/destroy/{subscriber_id}', 'VideosController@destroy')->name('employer.video.destroy');

        ######################### Attachments #################################
        Route::get('/attachments/levels/{level_id}/{subscriber_id}', 'AttachmentsController@index');
        Route::get('/attachments/create/{level_id}/{subscriber_id}', 'AttachmentsController@create');
        Route::post('/attachments/store/{level_id}/{subscriber_id}', 'AttachmentsController@store');
        Route::get('/attachments/choose/{level_id}/{subscriber_id}', 'AttachmentsController@choose');
        Route::post('/attachments/store/choose/{level_id}/{subscriber_id}', 'AttachmentsController@storeChoose');
        Route::get('/attachments/edit/{id}/{subscriber_id}', 'AttachmentsController@edit');
        Route::patch('/attachments/update/{id}/{subscriber_id}', 'AttachmentsController@update')->name('employer.attachment.update');
        Route::post('/attachments/destroy/{subscriber_id}', 'AttachmentsController@destroy')->name('employer.attachment.destroy');

        ######################### Scorms #################################
        Route::get('/scorms/levels/{level_id}/{subscriber_id}', 'ScormsController@index');
        Route::get('/scorms/play/{id}/{subscriber_id}', 'ScormsController@play');
        Route::get('/scorms/choose/{level_id}/{subscriber_id}', 'ScormsController@choose');
        Route::post('/scorms/store/choose/{level_id}/{subscriber_id}', 'ScormsController@storeChoose');
        Route::post('/scorms/destroy/{subscriber_id}', 'ScormsController@destroy')->name('employer.scorm.destroy');

        ######################### Projects #################################
        Route::get('/projects/levels/{level_id}/{subscriber_id}', 'ProjectsController@index');
        Route::get('/projects/create/{level_id}/{subscriber_id}', 'ProjectsController@create');
        Route::post('/projects/store/{level_id}/{subscriber_id}', 'ProjectsController@store');
        Route::get('/projects/edit/{id}/{subscriber_id}', 'ProjectsController@edit');
        Route::patch('/projects/update/{id}/{subscriber_id}', 'ProjectsController@update')->name('employer.project.update');
        Route::post('/projects/destroy/{subscriber_id}', 'ProjectsController@destroy')->name('employer.project.destroy');

        ######################### Banks #################################
        Route::get('/banks/{course_id}/{subscriber_id}', 'BanksController@index');
        Route::get('/banks/create/{course_id}/{subscriber_id}', 'BanksController@create');
        Route::post('/banks/store/{course_id}/{subscriber_id}', 'BanksController@store');
        Route::get('/banks/edit/{id}/{subscriber_id}', 'BanksController@edit');
        Route::patch('/banks/update/{id}/{subscriber_id}', 'BanksController@update')->name('employer.bank.update');
        Route::post('/banks/destroy/{subscriber_id}', 'BanksController@destroy')->name('employer.bank.destroy');
        Route::get('/banks/show/{id}/{subscriber_id}', 'BanksController@show');

        ######################### Exams #################################
        Route::get('/exams/{course_id}/{subscriber_id}', 'ExamsController@index');
        Route::get('/exams/create/{course_id}/{subscriber_id}', 'ExamsController@create');
        Route::post('/exams/store/{course_id}/{subscriber_id}', 'ExamsController@store');
        Route::get('/exams/edit/{id}/{subscriber_id}', 'ExamsController@edit');
        Route::patch('/exams/update/{id}/{subscriber_id}', 'ExamsController@update')->name('employer.exam.update');
        Route::post('/exams/destroy/{subscriber_id}', 'ExamsController@destroy')->name('employer.exam.destroy');
        Route::get('/exams/show/{id}/{subscriber_id}', 'ExamsController@show');

    });
});
