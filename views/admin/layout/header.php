<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (! isset($_SESSION['user'])) {
        header('Location: /admin/login');
        exit;
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Proyek</title>

    <link rel="shortcut icon" href="/img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="/img/logo.png" type="image/png">

    <link rel="stylesheet"
        href="/template_admin/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="/template_admin/assets/extensions/choices.js/public/assets/styles/choices.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-pbFz1cAQYz4sVxqV5D8Zyx/+D5z+wec3s+sqXwVxLqS2Cj0Yw5Dfdd1q+o5hVH6UgM0G3jqm+DQ2nRrGnx1pRw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="/template_admin/assets/compiled/css/table-datatable-jquery.css">
    <link rel="stylesheet" href="/template_admin/assets/extensions/rater-js/lib/style.css">
    <link rel="stylesheet" href="/template_admin/assets/compiled/css/app.css">
    <link rel="stylesheet" href="/template_admin/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="/template_admin/assets/compiled/css/iconly.css">

    <link rel="stylesheet" href="/template_admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/template_admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/template_admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <link rel="stylesheet" href="/template_admin/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="/template_admin/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        body.dark .select2-container--default .select2-selection--single {
            background-color: #1e1e2d;
            color: #fff;
            border: 1px solid #444;
        }

        body.dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #fff;
        }

        body.dark .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #aaa;
        }

        body.dark .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #fff transparent transparent transparent;
        }

        body.dark .select2-container--open .select2-dropdown .select2-results__option {
            text-align: left !important;
            padding-left: -500px !important;
        }

        body.dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #444;
            color: #fff;
        }

        body.dark .select2-container--default .select2-results__option {
            text-align: left !important;
            padding-left: -10px !important;
        }

        body.dark .select2-dropdown {
            background-color: #2c2f33;
            color: #fff;
            border-color: #444;
        }

        body.light .user-info-text .username {
            color: #fff important;
        }

        body.light .user-info-text .role {
            color: #fff !important;
        }

        .select2-container .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            padding: 6px 12px;
            font-size: 1rem;
            background-color: #fff;
        }

        .select2-container .select2-selection__arrow {
            display: none;
        }

        .select2-container .select2-selection--single:focus {
            outline: none;
        }

        .select2-container .select2-selection__rendered {
            line-height: 30px;
        }

        #main-navbar {
            background-color: white !important;
            color: #333;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        body.dark #main-navbar {
            background-color: #1e1e2d !important;
            color: #eee;
        }

        body.dark #main-navbar .nav-link {
            color: #ccc;
        }

        body.dark #main-navbar .nav-link.active,
        body.dark #main-navbar .nav-link:hover {
            color: #0d6efd;
        }

        .main-navbar {
            position: relative;
            z-index: 1000;
        }

        .dropdown-menu {
            top: 100% !important;
            left: auto !important;
            right: 0 !important;
        }

        input[readonly] {
            background-color: #e9ecef !important;
            color: #212529 !important;
        }


        @media (max-width: 768px) {
            #main-menu {
                display: none !important;
                flex-direction: column;
                background-color: #fff;
                padding: 10px 0;
                position: absolute;
                top: 60px;
                left: 0;
                right: 0;
                z-index: 1000;
            }

            #main-menu.show {
                display: flex !important;
            }

            body.dark #main-menu {
                background-color: #1e1e2d;
            }

            .main-navbar {
                position: relative;
            }
        }

        .fixed-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1030;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .fixed-header-nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1030;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 767.98px) {
            .fixed-header {
                height: 80px;
            }

            .fixed-header-nav {
                top: 80px;
            }
        }

        .rating-star i {
            font-size: 2rem;
            color: gray;
            cursor: pointer;
            margin: 0 15px;
            transition: color 0.1s;
        }

        .rating-star i.active {
            color: gold;
        }

        @media (min-width: 992px) {
            .rating-star i {
                margin: 0 25px;
            }
        }

        .card.border-danger {
            border: 2px solid #dc3545 !important;
        }

        .card.border-danger.shadow {
            box-shadow: 0 0 10px rgba(220, 53, 69, .6) !important;
        }
    </style>


</head>

<body class="<?php echo(isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark') ? 'theme-dark' : ''; ?>">

    <script src="/template_admin/assets/static/js/initTheme.js"></script>
    <div id="app">
        <div id="sidebar">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a><img src="/img/logo.png" alt="Logo" style="width:80px; height:auto;"></a>
                        </div>
                        <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20"
                                height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                                <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                        opacity=".3"></path>
                                    <g transform="translate(-210 -1)">
                                        <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                        <circle cx="220.5" cy="11.5" r="4"></circle>
                                        <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2">
                                        </path>
                                    </g>
                                </g>
                            </svg>
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input me-0" type="checkbox" id="toggle-dark"
                                    style="cursor: pointer" />
                                <label class="form-check-label"></label>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20"
                                preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                                </path>
                            </svg>
                        </div>
                        <div class="sidebar-toggler x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>

                <div class="sidebar-menu">
                    <ul class="menu">
                        <?php
                    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                    $uri = rtrim($uri, '/');
                    function isActive($route) {
                        global $uri;
                        $cleanRoute = rtrim($route, '/');
                        return $uri === $cleanRoute ? 'active' : '';
                    }
                ?>

                        <li class="sidebar-item <?php echo isActive('/admin/dashboard') ?>">
                            <a href="/admin/dashboard" class="sidebar-link">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-title">Data Master</li>

                        <li class="sidebar-item <?php echo isActive('/admin/klien') ?>">
                            <a href="/admin/klien" class="sidebar-link">
                                <i class="bi bi-person-rolodex"></i>
                                <span>Klien</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?php echo isActive('/admin/pegawai') ?>">
                            <a href="/admin/pegawai" class="sidebar-link">
                                <i class="bi bi-person-badge-fill"></i>
                                <span>Pegawai</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?php echo isActive('/admin/tim') ?>">
                            <a href="/admin/tim" class="sidebar-link">
                                <i class="bi bi-people-fill"></i>
                                <span>Tim</span>
                            </a>
                        </li>

                        <li class="sidebar-title">Manajemen Proyek</li>

                        <li class="sidebar-item <?php echo isActive('/admin/proyek') ?>">
                            <a href="/admin/proyek" class="sidebar-link">
                                <i class="bi bi-kanban"></i>
                                <span>Proyek</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?php echo isActive('/admin/tugas') ?>">
                            <a href="/admin/tugas" class="sidebar-link">
                                <i class="bi bi-list-check"></i>
                                <span>Tugas</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?php echo isActive('/admin/status') ?>">
                            <a href="/admin/status" class="sidebar-link">
                                <i class="bi bi-flag-fill"></i>
                                <span>Status</span>
                            </a>
                        </li>

                        <li class="sidebar-title">Relasi & Pengaturan</li>

                        <li class="sidebar-item <?php echo isActive('/admin/proyek_tim') ?>">
                            <a href="/admin/proyek_tim" class="sidebar-link">
                                <i class="bi bi-diagram-3-fill"></i>
                                <span>Proyek Tim</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?php echo isActive('/admin/proyek_klien') ?>">
                            <a href="/admin/proyek_klien" class="sidebar-link">
                                <i class="bi bi-briefcase-fill"></i>
                                <span>Proyek Klien</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?php echo isActive('/admin/anggota_tim') ?>">
                            <a href="/admin/anggota_tim" class="sidebar-link">
                                <i class="bi bi-person-plus-fill"></i>
                                <span>Anggota Tim</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?php echo isActive('/admin/users') ?>">
                            <a href="/admin/users" class="sidebar-link">
                                <i class="bi bi-shield-lock-fill"></i>
                                <span>Users</span>
                            </a>
                        </li>

                        <li class="sidebar-item mt-3">
                            <a href="#" onclick="logoutConfirm(event)" class="sidebar-link text-danger">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </a>
                            <form id="logout-form" action="/admin/logout" method="POST" style="display: none;"></form>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
        <div id="main" class='layout-navbar navbar-fixed'>
            <header>
                <nav class="navbar navbar-expand navbar-light navbar-top">
                    <div class="container-fluid">
                        <a href="#" class="burger-btn d-block d-lg-none">
                            <i class="bi bi-justify fs-3"></i>
                        </a>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-lg-0">
                            </ul>
                            <div class="dropdown">
                                <a data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-menu d-flex">
                                        <?php
                                    if (session_status() === PHP_SESSION_NONE) {
                                        session_start();
                                    }

                                    require_once __DIR__ . '/../../../app/models/Users.php';

                                    $usernameSession = $_SESSION['user']['username'] ?? null;
                                    $username = '-';
                                    $nama_role = '-';
                                    $fotoPath = '/img/1.jpg';

                                    if ($usernameSession) {
                                        $userModel = new Users();
                                        $user = $userModel->findWithRole($usernameSession);

                                        if ($user) {
                                            $username = htmlspecialchars($user['username']);
                                            $nama_role = htmlspecialchars($user['nama_role'] ?? '-');
                                        }
                                    }
                                    ?>

                                        <div class="user-name text-end me-3">
                                            <h6 class="mb-0 text-gray-600"><?php echo $username; ?></h6>
                                            <p class="mb-0 text-sm text-gray-600"><?php echo $nama_role; ?></p>
                                        </div>

                                        <div class="user-img d-flex align-items-center">
                                            <div class="avatar avatar-md">
                                                <img src="<?php echo $fotoPath; ?>" alt="Foto Profil">
                                            </div>
                                        </div>
                                    </div>

                                </a>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
            <div id="main-content">