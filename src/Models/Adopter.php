<?php

namespace App\Models;

class Adopter
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($values)
    {
        $stmt = $this->conn->prepare("INSERT INTO adopters (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
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

    // get adopter by email
    public function getUserByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM adopters WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // check for duplicate entries
    public function validateDuplicate($values)
    {
        $stmt = $this->conn->prepare("SELECT id FROM adoptions WHERE pet_id = ? AND adopter_id = ?");
        $stmt->bind_param("ii", $values['pet_id'], $values['adopter_id']);
        $stmt->execute();
        return $stmt->get_result();
    }

    //  create a request
    public function createRequest($values)
    {
        $status = 'Pending';
        $stmt = $this->conn->prepare("INSERT INTO adoptions (pet_id, adopter_id, status) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $values['pet_id'], $values['adopter_id'], $status);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // return all request created by an adopter
    public function getRequest($adopter_id)
    {
        $stmt = $this->conn->prepare("
        SELECT a.id, p.name AS pet_name, p.species, a.status, 
        ngos.name AS ngo_name, ngos.email AS ngo_email, ngos.phone AS ngo_phone, ngos.address AS ngo_address
        FROM adoptions a
        JOIN pets p ON a.pet_id = p.id
        JOIN ngos ON p.ngo_id = ngos.id
        WHERE a.adopter_id = ?
        ");
        $stmt->bind_param("i", $adopter_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
