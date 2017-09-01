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
});
