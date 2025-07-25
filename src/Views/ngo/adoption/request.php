<?php

require_once __DIR__ . "/../../includes/_init.php";

use App\Controllers\NgoController;
use App\Controllers\AdoptionController;

$ngo = new NgoController($conn);
$ngo->verifyNgo();

$ngo_id = $_SESSION['user_id'];
$adoptionController = new AdoptionController($conn);
$results = $adoptionController->getRequest($ngo_id); // Fetch adoption requests for this NGO's pets

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adoptionController->UpdateRequest($_POST);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Adoption Requests | NGO</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="./css/manage_requests.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-4 mt-md-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-3">
            <h2 class="mb-0">Adoption Requests - <?= htmlspecialchars($_SESSION['name']) ?> (NGO)</h2>
            <a href="<?= $appUrl ?>/src/Views/ngo/dashboard" class="btn btn-secondary">Back to Dashboard</a>
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

        <?php if (empty($results)): ?>
            <p>No adoption requests at this time.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered bg-white align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Pet Name</th>
                            <th>Adopter Name</th>
                            <th>Status</th>
                            <th>Action</th>
                            <th>Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $req): ?>
                            <tr>
                                <td><?= htmlspecialchars($req['pet_name']) ?></td>
                                <td><?= htmlspecialchars($req['adopter_name']) ?></td>
                                <td>
                                    <?php
                                    $status = strtolower($req['status']);
                                    if ($status === 'pending') {
                                        echo '<span class="badge bg-warning text-dark">Pending</span>';
                                    } elseif ($status === 'approved') {
                                        echo '<span class="badge bg-success">Approved</span>';
                                    } elseif ($status === 'rejected') {
                                        echo '<span class="badge bg-danger">Rejected</span>';
                                    } else {
                                        echo '<span class="badge bg-secondary">' . htmlspecialchars($req['status']) . '</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if (strtolower($req['status']) === 'pending'): ?>
                                        <form action="" method="POST" class="d-inline-block mb-1 mb-md-0 me-1">
                                            <input type="hidden" name="id" value="<?= $req['request_id'] ?>">
                                            <input type="hidden" name="adoption_id" value="<?= $req['request_id'] ?>">
                                            <input type="hidden" name="adoption_id" value="<?= $req['request_id'] ?>">
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="btn btn-success btn-sm w-100 w-md-auto">Approve</button>
                                        </form>
                                        <form action="" method="POST" class="d-inline-block">
                                            <input type="hidden" name="id" value="<?= $req['request_id'] ?>">
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="btn btn-danger btn-sm w-100 w-md-auto">Reject</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted"><?= htmlspecialchars($req['status']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button
                                        class="btn btn-info btn-sm w-100 w-md-auto info-btn"
                                        data-id="<?= htmlspecialchars($req['request_id']) ?>"
                                        data-pet="<?= htmlspecialchars($req['pet_name']) ?>"
                                        data-adopter="<?= htmlspecialchars($req['adopter_name']) ?>"
                                        data-status="<?= htmlspecialchars($req['status']) ?>">
                                        Info
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="requestInfoModal" tabindex="-1" aria-labelledby="requestInfoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestInfoLabel">Adoption Request Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Request ID:</strong> <span id="modalRequestId"></span></p>
                    <p><strong>Pet Name:</strong> <span id="modalPetName"></span></p>
                    <p><strong>Adopter Name:</strong> <span id="modalAdopterName"></span></p>
                    <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.info-btn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const pet = button.getAttribute('data-pet');
                const adopter = button.getAttribute('data-adopter');
                const status = button.getAttribute('data-status');

                document.getElementById('modalRequestId').textContent = id;
                document.getElementById('modalPetName').textContent = pet;
                document.getElementById('modalAdopterName').textContent = adopter;
                document.getElementById('modalStatus').textContent = status;

                const modal = new bootstrap.Modal(document.getElementById('requestInfoModal'));
                modal.show();
            });
        });
    </script>
</body>

</html>