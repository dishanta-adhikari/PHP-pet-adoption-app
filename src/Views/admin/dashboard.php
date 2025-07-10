<?php

require_once __DIR__ . "/../includes/_init.php";

use App\Controllers\AdminController;

$admin = new AdminController($conn);
$admin->verifyAdmin();

$totalPets = $admin->getAllCount('pets'); // Total pets
$totalNGOs = $admin->getAllCount('ngos'); // Total NGOs
$totalAdopters = $admin->getAllCount('adopters'); // Total Adopters
$totalAdoptions = $admin->getAllCount('adoptions'); // Total Adoptions

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard | ADMIN</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $appUrl ?>/public/assets/css/dashboard_admin.css">
</head>

<body class="bg-light">
    <div class="container mt-4 mt-md-5">
        <h2 class="mb-4 text-center text-md-start">Dashboard | ADMIN</h2>
        <?php if (isset($_SESSION['success'])): ?>
            <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
                <symbol id="check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                </symbol>
            </svg>

            <div class="alert alert-success d-flex align-items-center alert-dismissible fade show container mt-3" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                    <use xlink:href="#check-circle-fill" />
                </svg>
                <div>
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php unset($_SESSION['success']);
        endif; ?>

        <div class="row text-center text-md-start g-3">
            <div class="col-6 col-md-3">
                <div class="card text-white bg-primary h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title">Total Pets</h5>
                        <p class="card-text fs-3 fs-md-4 fw-bold mb-0"><?= $totalPets ?></p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-white bg-success h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title">Total NGOs</h5>
                        <p class="card-text fs-3 fs-md-4 fw-bold mb-0"><?= $totalNGOs ?></p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-white bg-warning h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title">Total Adopters</h5>
                        <p class="card-text fs-3 fs-md-4 fw-bold mb-0"><?= $totalAdopters ?></p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-white bg-info h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title">Total Adoptions</h5>
                        <p class="card-text fs-3 fs-md-4 fw-bold mb-0"><?= $totalAdoptions ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row gap-2 gap-md-3 mt-4 justify-content-center justify-content-md-start">
            <a href="<?= $appUrl ?>/src/Views/admin/user/index" class="btn btn-outline-primary flex-fill flex-md-grow-0">View All Users</a>
            <a href="<?= $appUrl ?>/src/Views/admin/pet/index" class="btn btn-outline-secondary flex-fill flex-md-grow-0">View All Pets</a>
            <a href="<?= $appUrl ?>/src/Views/admin/adoption/index" class="btn btn-outline-success flex-fill flex-md-grow-0">View All Adoptions</a>
            <a href="<?= $appUrl ?>/src/Views/admin/create" class="btn btn-outline-success flex-fill flex-md-grow-0">Create New Admin</a>
        </div>
    </div>
</body>

</html>