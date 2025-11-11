<?php

require_once __DIR__ . '/Controller.php';

class LandingPageController extends Controller
{

    public function index()
    {
        $this->view('landing_page/index');
    }

    public function getArtikelBerita()
    {
        $this->view('landing_page/artikel_berita');
    }

    public function getPublikasiIlmiah()
    {
        $this->view('landing_page/publikasi_ilmiah');
    }

    public function getEventHighlight()
    {
        $this->view('landing_page/event_highlight');
    }
}
