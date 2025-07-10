<?php

namespace App\Controllers;

use App\Models\Adopter;
use App\Models\Ngo;
use App\Models\Admin;
use Exception;


class LoginController
{
    private $Adopter, $Ngo, $Admin;

    public function __construct($db)
    {
        $this->Adopter = new Adopter($db);
        $this->Ngo = new Ngo($db);
        $this->Admin = new Admin($db);
    }

    public function login($values)
    {
        $allowed_roles = ['adopter', 'ngo', 'admin'];
        $role = isset($_GET['role']) && in_array($_GET['role'], $allowed_roles) ? $_GET['role'] : '';

        if (!$role) {
            $_SESSION['error'] = "Role is not defined.";
            header("Location: " . APP_URL . "/src/Views/auth/login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
            try {
                $email = trim($values['email']);
                $password = trim($values['password']);

                if (empty($email) || empty($password)) {
                    throw new Exception("Required fields are blank.");
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Invalid email format.");
                }

                if ($role == 'adopter') {
                    $user = $this->Adopter->getUserByEmail($email);
                    $dashboard = APP_URL . "/src/Views/adopter/dashboard";
                } elseif ($role == 'ngo') {
                    $user = $this->Ngo->getUserByEmail($email);
                    $dashboard = APP_URL . "/src/Views/ngo/dashboard";
                } elseif ($role == 'admin') {
                    $user = $this->Admin->getUserByEmail($email);
                    $dashboard = APP_URL . "/src/Views/admin/dashboard";
                } else {
                    throw new Exception("Invalid role.");
                }

                if (!$user) {
                    throw new Exception("User not found.");
                }

                if (!password_verify($password, $user['password'])) {
                    throw new Exception("Incorrect password.");
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $role;

                session_regenerate_id(true);

                header("Location: $dashboard");
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header("Location: " . APP_URL . "/src/Views/auth/login?role=$role");
                exit;
            }
        }
    }

    public function verifyUserLoggedIn()
    {
        if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
            header("Location:" . APP_URL . "/src/Views/" . $_SESSION['role'] . "/dashboard");
        }
    }
}
