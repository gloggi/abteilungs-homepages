<?php

use App\Http\Controllers\Admin\PageCrudController;
use App\Models\Page;

return [
    // Change this class if you wish to extend PageCrudController
    'admin_controller_class' => PageCrudController::class,

    // Change this class if you wish to extend the Page model
    'page_model_class'       => Page::class,
];
