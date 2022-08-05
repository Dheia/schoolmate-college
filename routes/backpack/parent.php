<?php 

	Route::group([
	    'prefix'     => config('backpack.base.route_prefix', 'admin'),
	    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'verify_schoolyear'],
	    // 'namespace'  => 'App\Http\Controllers\Admin',
	], function () { // custom admin routes

		CRUD::resource('parent-user', 'ParentUserCrudController')->with(function() {
			Route::get('parent-user/{id}/portal/{action}', 'ParentUserCrudController@portalAuthorization')->where(['id' => '[0-9]+', 'action' => '^(enable|disable)$']);
			Route::get('parent-user/{id}/verify', 'ParentUserCrudController@verifyAccount')->where(['id' => '[0-9]+']);
	    });

	});

?>