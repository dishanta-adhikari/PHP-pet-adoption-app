<?php

namespace App\Controllers;

use App\Models\Adopter;
use App\Models\Ngo;
use Exception;

class RegisterController
{
    private $Adopter, $Ngo;

    public function __construct($db)
    {
        $this->Adopter = new Adopter($db);
        $this->Ngo = new Ngo($db);
    }

    public function register($values)
    {
        $allowed_roles = ['adopter', 'ngo'];
        $role = isset($_GET['role']) && in_array($_GET['role'], $allowed_roles) ? $_GET['role'] : '';

        if (!$role) {
            $_SESSION['error'] = "Role is not defined.";
            header("Location: " . APP_URL . "/src/Views/auth/register");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
            try {
                $name = trim($values['name']);
                $email = trim($values['email']);
                $password_plain = $values['password'];
                $phone = trim($values['phone']);
                $address = trim($values['address']);

                if (!$name || !$email || !$password_plain || !$phone || !$address) {
                    throw new Exception("All fields are required.");
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Invalid email format.");
                }

                // Check if email already exists
                $existingUser = ($role === 'adopter')
                    ? $this->Adopter->getUserByEmail($email)
                    : $this->Ngo->getUserByEmail($email);

                if ($existingUser) {
                    throw new Exception("An account with this email already exists.");
                }

                $password_hashed = password_hash($password_plain, PASSWORD_BCRYPT);

                $userData = [
                    'name'     => $name,
                    'email'    => $email,
                    'password' => $password_hashed,
                    'phone'    => $phone,
                    'address'  => $address
                ];

                if ($role === 'adopter') {
                    $user = $this->Adopter->create($userData);
                } else {
                    $user = $this->Ngo->create($userData);
                }

                if (!$user || !isset($user['id'])) {
                    throw new Exception("Registration failed. Please try again.");
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'] ?? $name;
                $_SESSION['role'] = $role;

                session_regenerate_id(true);

                header("Location: " . APP_URL . "/src/Views/$role/dashboard");
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header("Location: " . APP_URL . "/src/Views/auth/register?role=$role");
                exit;
            }
        }
    }
}
