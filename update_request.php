<?php
include 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['action'])) {
    try {
        $request_id = $_POST['request_id'];
        $action = $_POST['action'];

        // Update request status
        $stmt = $conn->prepare("UPDATE adoptions SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $action, $request_id);
        if (!$stmt->execute()) throw new Exception("Update failed");

        // If approved, mark the pet as 'Adopted'
        if ($action === "Approved") {
            $getPetStmt = $conn->prepare("SELECT pet_id FROM adoptions WHERE id = ?");
            $getPetStmt->bind_param("i", $request_id);
            $getPetStmt->execute();
            $result = $getPetStmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $pet_id = $row['pet_id'];
                $updatePet = $conn->prepare("UPDATE pets SET status = 'Adopted' WHERE id = ?");
                $updatePet->bind_param("i", $pet_id);
                $updatePet->execute();
            }
        }

        header("Location: manage_requests");
        exit;
    } catch (Exception $e) {
        echo "<div class='alert alert-danger m-3'>Error: " . $e->getMessage() . "</div>";
    }
} else {
    header("Location: manage_requests");
    exit;
}
