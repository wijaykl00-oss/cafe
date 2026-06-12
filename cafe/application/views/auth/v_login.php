<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cafe POS - Login</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 50%, #1a3a9c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Nunito', sans-serif;
        }
        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }
        .login-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #4e73df, #224abe);
            padding: 40px 30px 30px;
            text-align: center;
            color: white;
        }
        .login-header .cafe-icon {
            width: 70px;
            height: 70px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 28px;
        }
        .login-header h4 {
            font-weight: 800;
            margin: 0;
            font-size: 1.5rem;
        }
        .login-header p {
            margin: 5px 0 0;
            opacity: 0.85;
            font-size: 0.875rem;
        }
        .login-body {
            padding: 35px 30px;
            background: white;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1.5px solid #e0e0e0;
            font-size: 0.9rem;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 3px rgba(78,115,223,0.15);
        }
        .input-group-text {
            background: #f8f9fc;
            border: 1.5px solid #e0e0e0;
            border-right: none;
            border-radius: 8px 0 0 8px;
            color: #4e73df;
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }
        .btn-login {
            background: linear-gradient(135deg, #4e73df, #224abe);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(78,115,223,0.4);
        }
        .alert {
            border-radius: 8px;
            font-size: 0.875rem;
        }
        .label-text {
            font-weight: 700;
            font-size: 0.8rem;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .login-footer {
            text-align: center;
            padding: 15px;
            background: #f8f9fc;
            font-size: 0.8rem;
            color: #999;
        }
        .toggle-pw {
            cursor: pointer;
            background: #f8f9fc;
            border: 1.5px solid #e0e0e0;
            border-left: none;
            border-radius: 0 8px 8px 0;
            padding: 0 15px;
            color: #aaa;
        }
        .toggle-pw:hover { color: #4e73df; }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card card">

            <!-- Header -->
            <div class="login-header">
                <div class="cafe-icon">
                    <i class="fas fa-coffee"></i>
                </div>
                <h4>Cafe POS</h4>
                <p>Sistem Kasir Cafe</p>
            </div>

            <!-- Body -->
            <div class="login-body">
                <h6 class="text-center text-gray-600 mb-4" style="font-weight:600;">Masuk ke akun Anda</h6>

                <!-- Alert Error -->
                <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger d-flex align-items-center mb-3">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
                <?php endif; ?>

                <form action="<?php echo base_url('auth/proses_login'); ?>" method="POST">

                    <!-- Username -->
                    <div class="form-group mb-3">
                        <label class="label-text">Username</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-user fa-sm"></i>
                                </span>
                            </div>
                            <input type="text" name="username" class="form-control"
                                   placeholder="Masukkan username"
                                   value="<?php echo set_value('username'); ?>"
                                   autofocus required>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="form-group mb-4">
                        <label class="label-text">Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-lock fa-sm"></i>
                                </span>
                            </div>
                            <input type="password" name="password" id="inputPassword"
                                   class="form-control" placeholder="Masukkan password" required>
                            <div class="input-group-append">
                                <button type="button" class="toggle-pw" id="togglePw" tabindex="-1">
                                    <i class="fas fa-eye fa-sm" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary btn-login btn-block text-white">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </button>
                </form>

                <!-- Info Akun Default -->
                <div class="mt-4 p-3 rounded" style="background:#f0f4ff; border:1px dashed #4e73df;">
                    <p class="mb-1 small font-weight-bold text-primary"><i class="fas fa-info-circle mr-1"></i>Akun Default:</p>
                    <p class="mb-0 small text-muted">Admin &nbsp;→ username: <code>admin</code> &nbsp;| password: <code>password</code></p>
                </div>
            </div>

            <!-- Footer -->
            <div class="login-footer">
                &copy; <?php echo date('Y'); ?> Cafe POS &mdash; All rights reserved
            </div>

        </div>
    </div>

    <script src="<?php echo base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
    <script>
        // Toggle show/hide password
        $('#togglePw').on('click', function() {
            var input = $('#inputPassword');
            var icon  = $('#eyeIcon');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    </script>
</body>
</html>