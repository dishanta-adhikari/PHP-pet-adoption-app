<?php
include 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_adoption'])) {
    try {
        $pet_id = $_POST['pet_id'];
        $adopter_id = $_POST['adopter_id'];

        // Check if request already exists
        $checkStmt = $conn->prepare("SELECT * FROM adoptions WHERE pet_id = ? AND adopter_id = ?");
        $checkStmt->bind_param("ii", $pet_id, $adopter_id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            throw new Exception("You have already requested to adopt this pet.");
        }

        // Insert new request
        $stmt = $conn->prepare("INSERT INTO adoptions (pet_id, adopter_id, status) VALUES (?, ?, 'Pending')");
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
        $stmt->bind_param("ii", $pet_id, $adopter_id);
        if (!$stmt->execute()) throw new Exception("Execution failed: " . $stmt->error);

        header("Location: dashboard_adopter?success=1");
        exit;
    } catch (Exception $e) {
        echo "<div class='alert alert-danger m-3'>Error: " . $e->getMessage() . "</div>";
    }
} else {
    header("Location: dashboard_adopter.php");
    exit;
}
