<?php

require_once __DIR__ . '/Controller.php';

class LandingPageController extends Controller
{

    public function index()
    {
        $this->view('landing_page/index');
    }
}
