<?php
require_once __DIR__ . "/../includes/_init.php";

if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    header("Location:" . $appUrl . "/src/Views/" . $_SESSION['role'] . "/dashboard");
}

use App\Controllers\LoginController;

$loginController = new LoginController($conn);
$loginController->login($_POST);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title><?= ucfirst($_GET['role']) ?> Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            background: url('<?= APP_URL; ?>/public/assets/images/login-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            padding-bottom: 80px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(40, 35, 22, 0.75);
            z-index: -1;
        }

        .login-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.8rem 2rem rgba(255, 193, 7, 0.4);
            background-color: rgba(255, 255, 224, 0.95);
            color: #3a2f00;
        }

        .login-logo {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
            filter: drop-shadow(0 0 3px #ffeb3b);
        }

        .btn-primary {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #3a2f00;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #ffca28;
            border-color: #ffca28;
            color: #2e2a00;
        }

        .form-control:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.4);
        }

        label {
            color: #665c00;
            font-weight: 600;
        }

        .login-footer {
            font-size: 0.9rem;
            color: #a78f00;
        }

        .login-footer a {
            color: #ffca28;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .login-footer a:hover {
            color: #ffc107;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="bg-overlay"></div>

    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="row w-100 justify-content-center">
            <div class="col-11 col-sm-10 col-md-8 col-lg-5">
                <div class="card login-card">
                    <div class="card-body p-4 p-md-5 text-center">
                        <img src="<?= APP_URL; ?>/public/assets/images/login.png" alt="Logo" class="login-logo mx-auto d-block" />

                        <h3 class="mb-4"><?= ucfirst($_GET['role']) ?> Login</h3>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                <?= htmlspecialchars($_SESSION['error']) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="form-floating mb-3">
                                <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="name@example.com" required />
                                <label for="floatingEmail">Email address</label>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required />
                                <label for="floatingPassword">Password</label>
                            </div>

                            <button type="submit" name="login" class="btn btn-primary w-100 py-2">Login</button>
                        </form>

                        <div class="text-center mt-4 login-footer">
                            <p>Don't have an account? <a href="register?role=<?= $_GET['role'] ?>">Register here</a></p>
                            <p><a href="<?= APP_URL ?>">← Back to Home</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>