<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PageRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\PageManager\app\Http\Controllers\Admin\PageCrudController as BackpackPageCrudController;

/**
 * Class PageCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PageCrudController extends BackpackPageCrudController
{

    public function setupCreateOperation()
    {
        parent::setupCreateOperation();
        $this->crud->setValidation(PageRequest::class);
    }

    public function setupUpdateOperation()
    {
        parent::setupCreateOperation();
        $this->crud->setValidation(PageRequest::class);
    }
}
