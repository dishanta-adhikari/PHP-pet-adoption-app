<?php
require_once __DIR__ . "/../includes/_init.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'adopter') {
    header("Location: " . $appUrl);
    exit;
}

use App\Controllers\PetController;
use App\Controllers\AdopterController;
use App\Controllers\AdoptionController;

$adopter_id = $_SESSION['user_id'];

// Handle adoption request POST inside the dashboard
$adoptionController = new AdoptionController($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_adoption'])) {
    $adoptionController->create($_POST);
}

// Fetch all pets and existing requests
$petController = new PetController($conn);
$pets = $petController->getAvailablePets();

$adopterController = new AdopterController($conn);
$requests = $adopterController->getRequest($adopter_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Adopter Dashboard</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= $appUrl; ?>/public/assets/css/dashboard_adopter.css" />
    <script>
        function toggleRequests() {
            const reqSection = document.getElementById('myRequestsSection');
            reqSection.style.display = (reqSection.style.display === 'none' || reqSection.style.display === '') ? 'block' : 'none';
        }
    </script>
</head>

<body class="bg-light">
    <div class="container mt-4 mt-md-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-3">
            <h2 class="mb-0">Welcome, <?= htmlspecialchars($_SESSION['name']) ?> (Adopter)</h2>
            <!-- <a href="logout" class="btn btn-danger">Logout</a> -->
            <button class="btn btn-info" onclick="toggleRequests()">My Requests</button>
        </div>
        <hr />

        <div id="myRequestsSection" style="display:none;">
            <h4>Your Adoption Requests</h4>
            <?php if (empty($requests)): ?>
                <p>You have not made any adoption requests yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Pet Name</th>
                                <th>Species</th>
                                <th>Status</th>
                                <th class="text-center">Info</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $req): ?>
                                <tr>
                                    <td><?= htmlspecialchars($req['pet_name']) ?></td>
                                    <td><?= htmlspecialchars($req['species']) ?></td>
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
                                    <td class="text-center">
                                        <button
                                            type="button"
                                            class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ngoInfoModal"
                                            data-name="<?= htmlspecialchars($req['ngo_name'] ?? 'N/A') ?>"
                                            data-email="<?= htmlspecialchars($req['ngo_email'] ?? 'N/A') ?>"
                                            data-phone="<?= htmlspecialchars($req['ngo_phone'] ?? 'N/A') ?>"
                                            data-address="<?= htmlspecialchars($req['ngo_address'] ?? 'N/A') ?>"
                                            title="View NGO Details">
                                            Info
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            <hr />
        </div>

        <h4>Available Pets for Adoption</h4>

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


        <div class="row">
            <?php if (empty($pets)): ?>
                <p>No pets available at the moment.</p>
            <?php else: ?>
                <?php foreach ($pets as $pet): ?>
                    <div class="col-12 col-sm-6 col-md-4 d-flex">
                        <div class="card mb-3 flex-fill">
                            <?php if ($pet['image']): ?>
                                <img src="<?= $appUrl ?>/public/assets/uploads/<?= htmlspecialchars($pet['image']) ?>"
                                    class="card-img-top img-fluid"
                                    style="height: 200px; object-fit: cover;"
                                    alt="<?= htmlspecialchars($pet['name']) ?>">

                            <?php endif; ?>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($pet['name']) ?> (<?= htmlspecialchars($pet['species']) ?>)</h5>
                                <p class="card-text flex-grow-1"><?= htmlspecialchars($pet['description']) ?></p>
                                <p class="mb-2">NGO: <?= htmlspecialchars($pet['ngo_name']) ?></p>
                                <form action="" method="POST" class="mt-auto">
                                    <input type="hidden" name="pet_id" value="<?= $pet['id'] ?>">
                                    <input type="hidden" name="adopter_id" value="<?= $adopter_id ?>">
                                    <button type="submit" name="request_adoption" class="btn btn-primary w-100">Request Adoption</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- NGO Info Modal -->
    <div class="modal fade" id="ngoInfoModal" tabindex="-1" aria-labelledby="ngoInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ngoInfoModalLabel">NGO Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> <span id="ngoName"></span></p>
                    <p><strong>Email:</strong> <span id="ngoEmail"></span></p>
                    <p><strong>Phone:</strong> <span id="ngoPhone"></span></p>
                    <p><strong>Address:</strong> <span id="ngoAddress"></span></p>
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
        // Fill NGO modal with details on show
        var ngoInfoModal = document.getElementById('ngoInfoModal');
        ngoInfoModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;

            var name = button.getAttribute('data-name') || 'N/A';
            var email = button.getAttribute('data-email') || 'N/A';
            var phone = button.getAttribute('data-phone') || 'N/A';
            var address = button.getAttribute('data-address') || 'N/A';

            ngoInfoModal.querySelector('#ngoName').textContent = name;
            ngoInfoModal.querySelector('#ngoEmail').textContent = email;
            ngoInfoModal.querySelector('#ngoPhone').textContent = phone;
            ngoInfoModal.querySelector('#ngoAddress').textContent = address;
        });
    </script>
</body>

</html>