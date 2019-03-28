<?php

Route::group([
    'module' => 'System',
    'namespace' => 'App\Modules\Basic\Navigation\Controllers',
    'prefix' => 'navigation',
    'middleware' => 'web'
], function() {

    /**
     * Front
     */

    // ---

    /**
     * Back
     */

    Route::group([
        'prefix' => 'management'
    ], function() {


        /**
         * Navigations
         */

        Route::get('/index', [
            'as' => 'get.navigation.index',
            'uses' => 'Back\NavigationController@getIndex',
            'middleware' => ['permission:navigation-index']
        ]);


        Route::get('/edit/{param?}', [
            'as' => 'get.navigation.edit',
            'uses' => 'Back\NavigationController@getEdit',
            'middleware' => ['permission:navigation-edit']
        ]);
        Route::post('/edit/{param?}', [
            'as' => 'post.navigation.edit',
            'uses' => 'Back\NavigationController@postEdit',
            'middleware' => ['permission:navigation-edit']
        ]);


        Route::get('/add', [
            'as' => 'get.navigation.add',
            'uses' => 'Back\NavigationController@getAdd',
            'middleware' => ['permission:navigation-add']
        ]);
        Route::post('/add', [
            'as' => 'post.navigation.add',
            'uses' => 'Back\NavigationController@postAdd',
            'middleware' => ['permission:navigation-add']
        ]);


        Route::get('/delete/{param?}/{type?}', [
            'as' => 'get.navigation.delete',
            'uses' => 'Back\NavigationController@getDelete',
            'middleware' => ['permission:navigation-delete']
        ]);


        Route::get('/restore/{param?}', [
            'as' => 'get.navigation.restore',
            'uses' => 'Back\NavigationController@getRestore',
            'middleware' => ['permission:navigation-restore']
        ]);

    });

});	