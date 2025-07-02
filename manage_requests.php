<?php include 'includes/header.php'; ?>

<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ngo') {
    header("Location: logout");
    exit;
}

include 'includes/db.php';
$ngo_id = $_SESSION['user_id'];

// Fetch adoption requests for this NGO's pets
$requests = [];
try {
    $stmt = $conn->prepare("
        SELECT 
            adoptions.id AS request_id, 
            pets.name AS pet_name, 
            adopters.name AS adopter_name, 
            adoptions.status
        FROM adoptions
        JOIN pets ON adoptions.pet_id = pets.id
        JOIN adopters ON adoptions.adopter_id = adopters.id
        WHERE pets.ngo_id = ?
    ");
    $stmt->bind_param("i", $ngo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
} catch (Exception $e) {
    die("Error fetching adoption requests: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Adoption Requests</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="./css/manage_requests.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-4 mt-md-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-3">
            <h2 class="mb-0">Adoption Requests - <?= htmlspecialchars($_SESSION['name']) ?> (NGO)</h2>
            <a href="dashboard_ngo" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
        <?php endif; ?>

        <?php if (empty($requests)): ?>
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
                        <?php foreach ($requests as $req): ?>
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
                                        <form action="update_adoption_status" method="POST" class="d-inline-block mb-1 mb-md-0 me-1">
                                            <input type="hidden" name="id" value="<?= $req['request_id'] ?>">
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="btn btn-success btn-sm w-100 w-md-auto">Approve</button>
                                        </form>
                                        <form action="update_adoption_status" method="POST" class="d-inline-block">
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

    <?php include 'includes/footer.php'; ?>
</body>

</html>
