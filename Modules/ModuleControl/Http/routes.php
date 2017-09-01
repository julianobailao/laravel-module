<?php

Route::group([
    'middleware' => 'api',
    'namespace' => 'Modules\ModuleControl\Http\Controllers',
], function() {
    // Display a listing of modules.
    Route::get('/modules', 'ModuleControlController@index');

    // Display a listing of actions.
    Route::get('/modules/actions', 'ActionController@index');

    // Display a specified module data by path.
    Route::get('/modules/{path}', 'ModuleControlController@show');
});
