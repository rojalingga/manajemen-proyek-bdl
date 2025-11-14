<!DOCTYPE html>
<html class="wide wow-animation" lang="en">

<head>
    <title>LAB MMT</title>
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport"
        content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <link href="/img/logo.svg" rel="icon">
    <!-- Stylesheets-->
    <link rel="stylesheet" type="text/css"
        href="//fonts.googleapis.com/css?family=Montserrat:400,500,600,700%7CPoppins:400%7CTeko:300,400">
    <link rel="stylesheet" href="/template_landing_page/wondertour/css/bootstrap.css">
<link rel="stylesheet" href="/template_landing_page/wondertour/css/fonts.css">
<link rel="stylesheet" href="/template_landing_page/wondertour/css/style.css">

<!-- DataTables -->
<link rel="stylesheet" href="/template_landing_page/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/template_landing_page/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="/template_landing_page/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">



</head>

<body>
    <style>
        .rd-megamenu-title {
            font-size: 15px;
            /* Membuat teks menjadi tebal */
        }
    </style>
    <div class="preloader">
        <div class="preloader-body">
            <div class="cssload-container">
                <div class="cssload-speeding-wheel"></div>
            </div>
            <p>Loading...</p>
        </div>
    </div>
    <div class="page">
        <!-- Page Header-->
        <header class="section page-header">
            <!-- RD Navbar-->
            <div class="rd-navbar-wrap">
                <nav class="rd-navbar rd-navbar-corporate" data-layout="rd-navbar-fixed"
                    data-sm-layout="rd-navbar-fixed" data-md-layout="rd-navbar-fixed"
                    data-md-device-layout="rd-navbar-fixed" data-lg-layout="rd-navbar-static"
                    data-lg-device-layout="rd-navbar-fixed" data-xl-layout="rd-navbar-static"
                    data-xl-device-layout="rd-navbar-static" data-xxl-layout="rd-navbar-static"
                    data-xxl-device-layout="rd-navbar-static" data-lg-stick-up-offset="46px"
                    data-xl-stick-up-offset="46px" data-xxl-stick-up-offset="106px" data-lg-stick-up="true"
                    data-xl-stick-up="true" data-xxl-stick-up="true">
                    <div class="rd-navbar-collapse-toggle rd-navbar-fixed-element-1"
                        data-rd-navbar-toggle=".rd-navbar-collapse"><span></span></div>
                    <div class="rd-navbar-aside-outer">
                        <div class="rd-navbar-aside">
                            <!-- RD Navbar Panel-->
                            <div class="rd-navbar-panel">
                                <!-- RD Navbar Toggle-->
                                <button class="rd-navbar-toggle"
                                    data-rd-navbar-toggle=".rd-navbar-nav-wrap"><span></span></button>
                                <!-- RD Navbar Brand-->
                                <div class="rd-navbar-brand"><a class="brand"><img
                                            src="/img/logo.svg"/></a></div>
                            </div>
                            <div class="rd-navbar-aside-right rd-navbar-collapse">
                                <ul class="rd-navbar-corporate-contacts">
                                    <li>
                                        <div class="unit unit-spacing-xs">
                                            <div class="unit-left"><span class="icon fa fa-envelope"></span></div>
                                            <div class="unit-body"><a class="link-email">mmt@mail.com</a>
                                            </div>
                                        </div>
                                    </li>

                            </div>
                        </div>
                    </div>


                    <div class="rd-navbar-main-outer">
                        <div class="rd-navbar-main">
                            <div class="rd-navbar-nav-wrap">
                                <!-- RD Navbar Nav-->
                               <ul class="rd-navbar-nav">
                                <?php
                                    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                                    $uri = rtrim($uri, '/');
                                    if ($uri === '') {
                                        $uri = '/';
                                    }

                                    function isActive($route)
                                    {
                                        global $uri;
                                        $cleanRoute = rtrim($route, '/');
                                        if ($cleanRoute === '') {
                                            $cleanRoute = '/';
                                        }

                                        return $uri === $cleanRoute ? 'active' : '';
                                    }
                                ?>

                                  <li class="rd-nav-item                                                          <?php echo isActive('/') ?>">
                                    <a class="rd-nav-link" href="/">Home</a>
                                  </li>

                                  <li class="rd-nav-item">
                                    <a class="rd-nav-link" href="/tentang">Tentang Kami</a>
                                    <ul class="rd-menu rd-navbar-dropdown">
                                      <li class="rd-dropdown-item">
                                        <a class="rd-dropdown-link" href="/tentang/sejarah">Sejarah</a>
                                      </li>
                                      <li class="rd-dropdown-item">
                                        <a class="rd-dropdown-link" href="/tentang/visi-misi">Visi & Misi</a>
                                      </li>
                                      <li class="rd-dropdown-item">
                                        <a class="rd-dropdown-link" href="/tentang/tim">Tim Kami</a>
                                      </li>
                                    </ul>
                                  </li>

                                  <li class="rd-nav-item                                                                      <?php echo isActive('/proyek-digital') ?>">
                                    <a class="rd-nav-link" href="/proyek-digital">Proyek Digital</a>
                                  </li>

                                  <li class="rd-nav-item                                                                       <?php echo isActive('/publikasi-kegiatan') ?>">
                                    <a class="rd-nav-link">Publikasi Kegiatan</a>
                                    <ul class="rd-menu rd-navbar-dropdown">
                                      <li class="rd-dropdown-item                                                              <?php echo isActive('/artikel-berita') ?>">
                                        <a class="rd-dropdown-link" href="/artikel-berita">Artikel dan Berita</a>
                                      </li>
                                      <li class="rd-dropdown-item                                                              <?php echo isActive('/publikasi-ilmiah') ?>">
                                        <a class="rd-dropdown-link" href="/publikasi-ilmiah">Publikasi Ilmiah</a>
                                      </li>
                                      <li class="rd-dropdown-item                                                              <?php echo isActive('/event-highlight') ?>">
                                        <a class="rd-dropdown-link" href="/event-highlight">Event Highlight</a>
                                      </li>
                                    </ul>
                                  </li>
                                  
                                  <li class="rd-nav-item">
                                    <a class="rd-nav-link" href="/produk">Produk</a>
                                    <ul class="rd-menu rd-navbar-megamenu">
                                      <li class="rd-megamenu-item">
                                        <div>
                                          <h6 class="rd-megamenu-title">Elektronik</h6>
                                          <ul class="rd-megamenu-list">
                                            <li class="rd-megamenu-list-item">
                                              <a class="rd-megamenu-list-link" href="/produk/elektronik/hp">Handphone</a>
                                            </li>
                                            <li class="rd-megamenu-list-item">
                                              <a class="rd-megamenu-list-link" href="/produk/elektronik/laptop">Laptop</a>
                                            </li>
                                            <li class="rd-megamenu-list-item">
                                              <a class="rd-megamenu-list-link" href="/produk/elektronik/tv">Televisi</a>
                                            </li>
                                          </ul>
                                        </div>
                                      </li>

                                      <li class="rd-megamenu-item">
                                        <div>
                                          <h6 class="rd-megamenu-title">Pakaian</h6>
                                          <ul class="rd-megamenu-list">
                                            <li class="rd-megamenu-list-item">
                                              <a class="rd-megamenu-list-link" href="/produk/pakaian/pria">Pria</a>
                                            </li>
                                            <li class="rd-megamenu-list-item">
                                              <a class="rd-megamenu-list-link" href="/produk/pakaian/wanita">Wanita</a>
                                            </li>
                                            <li class="rd-megamenu-list-item">
                                              <a class="rd-megamenu-list-link" href="/produk/pakaian/anak">Anak-anak</a>
                                            </li>
                                          </ul>
                                        </div>
                                      </li>

                                      <li class="rd-megamenu-item">
                                        <div>
                                          <h6 class="rd-megamenu-title">Aksesoris</h6>
                                          <ul class="rd-megamenu-list">
                                            <li class="rd-megamenu-list-item">
                                              <a class="rd-megamenu-list-link" href="/produk/aksesoris/jam">Jam Tangan</a>
                                            </li>
                                            <li class="rd-megamenu-list-item">
                                              <a class="rd-megamenu-list-link" href="/produk/aksesoris/tas">Tas</a>
                                            </li>
                                            <li class="rd-megamenu-list-item">
                                              <a class="rd-megamenu-list-link" href="/produk/aksesoris/topi">Topi</a>
                                            </li>
                                          </ul>
                                        </div>
                                      </li>
                                    </ul>
                                  </li>
                                  </ul>

                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </header>



