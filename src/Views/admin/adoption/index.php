<?php

require_once __DIR__ . "/../../includes/_init.php";

use App\Controllers\AdminController;

$admin = new AdminController($conn);
$admin->verifyAdmin();

$results = $admin->getAllAdoptions();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>All Adoptions | ADMIN</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $appUrl ?>/public/assets/css/view_all_adoptions.css">
</head>

<body class="bg-light">
    <div class="container mt-4 mt-md-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <h3 class="mb-3 mb-md-0">All Adoption Requests</h3>
            <a href="<?= $appUrl ?>/src/Views/admin/dashboard" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered bg-white align-middle">
                <thead class="table-dark">
                    <tr>

                        <th scope="col">Pet</th>
                        <th scope="col">Adopter</th>
                        <th scope="col">Status</th>
                        <th scope="col" style="min-width: 80px;">Info</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                        <tr>

                            <td><?= htmlspecialchars($row['pet_name']) ?></td>
                            <td><?= htmlspecialchars($row['adopter_name']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td>
                                <button
                                    class="btn btn-info btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#infoModal"
                                    data-request='<?= json_encode($row, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>'>
                                    Info
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="infoModalLabel">Adoption Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="fw-bold">Pet Info</h6>
                    <ul class="list-unstyled">
                        <li><strong>Name:</strong> <span id="petName"></span></li>
                        <li><strong>Species:</strong> <span id="petSpecies"></span></li>
                        <li><strong>Breed:</strong> <span id="petBreed"></span></li>
                        <li><strong>Age:</strong> <span id="petAge"></span></li>
                        <li><strong>Gender:</strong> <span id="petGender"></span></li>
                    </ul>
                    <hr>
                    <h6 class="fw-bold">Adopter Info</h6>
                    <ul class="list-unstyled">
                        <li><strong>Name:</strong> <span id="adopterName"></span></li>
                        <li><strong>Email:</strong> <span id="adopterEmail"></span></li>
                        <li><strong>Phone:</strong> <span id="adopterPhone"></span></li>
                        <li><strong>Address:</strong> <span id="adopterAddress"></span></li>
                    </ul>
                    <hr>
                    <p><strong>Status:</strong> <span id="adoptionStatus"></span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Modal Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('infoModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const data = JSON.parse(button.getAttribute('data-request'));

            document.getElementById('petName').textContent = data.pet_name || 'N/A';
            document.getElementById('petSpecies').textContent = data.species || 'N/A';
            document.getElementById('petBreed').textContent = data.breed || 'N/A';
            document.getElementById('petAge').textContent = data.age || 'N/A';
            document.getElementById('petGender').textContent = data.gender || 'N/A';

            document.getElementById('adopterName').textContent = data.adopter_name || 'N/A';
            document.getElementById('adopterEmail').textContent = data.email || 'N/A';
            document.getElementById('adopterPhone').textContent = data.phone || 'N/A';
            document.getElementById('adopterAddress').textContent = data.address || 'N/A';

            document.getElementById('adoptionStatus').textContent = data.status || 'N/A';
        });
    </script>
</body>

</html>