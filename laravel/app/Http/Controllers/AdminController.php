<?php

namespace App\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\AdminController as BackpackAdminController;

class AdminController extends BackpackAdminController {
    /**
     * Redirect to the initial page.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        return redirect(backpack_url('event'));
    }
}
