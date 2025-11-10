<?php

require_once __DIR__ . '/Controller.php';

class UsersController extends Controller
{
    public function index()
    {
        $this->view('admin/users/index');
    }
}
