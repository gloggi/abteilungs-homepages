<?php

namespace App\Http\Controllers\Admin\Operations;

use App\CrudPanel;
use App\Settings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Prologue\Alerts\Facades\Alert;

/**
 * Trait UpdateSettingsOperation
 * @package App\Http\Controllers\Admin\Operations
 * @property CrudPanel $crud
 */
trait UpdateSettingsOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupUpdateSettingsRoutes($segment, $routeName, $controller)
    {
        Route::get($segment, [
          'as'        => $routeName.'.editSettings',
          'uses'      => $controller.'@editSettings',
          'operation' => 'updateSettings',
        ]);

        Route::put($segment, [
          'as'        => $routeName.'.updateSettings',
          'uses'      => $controller.'@updateSettings',
          'operation' => 'updateSettings',
        ]);
    }

    /**
     * Add the default operation settings, buttons, etc that this operation needs.
     */
    protected function setupUpdateSettingsDefaults()
    {
        $this->crud->allowAccess('update');

        $this->crud->operation('updateSettings', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });
    }

    /**
     * Show the view for editing the settings.
     *
     * @return Response
     */
    public function editSettings()
    {
        $this->crud->hasAccessOrFail('update');
        /** @var Settings $settings */
        $settings = app('settings');
        $this->crud->entry = $settings;

        $this->data['crud'] = $this->crud;
        $this->data['entry'] = $this->crud->entry;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = trans($this->crud->getTitle() ?? 'crud.update-settings', [ 'entity' => $this->crud->entity_name ]);

        return view('crud::updateSettings', $this->data);
    }

    /**
     * Update the settings in the database.
     *
     * @return RedirectResponse
     */
    public function updateSettings()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // update the settings in the db
        $savedSettings = app('settings')->update($this->crud->getStrippedSaveRequest());
        $this->data['entry'] = $this->crud->entry = $savedSettings;

        // show a success message
        Alert::success(trans('crud.update_settings_success'))->flash();

        return Redirect::back();
    }

    public function setupFromSettingsConfig() {
        app('settings')->each(function ($field, $key) {
            $autoset = [
              'name' => $key,
              'label' => $this->crud->makeLabel($key),
              'values' => [],
              'attributes' => [],
              'autoset' => true,
            ];

            if (!isset($this->crud->fields()[$key])) {
                $this->crud->addField(array_merge($autoset, $field));
            }
        });
    }
}
