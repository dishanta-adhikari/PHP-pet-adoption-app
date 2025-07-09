<?php

namespace App\Models;

class Ngo
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($values)
    {
        $stmt = $this->conn->prepare("INSERT INTO ngos (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $values['name'], $values['email'], $values['password'], $values['phone'], $values['address']);
        if ($stmt->execute()) {
            $id = $this->conn->insert_id;
            return [
                'id' => $id,
                'name' => $values['name'],
                'email' => $values['email']
            ];
        }
        return false;
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM ngos WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getRequests($ngo_id)
    {
        $stmt = $this->conn->prepare("
        SELECT 
            adoptions.id AS request_id, 
            pets.name AS pet_name, 
            adopters.name AS adopter_name, 
            adoptions.status
        FROM adoptions
        JOIN pets ON adoptions.pet_id = pets.id
        JOIN adopters ON adoptions.adopter_id = adopters.id
        WHERE pets.ngo_id = ?
        ");
        $stmt->bind_param("i", $ngo_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
