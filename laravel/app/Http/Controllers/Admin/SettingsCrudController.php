<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\Operations\UpdateSettingsOperation;
use App\Models\Setting;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class SettingsCrudController extends CrudController {
    use UpdateSettingsOperation;

    public function setup() {
        $this->crud->setModel(Setting::class);
        $this->crud->setEntityNameStrings('setting', 'settings');
        $this->crud->setRoute(backpack_url('settings'));
    }

    protected function setupUpdateSettingsOperation() {
        $this->setupFromSettingsConfig();
    }

}
