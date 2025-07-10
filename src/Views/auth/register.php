<?php

require_once __DIR__ . "/../includes/_init.php";

use App\Controllers\LoginController;
use App\Controllers\RegisterController;

$loginController = new LoginController($conn);
$loginController->verifyUserLoggedIn();

$registerContrller = new RegisterController($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registerContrller->register($_POST);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Create Account | <?= ucfirst($_GET['role']) ?? '' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            background: url('<?= $appUrl ?>/public/assets/images/register-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            padding-bottom: 80px;
            /* added bottom spacing */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(40, 35, 22, 0.75);
            /* dark warm yellow overlay */
            z-index: -1;
        }

        .register-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.8rem 2rem rgba(255, 193, 7, 0.4);
            /* warm yellow glow */
            background-color: rgba(255, 255, 224, 0.95);
            /* soft light yellow background */
            color: #3a2f00;
            /* warm brown text */
        }

        h3 {
            color: #665c00;
            font-weight: 700;
        }

        .form-control:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.4);
            background-color: #fffbe6;
            color: #3a2f00;
        }

        label {
            color: #665c00;
            font-weight: 600;
        }

        /* Yellow themed buttons */
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

        .btn-success {
            background-color: #8bc34a;
            border-color: #8bc34a;
            color: #2e3a00;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-success:hover,
        .btn-success:focus {
            background-color: #9ccc65;
            border-color: #9ccc65;
            color: #1c2800;
        }

        /* Footer links */
        .text-center a {
            color: #ffca28;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .text-center a:hover {
            color: #ffc107;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="bg-overlay"></div>

    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="row w-100 justify-content-center">
            <div class="col-11 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="card register-card">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="text-center mb-4">Create Account <?= $_GET['role'] ?? '' ?></h3>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                <?= htmlspecialchars($_SESSION['error']) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="form-floating mb-3">
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    id="nameInput"
                                    placeholder="<?= ($_GET['role'] ?? '')  === 'ngo' ? 'NGO Name' : 'Full Name' ?>"
                                    required />
                                <label for="nameInput"><?= ($_GET['role'] ?? '') === 'ngo' ? 'NGO Name' : 'Full Name' ?></label>
                            </div>

                            <div class="form-floating mb-3">
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    id="emailInput"
                                    placeholder="Email"
                                    required />
                                <label for="emailInput">Email</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    id="passwordInput"
                                    placeholder="Password"
                                    required />
                                <label for="passwordInput">Password</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control"
                                    id="phoneInput"
                                    placeholder="Phone"
                                    required />
                                <label for="phoneInput">Phone</label>
                            </div>

                            <div class="form-floating mb-4">
                                <textarea
                                    name="address"
                                    class="form-control"
                                    placeholder="Address"
                                    id="addressInput"
                                    style="height: 100px;"
                                    required></textarea>
                                <label for="addressInput">Address</label>
                            </div>

                            <button
                                type="submit"
                                name="register"
                                class="btn <?= ($_GET['role'] ?? '') === 'ngo' ? 'btn-success' : 'btn-primary' ?? 'adopter' ?> w-100 py-2">
                                Register
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p>Already have an account? <a href="login?role=<?= $_GET['role'] ?? '' ?>">Login here</a></p>
                            <p><a href="<?= $appUrl ?>">← Back to Home</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>