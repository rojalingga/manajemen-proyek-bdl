<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Users.php';

class DashboardController extends Controller
{

    public function index()
    {
        $this->view('admin/dashboard/index');
    }
}
