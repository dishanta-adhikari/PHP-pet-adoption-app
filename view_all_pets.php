<?php include 'includes/header.php'; ?>
<?php
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: logout");
    exit;
}

include 'includes/db.php';

try {
    $stmt = $conn->prepare("SELECT pets.*, ngos.name AS ngo_name FROM pets JOIN ngos ON pets.ngo_id = ngos.id ORDER BY pets.status");
    $stmt->execute();
    $result = $stmt->get_result();
} catch (Exception $e) {
    die("Error fetching pets: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>All Pets</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/view_all_pets.css">
</head>

<body class="bg-light">
    <div class="container mt-4 mt-md-5">
        <h3>All Pets in System</h3>
        <a href="dashboard_admin" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <div class="table-responsive">
            <table class="table table-bordered bg-white align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Species</th>
                        <th>NGO</th>
                        <th>Status</th>
                        <th>Info</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['species']) ?></td>
                            <td><?= htmlspecialchars($row['ngo_name']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td>
                                <button class="btn btn-info btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#petInfoModal"
                                    data-name="<?= htmlspecialchars($row['name']) ?>"
                                    data-species="<?= htmlspecialchars($row['species']) ?>"
                                    data-breed="<?= htmlspecialchars($row['breed']) ?>"
                                    data-age="<?= htmlspecialchars($row['age']) ?>"
                                    data-gender="<?= htmlspecialchars($row['gender']) ?>"
                                    data-description="<?= htmlspecialchars($row['description']) ?>"
                                    data-status="<?= htmlspecialchars($row['status']) ?>"
                                    data-ngo="<?= htmlspecialchars($row['ngo_name']) ?>">
                                    Info
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="petInfoModal" tabindex="-1" aria-labelledby="petInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="petInfoModalLabel">Pet Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> <span id="modalName"></span></p>
                    <p><strong>Species:</strong> <span id="modalSpecies"></span></p>
                    <p><strong>Breed:</strong> <span id="modalBreed"></span></p>
                    <p><strong>Age:</strong> <span id="modalAge"></span></p>
                    <p><strong>Gender:</strong> <span id="modalGender"></span></p>
                    <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                    <p><strong>Description:</strong> <span id="modalDescription"></span></p>
                    <p><strong>NGO:</strong> <span id="modalNGO"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const petModal = document.getElementById('petInfoModal');
        petModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            document.getElementById('modalName').textContent = button.getAttribute('data-name');
            document.getElementById('modalSpecies').textContent = button.getAttribute('data-species');
            document.getElementById('modalBreed').textContent = button.getAttribute('data-breed');
            document.getElementById('modalAge').textContent = button.getAttribute('data-age');
            document.getElementById('modalGender').textContent = button.getAttribute('data-gender');
            document.getElementById('modalDescription').textContent = button.getAttribute('data-description');
            document.getElementById('modalStatus').textContent = button.getAttribute('data-status');
            document.getElementById('modalNGO').textContent = button.getAttribute('data-ngo');
        });
    </script>
    <?php include 'includes/footer.php'; ?>
</body>

</html>
