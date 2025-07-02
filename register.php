<?php
include 'includes/db.php';
include 'includes/header.php';

$allowed_roles = ['adopter', 'ngo'];
$role = isset($_GET['role']) && in_array($_GET['role'], $allowed_roles) ? $_GET['role'] : null;

if (!$role) {
    echo "<div class='alert alert-danger m-5'>Invalid role. Please go back and select Adopter or NGO.</div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Register as <?= ucfirst($role) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            background: url('assets/images/register-bg.jpg') no-repeat center center fixed;
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
                        <h3 class="text-center mb-4">Register as <?= ucfirst($role) ?></h3>

                        <form method="POST">
                            <div class="form-floating mb-3">
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    id="nameInput"
                                    placeholder="<?= $role === 'ngo' ? 'NGO Name' : 'Full Name' ?>"
                                    required />
                                <label for="nameInput"><?= $role === 'ngo' ? 'NGO Name' : 'Full Name' ?></label>
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
                                class="btn <?= $role === 'ngo' ? 'btn-success' : 'btn-primary' ?> w-100 py-2">
                                Register
                            </button>
                        </form>

                        <?php
                        if (isset($_POST['register'])) {
                            $name = $_POST['name'];
                            $email = $_POST['email'];
                            $password_plain = $_POST['password'];
                            $password_hashed = password_hash($password_plain, PASSWORD_BCRYPT);
                            $phone = $_POST['phone'];
                            $address = $_POST['address'];

                            try {
                                if ($role === 'adopter') {
                                    $stmt = $conn->prepare("INSERT INTO adopters (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
                                } else {
                                    $stmt = $conn->prepare("INSERT INTO ngos (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
                                }

                                if (!$stmt) {
                                    throw new Exception("Prepare failed: " . $conn->error);
                                }

                                $stmt->bind_param("sssss", $name, $email, $password_hashed, $phone, $address);

                                if ($stmt->execute()) {
                                    $user_id = $stmt->insert_id;
                                    $_SESSION['user_id'] = $user_id;
                                    $_SESSION['name'] = $name;
                                    $_SESSION['role'] = $role;

                                    echo "<div class='alert alert-success mt-4'>Registration successful! Redirecting...</div>";
                                    echo "<script>
                                        setTimeout(function() {
                                            window.location.href = 'dashboard_" . $role . "';
                                        }, 2000);
                                    </script>";
                                } else {
                                    throw new Exception("Execute failed: " . $stmt->error);
                                }

                                $stmt->close();
                            } catch (Exception $e) {
                                echo "<div class='alert alert-danger mt-3'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
                            }
                        }
                        ?>

                        <div class="text-center mt-4">
                            <p>Already have an account? <a href="login?role=<?= $role ?>">Login here</a></p>
                            <p><a href="index">← Back to Home</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'includes/footer.php'; ?>
</body>

</html>