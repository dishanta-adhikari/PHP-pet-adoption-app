<?php

namespace App\Models;

class Admin
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($values)
    {
        $stmt = $this->conn->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $values['name'], $values['email'], $values['password_hashed']);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getCount($table)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM $table");
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_assoc();
        return $rows['count'];
    }

    public function getAllUsers()
    {
        $stmt = $this->conn->prepare("SELECT id, name, email, 'adopter' AS role 
        FROM adopters
        UNION
        SELECT id, name, email, 'ngo' AS role FROM ngos
        ORDER BY role, name");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllAdoptions()
    {
        $stmt = $this->conn->prepare("
        SELECT a.id, a.status, 
               p.name AS pet_name, p.species, p.breed, p.age, p.gender,
               ad.name AS adopter_name, ad.email, ad.phone, ad.address
        FROM adoptions a
        JOIN pets p ON a.pet_id = p.id
        JOIN adopters ad ON a.adopter_id = ad.id
        ORDER BY a.status DESC
        ");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
