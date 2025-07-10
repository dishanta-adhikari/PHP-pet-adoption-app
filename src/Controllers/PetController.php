<?php

namespace App\Controllers;

use App\Models\Pet;
use Exception;

class PetController
{
    private $Pet;

    public function __construct($db)
    {
        $this->Pet = new Pet($db);
    }

    public function create($values)
    {
        try {
            $imageName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = uniqid() . "." . $ext;
                $path = __DIR__ . "/../../public/assets/uploads/";
                move_uploaded_file($_FILES['image']['tmp_name'], $path . $imageName);
            } else {
                throw new Exception("Image upload failed.");
            }

            if (
                empty($values['ngo_id']) || empty($values['name']) || empty($values['species']) ||
                empty($values['breed']) || empty($values['age']) || empty($values['gender']) ||
                empty($values['description']) || empty($values['city'])
            ) {
                throw new Exception('Required fields are empty!');
            }

            $values['imageName'] = $imageName;

            $pet = $this->Pet->create($values);
            if (!$pet) {
                throw new Exception('Error creating pet!');
            }

            $_SESSION['success'] = "Your pet is ready for adoption!";
            header("Location: " . APP_URL . "/src/Views/ngo/dashboard");
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: " . APP_URL . "/src/Views/ngo/dashboard");
            exit;
        }
    }

    public function getAll()
    {
        return $this->Pet->getAll();
    }


    public function getAvailablePets()
    {
        $status = "Available";
        return $this->Pet->getAllPets($status);
    }

    public function getBySearch()
    {
        $query = isset($_GET['query']) ? trim($_GET['query']) : '';
        return $this->Pet->getBySearch($query);
    }

    public function getWithAdopter($ngo_id)
    {
        return $this->Pet->getWithAdopter($ngo_id);
    }
}
