<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "pet_adoption_system";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset("utf8mb4");  // Set charset for safety
} catch (mysqli_sql_exception $e) {
    // Handle connection error gracefully
    die("Connection failed: " . htmlspecialchars($e->getMessage()));
}
