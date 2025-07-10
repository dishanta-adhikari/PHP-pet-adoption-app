<?php

require_once __DIR__ . "/../includes/_init.php";

use App\Controllers\AdminController;

$admin = new AdminController($conn);
$admin->verifyAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin->create($_POST);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Create Admin | ADMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container text-center mt-5" style="max-width: 500px;">
        <h2 class="mb-4">Create Admin | ADMIN</h2>
        <!-- display messages -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show container mt-3" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" class="form-control mb-3" required>
            <input type="email" name="email" placeholder="Email" class="form-control mb-3" required>
            <input type="password" name="password" placeholder="Password" class="form-control mb-3" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control mb-3" required>
            <button type="submit" name="register" class="btn btn-primary w-100">Create +</button>
        </form>
        <p><a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="<?= APP_URL ?>/src/Views/admin/dashboard">← Back to Home</a></p>
    </div>
</body>

</html>