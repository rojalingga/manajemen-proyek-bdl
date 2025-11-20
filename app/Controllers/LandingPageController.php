<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/TimKreatif.php';
require_once __DIR__ . '/../models/ProfilWeb.php';
require_once __DIR__ . '/../models/PartnerKolaborator.php';

class LandingPageController extends Controller
{

    public function index()
    {
        $this->view('landing_page/index');
    }

    public function getProfileLaboratorium()
    {
        $model       = new ProfilWeb();
        $data['profil_web'] = $model->getForLandingPage();

        $model       = new TimKreatif();
        $data['tim'] = $model->getForLandingPage();
        
        $modelPartner    = new PartnerKolaborator();
        $data['partner'] = $modelPartner->getForLandingPage();

        $this->view('landing_page/profile_laboratorium/index', $data);
    }

    public function getTimKreatifDetail($id)
    {
        $timKreatifModel = new TimKreatif();
        $data            = $timKreatifModel->findById($id);

        $this->view('landing_page/profile_laboratorium/detail_tim_kreatif', $data);
    }

    public function getPartnerKolaboratorDetail($id)
    {
        $partnerKolaboratorModel = new PartnerKolaborator();
        $data                    = $partnerKolaboratorModel->findById($id);

        $this->view('landing_page/profile_laboratorium/detail_partner_kolaborator', $data);
    }


    public function getProyekDigital()
    {
        $this->view('landing_page/proyek_digital/index');
    }

    public function getDetailProyekDigital($id)
    {
        $this->view('landing_page/proyek_digital/detail_proyek');
    }

    public function getPublikasiKegiatan()
    {
        $this->view('landing_page/publikasi_kegiatan/index');
    }

}
