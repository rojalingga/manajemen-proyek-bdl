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
        $this->view('landing_page/publikasi_kegiatan/artikel_berita/index');
    }

    public function getPublikasiIlmiah()
    {
        $this->view('landing_page/publikasi_kegiatan/publikasi_ilmiah/index');
    }

    public function getEventHighlight()
    {
        $this->view('landing_page/publikasi_kegiatan/event_highlight/index');
    }

    public function getProyekDigital()
    {
        $this->view('landing_page/proyek_digital/index');
    }

    public function getDetailProyekDigital($id)
    {
        $this->view('landing_page/proyek_digital/detail');
    }
}
