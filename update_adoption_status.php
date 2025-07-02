<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ngo') {
    header("Location: logout");
    exit;
}

include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adoption_id = intval($_POST['id']);
    $action = $_POST['action'];
    $ngo_id = $_SESSION['user_id'];

    if (!in_array($action, ['approve', 'reject'])) {
        die("Invalid action.");
    }

    // Check if this adoption belongs to a pet under this NGO
    $checkStmt = $conn->prepare("
        SELECT pets.id AS pet_id FROM adoptions
        JOIN pets ON adoptions.pet_id = pets.id
        WHERE adoptions.id = ? AND pets.ngo_id = ?
    ");
    $checkStmt->bind_param("ii", $adoption_id, $ngo_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        die("Unauthorized request.");
    }

    $petRow = $result->fetch_assoc();
    $pet_id = $petRow['pet_id'];

    // Update status in adoptions table
    $status = ($action === 'approve') ? 'Approved' : 'Rejected';
    $stmt = $conn->prepare("UPDATE adoptions SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $adoption_id);
    $stmt->execute();

    // If approved, update pet status
    if ($status === 'Approved') {
        $stmt2 = $conn->prepare("UPDATE pets SET status = 'Adopted' WHERE id = ?");
        $stmt2->bind_param("i", $pet_id);
        $stmt2->execute();
    }

    header("Location: manage_requests?msg=Request " . $status);
    exit;
} else {
    header("Location: manage_requests");
    exit;
}
