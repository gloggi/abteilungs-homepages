<?php

namespace App\Http\Controllers\Auth;

use Backpack\CRUD\app\Http\Controllers\Auth\LoginController as BackpackLoginController;

class LoginController extends BackpackLoginController
{
    protected $redirectTo;
    protected $redirectAfterLogout;

    public function __construct() {
        $this->redirectTo = backpack_url('event');
        $this->redirectAfterLogout = '/';
    }
}
