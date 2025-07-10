<?php
require_once __DIR__ . "/../includes/_init.php";

use App\Controllers\NgoController;
use App\Controllers\PetController;

$ngo = new NgoController($conn);
$ngo->verifyNgo();

$petContrlloer = new PetController($conn);
$results = $petContrlloer->getWithAdopter($_SESSION['user_id']); // Fetch adoption requests for this NGO's pets

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_pet'])) {
    $create = $petContrlloer->create($_POST); // create a new pet
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard | NGO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $appUrl ?>/public/assets/css/dashboard_ngo.css">
    <style>
        /* Optional: make cards equal height */
        .card {
            height: 100%;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container mt-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
            <h2 class="mb-3 mb-md-0">Welcome, <?= htmlspecialchars($_SESSION['name']) ?> (NGO)</h2>
            <div>
                <a href="<?= $appUrl ?>/src/Views/ngo/adoption/request" class="btn btn-primary me-2 mb-2 mb-md-0">Approve Requests</a>
                <!-- <a href="logout" class="btn btn-danger mb-2 mb-md-0">Logout</a> -->
            </div>
        </div>

        <!-- display messages -->
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
            <?php unset($_SESSION['success']); ?>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show container mt-3" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <h4>New Pet</h4>
        <form action="" method="POST" enctype="multipart/form-data" class="row g-3 mb-4">
            <input type="hidden" name="ngo_id" value="<?= $_SESSION['user_id'] ?>">
            <div class="col-md-6">
                <input type="text" name="name" class="form-control" placeholder="Pet Name" required>
            </div>
            <div class="col-md-6">
                <input type="text" name="species" class="form-control" placeholder="Species (e.g., Dog, Cat)" required>
            </div>
            <div class="col-md-6">
                <input type="text" name="breed" class="form-control" placeholder="Breed">
            </div>
            <div class="col-md-6">
                <input type="number" name="age" class="form-control" placeholder="Age (years)" min="0" required>
            </div>
            <div class="col-md-6">
                <select name="gender" class="form-select" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="city" class="form-control" placeholder="City" required>
            </div>
            <div class="col-md-6">
                <input type="file" name="image" class="form-control" accept="image/*" required>
            </div>
            <div class="col-12">
                <textarea name="description" class="form-control" placeholder="Short Description"></textarea>
            </div>
            <div class="col-12">
                <button type="submit" name="add_pet" class="btn btn-success w-100">Create</button>
            </div>
        </form>


        <hr>

        <h4>My Listed Pets</h4>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php if (empty($results)): ?>
                <p class="text-muted">No pets listed yet.</p>
            <?php else: ?>
                <?php foreach ($results as $pet): ?>
                    <div class="col">
                        <div class="card h-100">
                            <?php if ($pet['image']): ?>
                                <img src="<?= $appUrl ?>/public/assets/uploads/<?= htmlspecialchars($pet['image']) ?>" class="card-img-top" alt="Pet Image">
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($pet['name']) ?> (<?= htmlspecialchars($pet['species']) ?>)</h5>
                                <p class="card-text flex-grow-1"><?= htmlspecialchars($pet['description']) ?></p>
                                <p>
                                    Status: <strong><?= htmlspecialchars($pet['status']) ?></strong>
                                    <?php if ($pet['status'] !== 'Available' && $pet['adopter_name']): ?>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-info ms-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="#adopterInfoModal"
                                            data-name="<?= htmlspecialchars($pet['adopter_name']) ?>"
                                            data-email="<?= htmlspecialchars($pet['adopter_email']) ?>"
                                            data-phone="<?= htmlspecialchars($pet['adopter_phone']) ?>"
                                            data-address="<?= htmlspecialchars($pet['adopter_address']) ?>"
                                            title="View Adopter Details">
                                            Info
                                        </button>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Adopter Info Modal -->
    <div class="modal fade" id="adopterInfoModal" tabindex="-1" aria-labelledby="adopterInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adopterInfoModalLabel">Adopter Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> <span id="adopterName"></span></p>
                    <p><strong>Email:</strong> <span id="adopterEmail"></span></p>
                    <p><strong>Phone:</strong> <span id="adopterPhone"></span></p>
                    <p><strong>Address:</strong> <span id="adopterAddress"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Fill modal with adopter info on show
        var adopterInfoModal = document.getElementById('adopterInfoModal');
        adopterInfoModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;

            var name = button.getAttribute('data-name') || 'N/A';
            var email = button.getAttribute('data-email') || 'N/A';
            var phone = button.getAttribute('data-phone') || 'N/A';
            var address = button.getAttribute('data-address') || 'N/A';

            adopterInfoModal.querySelector('#adopterName').textContent = name;
            adopterInfoModal.querySelector('#adopterEmail').textContent = email;
            adopterInfoModal.querySelector('#adopterPhone').textContent = phone;
            adopterInfoModal.querySelector('#adopterAddress').textContent = address;
        });
    </script>
</body>

</html>