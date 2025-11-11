<?php

require_once __DIR__ . '/Controller.php';

class TestKoneksiController extends Controller
{

    public function test_koneksi()
    {
        $this->view('test_koneksi');
    }

}
