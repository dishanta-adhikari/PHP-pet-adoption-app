<?php
include 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_pet'])) {
    try {
        $ngo_id = $_POST['ngo_id'];
        $name = $_POST['name'];
        $species = $_POST['species'];
        $breed = $_POST['breed'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $city = $_POST['city'];
        $description = $_POST['description'];
        $imageName = null;

        // Image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid() . "." . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $imageName);
        } else {
            throw new Exception("Image upload failed.");
        }

        $stmt = $conn->prepare("
            INSERT INTO pets 
                (ngo_id, name, species, breed, age, gender, description, city, image)
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);

        $stmt->bind_param("isssissss", $ngo_id, $name, $species, $breed, $age, $gender, $description, $city, $imageName);

        if (!$stmt->execute()) throw new Exception("Execution failed: " . $stmt->error);

        header("Location: dashboard_ngo");
        exit;
    } catch (Exception $e) {
        echo "<div class='alert alert-danger m-3'>Error: " . $e->getMessage() . "</div>";
    }
} else {
    header("Location: dashboard_ngo");
    exit;
}
