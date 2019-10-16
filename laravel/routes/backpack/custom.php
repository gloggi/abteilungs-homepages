<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('location', 'LocationCrudController');
    Route::crud('user', 'BackpackUserCrudController');
    Route::crud('event', 'EventCrudController');
    Route::crud('group', 'GroupCrudController');
    Route::crud('section', 'SectionCrudController');
    Route::crud('special-event-type', 'SpecialEventTypeCrudController');
    Route::crud('staff-contact', 'StaffContactCrudController');
}); // this should be the absolute last line of this file
