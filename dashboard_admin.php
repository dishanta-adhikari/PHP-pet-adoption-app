<?php include 'includes/header.php'; ?>

<?php
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: logout");
    exit;
}

include 'includes/db.php';

try {
    // Total pets
    $totalPets = $conn->query("SELECT COUNT(*) as count FROM pets")->fetch_assoc()['count'];

    // Total NGOs
    $totalNGOs = $conn->query("SELECT COUNT(*) as count FROM ngos ")->fetch_assoc()['count'];

    // Total Adopters
    $totalAdopters = $conn->query("SELECT COUNT(*) as count FROM adopters")->fetch_assoc()['count'];

    // Total Adoptions
    $totalAdoptions = $conn->query("SELECT COUNT(*) as count FROM adoptions WHERE status = 'Approved'")->fetch_assoc()['count'];
} catch (Exception $e) {
    die("Error fetching admin stats: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Dashboard</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/dashboard_admin.css">
</head>

<body class="bg-light">
    <div class="container mt-4 mt-md-5">
        <h2 class="mb-4 text-center text-md-start">Admin Dashboard</h2>
        <!-- <a href="logout" class="btn btn-danger">Logout</a> -->

        <hr>

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
            <a href="view_all_users" class="btn btn-outline-primary flex-fill flex-md-grow-0">View All Users</a>
            <a href="view_all_pets" class="btn btn-outline-secondary flex-fill flex-md-grow-0">View All Pets</a>
            <a href="view_all_adoptions" class="btn btn-outline-success flex-fill flex-md-grow-0">View All Adoptions</a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>

</html>
