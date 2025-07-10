<?php

namespace App\Controllers;

use App\Models\Admin;
use Exception;

class AdminController
{
    private $Admin;

    public  function __construct($db)
    {
        $this->Admin = new Admin($db);
    }

    public function getAllCount($table)
    {
        $allowedTables = ['adopters', 'adoptions', 'pets', 'ngos'];

        // Validate table name against whitelist
        if (!in_array($table, $allowedTables, true)) {
            $_SESSION['error'] = "Invalid table name!";
            header("Location: " . APP_URL . "/src/Views/admin/dashboard");
            exit;
        }

        try {
            return $this->Admin->getCount($table);
        } catch (Exception $e) {
            $_SESSION['error'] = "Error fetching count: " . $e->getMessage();
            header("Location: " . APP_URL . "/src/Views/admin/dashboard");
            exit;
        }
    }

    public function getAll()
    {
        return $this->Admin->getAllUsers();
    }

    public  function getAllAdoptions()
    {
        return $this->Admin->getAllAdoptions();
    }

    public function verifyAdmin()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: " . APP_URL);
            exit;
        }
    }

    public function create($values)
    {
        $name = trim($values['name']);
        $email = trim($values['email']);
        $password = $values['password'];
        $confirm_password = $values['confirm_password'];

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

            $user = $this->Admin->getUserByEmail($email);
            if ($user) { // Check if email already exists
                throw new Exception("Email already registered.");
            }

            $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash password

            $values = [ //$values['name'], $values['$email'], $values['$password_hashed']
                'name' => $name,
                'email' => $email,
                'password_hashed' => $hashed_password
            ];

            $result = $this->Admin->create($values); // create new admin

            if (!$result) {
                throw new Exception("Falied to create new Admin!");
            }

            $_SESSION['success'] = "Admin Created Successfully!";
            header("Location: " . APP_URL . "/src/Views/admin/dashboard");
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: " . APP_URL . "/src/Views/admin/create");
            exit;
        }
    }
}
