<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

use App\Http\Controllers\Admin\BackpackUserCrudController;
use App\Http\Controllers\Admin\EventCrudController;
use App\Http\Controllers\Admin\GroupCrudController;
use App\Http\Controllers\Admin\LocationCrudController;
use App\Http\Controllers\Admin\SectionCrudController;
use App\Http\Controllers\Admin\SpecialEventTypeCrudController;
use App\Http\Controllers\Admin\StaffContactCrudController;
use App\Http\Controllers\SettingsCrudController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => '',
], function () { // custom admin routes
    Route::crud('settings', SettingsCrudController::class);
    Route::crud('location', LocationCrudController::class);
    Route::crud('user', BackpackUserCrudController::class);
    Route::crud('event', EventCrudController::class);
    Route::crud('group', GroupCrudController::class);
    Route::crud('section', SectionCrudController::class);
    Route::crud('special-event-type', SpecialEventTypeCrudController::class);
    Route::crud('staff-contact', StaffContactCrudController::class);
}); // this should be the absolute last line of this file
