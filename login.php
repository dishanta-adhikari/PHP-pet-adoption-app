<?php include 'includes/header.php'; ?>

<?php
ob_start();
include 'includes/db.php';

$allowed_roles = ['adopter', 'ngo', 'admin'];
$role = isset($_GET['role']) && in_array($_GET['role'], $allowed_roles) ? $_GET['role'] : null;

if (!$role) {
    echo "<div class='alert alert-danger m-5'>Invalid role selected. Please go back and choose a role.</div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title><?= ucfirst($role) ?> Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        /* Background & overlay */
        body {
            background: url('assets/images/login-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            padding-bottom: 80px;
            /* bottom spacing */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(40, 35, 22, 0.75);
            /* dark with subtle yellow tint */
            z-index: -1;
        }

        /* Card styling */
        .login-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.8rem 2rem rgba(255, 193, 7, 0.4);
            /* warm yellow glow */
            background-color: rgba(255, 255, 224, 0.95);
            /* light yellow background */
            color: #3a2f00;
            /* dark yellow/brown text */
        }

        /* Logo styling */
        .login-logo {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
            filter: drop-shadow(0 0 3px #ffeb3b);
        }

        /* Yellow themed button */
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

        /* Form input focus highlight */
        .form-control:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.4);
        }

        /* Labels */
        label {
            color: #665c00;
            font-weight: 600;
        }

        /* Footer text */
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
                        <!-- Optional Logo -->
                        <img src="assets/images/login.png" alt="Logo" class="login-logo mx-auto d-block" />

                        <h3 class="mb-4"><?= ucfirst($role) ?> Login</h3>

                        <form method="POST">
                            <div class="form-floating mb-3">
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    id="floatingEmail"
                                    placeholder="name@example.com"
                                    required />
                                <label for="floatingEmail">Email address</label>
                            </div>

                            <div class="form-floating mb-4">
                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    id="floatingPassword"
                                    placeholder="Password"
                                    required />
                                <label for="floatingPassword">Password</label>
                            </div>

                            <button type="submit" name="login" class="btn btn-primary w-100 py-2">
                                Login
                            </button>
                        </form>

                        <?php
                        if (isset($_POST['login'])) {
                            try {
                                $email = trim($_POST['email']);
                                $password = trim($_POST['password']);

                                if ($role === 'adopter') {
                                    $stmt = $conn->prepare("SELECT * FROM adopters WHERE email = ?");
                                    $dashboard = "dashboard_adopter";
                                } elseif ($role === 'ngo') {
                                    $stmt = $conn->prepare("SELECT * FROM ngos WHERE email = ?");
                                    $dashboard = "dashboard_ngo";
                                } else { // admin
                                    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
                                    $dashboard = "dashboard_admin";
                                }

                                if (!$stmt) throw new Exception("Database error: " . $conn->error);

                                $stmt->bind_param("s", $email);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows === 1) {
                                    $user = $result->fetch_assoc();
                                    if (password_verify($password, $user['password'])) {
                                        // Set session
                                        if ($role === 'admin') {
                                            $_SESSION['admin_id']   = $user['id'];
                                            $_SESSION['admin_name'] = $user['name'];
                                        } else {
                                            $_SESSION['user_id']   = $user['id'];
                                            $_SESSION['name']      = $user['name'];
                                        }

                                        $_SESSION['role'] = $role;
                                        header("Location: $dashboard");
                                        exit;
                                    } else {
                                        throw new Exception("Incorrect password.");
                                    }
                                } else {
                                    throw new Exception("User not found.");
                                }
                            } catch (Exception $e) {
                                echo "
                                <div class='alert alert-danger alert-dismissible fade show mt-4' role='alert'>
                                    " . htmlspecialchars($e->getMessage()) . "
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>";
                            }
                        }
                        ?>

                        <div class="text-center mt-4 login-footer">
                            <p>
                                Don't have an account?
                                <a href="register?role=<?= $role ?>">Register here</a>
                            </p>
                            <p><a href="index">← Back to Home</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'includes/footer.php'; ?>
</body>

</html>

<?php
ob_end_flush();
?>