<?php
// For Language
Route::get('setlocale/{locale}', function ($locale) {
    if (in_array($locale, \Config::get('app.locales'))) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
});

Route::group(['prefix' => 'admin','namespace'=>'admin'], function () {

    // Without Login
    Route::get('login', 'AdminController@login');
    Route::post('login/store', 'AdminController@store');

    // Must Login
    Route::group(['middleware' => 'admin:webAdmin'], function(){
        ######################### Admins #################################
        Route::get('admins/edit/{id}', 'AdminController@edit');
        Route::patch('admins/update/{id}', 'AdminController@update');
        Route::get('admins/user', 'AdminController@user');
        Route::patch('admins/update/user', 'AdminController@updateUser');
        Route::get('/', 'AdminController@index');
        Route::get('admins', 'AdminController@admins');
        Route::get('admins/create', 'AdminController@create');
        Route::post('admins/create', 'AdminController@save');
        Route::get('/index', 'AdminController@index');
        Route::get('/logout', 'AdminController@logout');
        Route::post('admins/destroy', 'AdminController@destroy');

        ######################### Options #################################
        Route::get('options', 'OptionsController@index');
        Route::get('options/create', 'OptionsController@create')->name('options.create');
        Route::post('options/store', 'OptionsController@store')->name('options.store');
        Route::get('options/edit/{id}', 'OptionsController@edit')->name('options.edit');
        Route::patch('options/update/{id}', 'OptionsController@update')->name('options.update');
        Route::post('options/destroy', 'OptionsController@destroy');

        ######################### Packages #################################
        Route::get('packages', 'PackagesController@index');
        Route::get('packages/show/{id}', 'PackagesController@show');
        Route::get('packages/create', 'PackagesController@create')->name('packages.create');
        Route::post('packages/store', 'PackagesController@store')->name('packages.store');
        Route::get('packages/edit/{id}', 'PackagesController@edit')->name('packages.edit');
        Route::patch('packages/update/{id}', 'PackagesController@update')->name('packages.update');
        Route::post('packages/destroy', 'PackagesController@destroy');


        ######################### Logos #################################
        Route::get('logos', 'LogosController@index');
        Route::get('logos/show/{id}', 'LogosController@show');
        Route::get('logos/create', 'LogosController@create')->name('logos.create');
        Route::post('logos/store', 'LogosController@store')->name('logos.store');
        Route::get('logos/edit/{id}', 'LogosController@edit')->name('logos.edit');
        Route::patch('logos/update/{id}', 'LogosController@update')->name('logos.update');
        Route::post('logos/destroy', 'LogosController@destroy');

        ######################### About Pages #################################
        Route::get('about/edit', 'AboutPagesController@edit');
        Route::patch('about/update', 'AboutPagesController@update')->name('about.update');

        ######################### Settings #################################
        Route::resource('/settings', 'SettingsController');

        ######################### Contacts #################################
        Route::get('/contacts', 'ContactsController@index');
        Route::get('/contacts/show/{id}', 'ContactsController@show');
        Route::delete('/contacts/destroy/{id}', 'ContactsController@destroy');

        ######################### Messages #################################
        Route::get('messages', 'MessagesController@index');
        Route::get('messages/show/{sender_id?}', 'MessagesController@show');
        Route::post('messages/send/{sender_id?}', 'MessagesController@send');



        ######################### Subscriptions #################################
        Route::get('/subscribers', 'SubscribersController@index');
        Route::get('/subscribers/details', 'SubscribersController@details');
        Route::get('subscribers/active/{id}', 'SubscribersController@active');
        Route::get('subscribers/create', 'SubscribersController@create')->name('subscribers.create');
        Route::post('subscribers/store', 'SubscribersController@store')->name('subscribers.store');
        Route::get('subscribers/edit/{id}', 'SubscribersController@edit')->name('subscribers.edit');
        Route::patch('subscribers/update/{id}', 'SubscribersController@update')->name('subscribers.update');
        Route::get('subscribers/edit-password/{id}', 'SubscribersController@editPassword')->name('subscribers.password');
        Route::patch('subscribers/update-password/{id}', 'SubscribersController@updatePassword')->name('subscribers.update_password');
        Route::post('subscribers/destroy', 'SubscribersController@destroy');

        ######################### Categories #################################
        Route::get('categories', 'CategoriesController@index');
        Route::get('categories/show/{id}', 'CategoriesController@show');
        Route::get('categories/create', 'CategoriesController@create')->name('categories.create');
        Route::post('categories/store', 'CategoriesController@store')->name('categories.store');
        Route::get('categories/edit/{id}', 'CategoriesController@edit')->name('categories.edit');
        Route::patch('categories/update/{id}', 'CategoriesController@update')->name('categories.update');
        Route::post('categories/destroy', 'CategoriesController@destroy');

        ######################### Cities #################################
        Route::get('cities', 'CitiesController@index');
        Route::get('cities/show/{id}', 'CitiesController@show');
        Route::get('cities/create', 'CitiesController@create')->name('cities.create');
        Route::post('cities/store', 'CitiesController@store')->name('cities.store');
        Route::get('cities/edit/{id}', 'CitiesController@edit')->name('cities.edit');
        Route::patch('cities/update/{id}', 'CitiesController@update')->name('cities.update');
        Route::post('cities/destroy', 'CitiesController@destroy');


        //Photos
        Route::get('photos', 'PhotosController@index');
        Route::get('photos/create', 'PhotosController@create')->name('photos.create');
        Route::post('photos/store', 'PhotosController@store')->name('photos.store');
        Route::get('photos/edit/{id}', 'PhotosController@edit')->name('photos.edit');
        Route::patch('photos/update/{id}', 'PhotosController@update')->name('photos.update');
        Route::delete('photos/destroy/{id}', 'PhotosController@destroy')->name('photos.destroy');

        ######################### videos #################################
        Route::get('videos', 'VideosController@index');
        Route::get('videos/show/{id}', 'VideosController@show');
        Route::get('videos/create', 'VideosController@create')->name('videos.create');
        Route::post('videos/store', 'VideosController@store')->name('videos.store');
        Route::get('videos/edit/{id}', 'VideosController@edit')->name('videos.edit');
        Route::patch('videos/update/{id}', 'VideosController@update')->name('videos.update');
        Route::delete('videos/destroy/{id}', 'VideosController@destroy')->name('videos.destroy');

        ######################### attachments #################################
        Route::get('attachments', 'AttachmentsController@index');
        Route::get('attachments/show/{id}', 'AttachmentsController@show');
        Route::get('attachments/create', 'AttachmentsController@create')->name('attachments.create');
        Route::post('attachments/store', 'AttachmentsController@store')->name('attachments.store');
        Route::get('attachments/edit/{id}', 'AttachmentsController@edit')->name('attachments.edit');
        Route::patch('attachments/update/{id}', 'AttachmentsController@update')->name('attachments.update');
        Route::delete('attachments/destroy/{id}', 'AttachmentsController@destroy')->name('attachments.destroy');


        ######################### Stages #################################
        Route::get('stages', 'StagesController@index');
        Route::get('stages/show/{id}', 'StagesController@show');
        Route::get('stages/create', 'StagesController@create')->name('stages.create');
        Route::post('stages/store', 'StagesController@store')->name('stages.store');
        Route::get('stages/edit/{id}', 'StagesController@edit')->name('stages.edit');
        Route::patch('stages/update/{id}', 'StagesController@update')->name('stages.update');
        Route::post('stages/destroy', 'StagesController@destroy');

        ######################### Products #################################
        Route::get('products', 'ProductsController@index');
        Route::get('products/show/{id}', 'ProductsController@show');
        Route::get('products/create', 'ProductsController@create')->name('products.create');
        Route::post('products/store', 'ProductsController@store')->name('products.store');
        Route::get('products/edit/{id}', 'ProductsController@edit')->name('products.edit');
        Route::patch('products/update/{id}', 'ProductsController@update')->name('products.update');
        Route::post('products/destroy', 'ProductsController@destroy');



        ######################### Orders #################################
        Route::get('orders', 'OrdersController@index');
        Route::get('orders/show/{sender_id?}', 'OrdersController@show');
        Route::post('orders/send/{sender_id?}', 'OrdersController@send');

        ######################### Pioneers #################################
        Route::get('pioneers', 'PioneersController@index');
        Route::get('pioneers/show/{id}', 'PioneersController@show');
        Route::get('pioneers/create', 'PioneersController@create')->name('pioneers.create');
        Route::post('pioneers/store', 'PioneersController@store')->name('pioneers.store');
        Route::get('pioneers/edit/{id}', 'PioneersController@edit')->name('pioneers.edit');
        Route::patch('pioneers/update/{id}', 'PioneersController@update')->name('pioneers.update');
        Route::delete('pioneers/destroy/{id}', 'PioneersController@destroy')->name('pioneers.destroy');
    });
});
