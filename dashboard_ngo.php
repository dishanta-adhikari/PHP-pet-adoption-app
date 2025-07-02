<?php include 'includes/header.php'; ?>

<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ngo') {
    header("Location: logout");
    exit;
}

include 'includes/db.php';
$ngo_id = $_SESSION['user_id'];

// Fetch pets with adopter info if adopted
$pets = [];
try {
    $stmt = $conn->prepare("
        SELECT pets.*, 
               adoptions.status AS adoption_status,
               adopters.name AS adopter_name,
               adopters.email AS adopter_email,
               adopters.phone AS adopter_phone,
               adopters.address AS adopter_address
        FROM pets 
        LEFT JOIN adoptions ON pets.id = adoptions.pet_id
        LEFT JOIN adopters ON adoptions.adopter_id = adopters.id
        WHERE pets.ngo_id = ?
        ORDER BY pets.id DESC
    ");
    $stmt->bind_param("i", $ngo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $pets[] = $row;
    }
} catch (Exception $e) {
    die("Error loading pets: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>NGO Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/dashboard_ngo.css">
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
                <a href="manage_requests" class="btn btn-primary me-2 mb-2 mb-md-0">Manage Requests</a>
                <!-- <a href="logout" class="btn btn-danger mb-2 mb-md-0">Logout</a> -->
            </div>
        </div>

        <h4>Add a New Pet</h4>
        <form action="add_pet" method="POST" enctype="multipart/form-data" class="row g-3 mb-4">
            <input type="hidden" name="ngo_id" value="<?= $ngo_id ?>">

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
                <button type="submit" name="add_pet" class="btn btn-success w-100">Add Pet</button>
            </div>
        </form>


        <hr>

        <h4>My Listed Pets</h4>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php if (empty($pets)): ?>
                <p class="text-muted">No pets listed yet.</p>
            <?php else: ?>
                <?php foreach ($pets as $pet): ?>
                    <div class="col">
                        <div class="card h-100">
                            <?php if ($pet['image']): ?>
                                <img src="uploads/<?= htmlspecialchars($pet['image']) ?>" class="card-img-top" alt="Pet Image">
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
    <?php include 'includes/footer.php'; ?>
</body>

</html>