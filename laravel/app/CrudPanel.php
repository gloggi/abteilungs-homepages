<?php

namespace App;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanel as BackpackCrudPanel;
use Illuminate\Support\Str;

class CrudPanel extends BackpackCrudPanel
{
    public function getAdditionalDatatableConfig() {
        return [
          'lengthChange' => false,
          'info' => false,
        ];
    }

    public function makeLabel($value) {
        return trans(Str::kebab(parent::makeLabel($value)));
    }
}
