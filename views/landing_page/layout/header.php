<!DOCTYPE html>
<html class="wide wow-animation" lang="en">

<head>
    <title>LAB MMT</title>
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport"
        content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <link href="/img/logo.svg" rel="icon" >
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

 <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .rd-megamenu-title {
            font-size: 15px;
        }
        
        /* Search Bar */
        .search-container {
            position: relative;
            margin-left: 15px;
        }

        .search-input {
            padding: 8px 35px 8px 12px;
            border: 1px solid #ddd;
            border-radius: 20px;
            width: 200px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #2a93e0;
            width: 250px;
        }

        .search-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
        }
    </style>
</head>

<body>
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
                                <div class="rd-navbar-brand">
                                    <a class="brand" href="/" onclick="return goToHome()">
                                        <img src="/img/logo.svg" alt="LAB MMT"/>
                                    </a>
                                </div>
                            </div>
                            <div class="rd-navbar-aside-right rd-navbar-collapse">
                                <!-- SEARCH BAR -->
                                <div class="search-container">
                                    <input type="text" class="search-input" placeholder="Cari proyek atau berita..." id="globalSearch">
                                    <button class="search-btn" onclick="performSearch()">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
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

                                  <li class="rd-nav-item <?php echo isActive('/') ?>">
                                    <a class="rd-nav-link" href="/">Beranda</a>
                                  </li>

                                  <li class="rd-nav-item <?php echo isActive('/profile-lab') ?>">
                                    <a class="rd-nav-link" href="/profile-lab">Profile</a>                                   
                                  </li>

                                  <li class="rd-nav-item <?php echo isActive('/proyek-digital') ?>">
                                    <a class="rd-nav-link" href="/proyek-digital">Proyek</a>
                                  </li>

                                  <li class="rd-nav-item <?php echo isActive('/publikasi-kegiatan') ?>">
                                    <a class="rd-nav-link" href="/publikasi-kegiatan">Berita</a>
                                  </li>
                                  
                                  <li class="rd-nav-item <?php echo isActive('/galeri-multimedia') ?>">
                                    <a class="rd-nav-link" href="/galeri-multimedia">Galeri</a>
                                  </li>

                                 <li class="rd-nav-item <?php echo isActive('/kontak') ?>">
                                    <a class="rd-nav-link" href="/kontak">Kontak</a>
                                  </li> 
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </header>

<script>
    // Fungsi untuk kembali ke beranda saat logo diklik
    function goToHome() {
        window.location.href = '/';
        return false;
    }

    // Fungsi pencarian
    function performSearch() {
        const searchTerm = document.getElementById('globalSearch').value.trim();
        if (searchTerm) {
            // Redirect ke halaman proyek dengan parameter pencarian
            window.location.href = '/proyek-digital?search=' + encodeURIComponent(searchTerm);
        }
    }

    // Event listener untuk enter pada search input
    document.getElementById('globalSearch').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
</script>



