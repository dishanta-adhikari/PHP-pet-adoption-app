<?php
require_once __DIR__ . "/../includes/_init.php";


use App\Controllers\PetController;

$petController = new PetController($conn);
$results = $petController->getBySearch();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Search Results for "<?= htmlspecialchars($_GET['query']) ?>"</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fffbea;
        }

        .card {
            border: 1px solid #ffe58f;
        }

        .card-title {
            color: #d48806;
        }

        .btn-primary {
            background-color: #fadb14;
            border-color: #fadb14;
            color: #000;
        }

        .btn-primary:hover {
            background-color: #facc14;
            border-color: #facc14;
        }

        .btn-secondary {
            background-color: #fff566;
            border-color: #fff566;
            color: #000;
        }

        .btn-secondary:hover {
            background-color: #ffe58f;
            border-color: #ffe58f;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <h2 class="mb-4 text-center text-warning">Search Results for "<?= htmlspecialchars($_GET['query']) ?>"</h2>

        <?php
        foreach ($results as $row) {
        ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?= $appUrl . "/public/assets/uploads/" . htmlspecialchars($row['image']) ?>" class="card-img-top img-fluid" alt="<?= htmlspecialchars($row['name']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['breed']) ?>)</h5>
                        <p class="card-text">
                            Age: <?= htmlspecialchars($row['age']) ?><br>
                            City: <?= htmlspecialchars($row['city']) ?><br>
                            NGO: <?= htmlspecialchars($row['ngo_name']) ?>
                        </p>
                        <p class="card-text small text-muted"><?= htmlspecialchars($row['description']) ?></p>
                        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#loginPromptModal">Request Adoption</button>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="text-center mt-4">
            <a href="<?= $appUrl ?>" class="btn btn-secondary">← Back to Home</a>
        </div>
    </div>

    <!-- Login/Register Prompt Modal -->
    <div class="modal fade" id="loginPromptModal" tabindex="-1" aria-labelledby="loginPromptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-warning">
                <div class="modal-header bg-warning bg-opacity-75">
                    <h5 class="modal-title text-dark" id="loginPromptModalLabel">Please Login or Register</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>You must be logged in to request adoption.</p>
                    <a href="<?= $appUrl ?>/src/Views/auth/login?role=adopter" class="btn btn-primary m-2 w-75">Login as Adopter</a>
                    <a href="<?= $appUrl ?>/src/Views/auth/register?role=adopter" class="btn btn-success m-2 w-75">Register as Adopter</a>
                    <button type="button" class="btn btn-secondary m-2 w-75" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>