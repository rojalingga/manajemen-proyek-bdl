<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Dashboard.php';

class DashboardController extends Controller
{
    private $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new Dashboard();
    }

    public function index()
    {
        $rekapStatus = $this->dashboardModel->getRekapStatus();

        $statistikTim = $this->dashboardModel->getStatistikTim();

        $this->view('admin/dashboard/index', [
            'rekapStatus' => $rekapStatus,
            'statistikTim' => $statistikTim
        ]);
    }

    public function refreshData()
    {
        $this->dashboardModel->refreshStatistik();
        
        header('Location: /admin/dashboard'); 
        exit;
    }
}