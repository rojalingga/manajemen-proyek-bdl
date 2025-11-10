<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>LAB MMT</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="/img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="/img/logo.png" type="image/png">
    <link rel="stylesheet" href="/template_admin/assets/compiled/css/app.css">
    <link rel="stylesheet" href="/template_admin/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="/template_admin/assets/compiled/css/iconly.css">
    <link rel="stylesheet" href="/template_admin/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">


    <style>
        .login-icon {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 80px;
            height: auto;
        }

        body {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .overlay::before {
            content: "";
            display: block;
            position: absolute;
            z-index: -1;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background: #4f5053;
            background: -webkit-linear-gradient(bottom, #4f5053, #4f5053bb);
            background: -o-linear-gradient(bottom, #4f5053, #4f5053bb);
            background: -moz-linear-gradient(bottom, #4f5053, #4f5053bb);
            background: linear-gradient(bottom, #4f5053, #4f5053bb);
            opacity: 0.9;
        }
    </style>
</head>

<body class="d-flex overflow-hidden overlay align-items-center justify-content-center min-vh-100"
    style="background-image: url('https://jti.polinema.ac.id/wp-content/uploads/2025/08/gedung-sipil.jpg');">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <img src="/img/logo.png" alt="Logo" class="login-icon mb-3">
                        <h4 class="text-center mb-4">LAB MMT</h4>

                        <form id="formLogin">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Masukkan username">
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Masukkan password">
                            </div>

                            <div class="d-grid">
                                <button type="submit" type="submit" id="submitBtn"
                                    class="btn btn-primary rounded-pill">
                                    <span class="spinner-border spinner-border-sm mx-1 d-none" role="status"
                                        aria-hidden="true"></span>
                                    <span class="button-text">Login</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/template/plugins/jquery/jquery.min.js"></script>
    <!-- JS -->
    <script src="/template/assets/static/js/components/dark.js"></script>
    <script src="/template/assets/compiled/js/app.js"></script>
    <script src="/template/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="/template/plugins/toastr/toastr.min.js"></script>

</body>

</html>
