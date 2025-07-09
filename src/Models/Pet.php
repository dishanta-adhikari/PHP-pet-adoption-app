<?php

namespace App\Models;

class Pet
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($values)
    {
        $stmt = $this->conn->prepare("
        INSERT INTO pets (ngo_id, name, species, breed, age, gender, description, city, image)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("isssissss", $values['ngo_id'], $values['name'], $values['species'], $values['breed'], $values['age'], $values['gender'], $values['description'], $values['city'], $values['imageName']);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getAllPets($status)
    {
        $stmt = $this->conn->prepare("SELECT pets.*, ngos.name AS ngo_name FROM pets JOIN ngos ON pets.ngo_id = ngos.id WHERE pets.status = ?");
        $stmt->bind_param('s', $status);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getBySearch($likeQuery)
    {
        $stmt = $this->conn->prepare("
        SELECT pets.id, pets.name, pets.age, pets.breed, pets.description, pets.image, ngos.name AS ngo_name, pets.city 
        FROM pets 
        JOIN ngos ON pets.ngo_id = ngos.id 
        WHERE pets.name LIKE ? OR pets.breed LIKE ? OR pets.city LIKE ? OR ngos.name LIKE ?
        ");
        $stmt->bind_param("ssss", $likeQuery, $likeQuery, $likeQuery, $likeQuery);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);;
    }

    public function getWithAdopter($ngo_id)
    {
        $stmt = $this->conn->prepare("
        SELECT 
            pets.*, 
            adopters.name AS adopter_name,
            adopters.email AS adopter_email,
            adopters.phone AS adopter_phone,
            adopters.address AS adopter_address
        FROM pets
        LEFT JOIN adoptions ON pets.id = adoptions.pet_id AND adoptions.status = 'Approved'
        LEFT JOIN adopters ON adoptions.adopter_id = adopters.id
        WHERE pets.ngo_id = ?
        GROUP BY pets.id
    ");
        $stmt->bind_param("i", $ngo_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
