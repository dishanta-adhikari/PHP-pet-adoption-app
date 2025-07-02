<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5" style="max-width: 500px;">
        <h2>Register Admin</h2>
        <form method="POST" novalidate>
            <input type="text" name="name" placeholder="Full Name" class="form-control mb-3" required>
            <input type="email" name="email" placeholder="Email" class="form-control mb-3" required>
            <input type="password" name="password" placeholder="Password" class="form-control mb-3" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control mb-3" required>
            <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
        </form>

        <?php
        if (isset($_POST['register'])) {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            try {
                if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
                    throw new Exception("All fields are required.");
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Invalid email format.");
                }

                if ($password !== $confirm_password) {
                    throw new Exception("Passwords do not match.");
                }

                // Check if email already exists
                $check_stmt = $conn->prepare("SELECT id FROM admins WHERE email = ?");
                if (!$check_stmt) throw new Exception("Prepare failed: " . $conn->error);

                $check_stmt->bind_param("s", $email);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();

                if ($check_result->num_rows > 0) {
                    throw new Exception("Email already registered.");
                }
                $check_stmt->close();

                // Hash password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Insert new admin
                $stmt = $conn->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
                if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);

                $stmt->bind_param("sss", $name, $email, $hashed_password);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success mt-3'>Admin registered successfully!</div>";
                } else {
                    throw new Exception("Insert failed: " . $stmt->error);
                }

                $stmt->close();
            } catch (Exception $e) {
                echo "<div class='alert alert-danger mt-3'>" . htmlspecialchars($e->getMessage()) . "</div>";
            }
        }
        ?>
    </div>
</body>

</html>