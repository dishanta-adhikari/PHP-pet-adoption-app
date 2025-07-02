<?php include 'includes/header.php'; ?>
<?php
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: logout");
    exit;
}

include 'includes/db.php';

try {
    $stmt = $conn->prepare("
        SELECT id, name, email, 'adopter' AS role FROM adopters
        UNION
        SELECT id, name, email, 'ngo' AS role FROM ngos
        ORDER BY role, name
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    die("Error fetching users: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>All Users</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dropdown-toggle span {
            text-transform: capitalize;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
            <h3>All Registered Users</h3>
            <a href="dashboard_admin" class="btn btn-secondary mt-2 mt-md-0">Back to Dashboard</a>
        </div>

        <!-- Dropdown Filter -->
        <div class="mb-3 d-flex justify-content-end">
            <div class="dropdown">
                <button class="btn btn-warning dropdown-toggle" type="button" id="roleFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Show: <span id="selectedRole">All</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="roleFilterDropdown">
                    <li><a class="dropdown-item role-filter" href="#" data-role="all">All</a></li>
                    <li><a class="dropdown-item role-filter" href="#" data-role="ngo">NGO</a></li>
                    <li><a class="dropdown-item role-filter" href="#" data-role="adopter">Adopter</a></li>
                </ul>
            </div>
        </div>

        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover bg-white">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col">Info</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr data-role="<?= htmlspecialchars($user['role']) ?>">
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-info btn-sm info-btn"
                                    data-id="<?= htmlspecialchars($user['id']) ?>"
                                    data-name="<?= htmlspecialchars($user['name']) ?>"
                                    data-email="<?= htmlspecialchars($user['email']) ?>"
                                    data-role="<?= htmlspecialchars($user['role']) ?>">
                                    Info
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- User Info Modal -->
    <div class="modal fade" id="userInfoModal" tabindex="-1" aria-labelledby="userInfoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userInfoLabel">User Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>ID:</strong> <span id="modalUserId"></span></p>
                    <p><strong>Name:</strong> <span id="modalUserName"></span></p>
                    <p><strong>Email:</strong> <span id="modalUserEmail"></span></p>
                    <p><strong>Role:</strong> <span id="modalUserRole"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Info Modal
        document.querySelectorAll('.info-btn').forEach(button => {
            button.addEventListener('click', () => {
                document.getElementById('modalUserId').textContent = button.dataset.id;
                document.getElementById('modalUserName').textContent = button.dataset.name;
                document.getElementById('modalUserEmail').textContent = button.dataset.email;
                document.getElementById('modalUserRole').textContent = button.dataset.role;

                new bootstrap.Modal(document.getElementById('userInfoModal')).show();
            });
        });

        // Role Filter Dropdown
        document.querySelectorAll('.role-filter').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const selectedRole = this.getAttribute('data-role');
                document.getElementById('selectedRole').textContent = selectedRole.charAt(0).toUpperCase() + selectedRole.slice(1);

                const rows = document.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const role = row.getAttribute('data-role');
                    row.style.display = (selectedRole === 'all' || role === selectedRole) ? '' : 'none';
                });
            });
        });
    </script>

    <?php include 'includes/footer.php'; ?>
</body>

</html>