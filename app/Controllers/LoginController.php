<?php

require_once __DIR__ . '/Controller.php';

class LoginController extends Controller
{

    public function getLogin()
    {
        $this->view('admin/login');
    }
}
