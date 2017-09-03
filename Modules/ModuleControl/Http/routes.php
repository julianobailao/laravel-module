<?php

Route::group([
    'middleware' => 'api',
    'namespace' => 'Modules\ModuleControl\Http\Controllers',
], function() {
    // Display a listing of modules.
    Route::get('/modules', 'ModuleControlController@index');

    // Actions resource controller.
    Route::resource('/modules/actions', 'ActionController', ['except' => ['create', 'edit']]);

    // Display a specified module data by path.
    Route::get('/modules/{path}', 'ModuleControlController@show');

    // User Group resource controller.
    Route::resource('/user-groups', 'UserGroupController', ['except' => ['create', 'edit']]);

    // User resource controller.
    Route::resource('/users', 'UserController', ['except' => ['create', 'edit']]);
});
