<?php 

    Route::group([
        'prefix'     => config('backpack.base.route_prefix', 'admin'),
        'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:System', 'first_time_login'],
        'namespace'  => 'Admin',
    ], function () {
        
        /*
        |--------------------------------------------------------------------------
        | CLASS
        |--------------------------------------------------------------------------
        */
        CRUD::resource('block-section', 'BlockSectionCrudController')->with(function () {
            Route::get('block-section/section', 'StudentSectionAssignmentCrudController@getSection');
        });

    });

?>