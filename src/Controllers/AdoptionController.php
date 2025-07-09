<?php

namespace App\Controllers;

use App\Models\Ngo;
use App\Models\Adopter;
use Exception;

class AdoptionController
{
    private $Adopter, $Ngo;

    public function __construct($db)
    {
        $this->Adopter = new Adopter($db);
        $this->Ngo = new Ngo($db);
    }

    public function create($values)
    {
        try {
            if (empty($values['pet_id']) || empty($values['adopter_id'])) {
                throw new Exception("Required fields are empty!");
            }

            $validate = $this->Adopter->validateDuplicate($values);
            if ($validate && $validate->num_rows > 0) {
                throw new Exception("You have already requested to adopt this pet.");
            }

            $request = $this->Adopter->createRequest($values);
            if (!$request) {
                throw new Exception("Something went wrong.");
            }

            $_SESSION['success'] = "Adoption request submitted successfully!";
            header("Location: " . APP_URL . "/src/Views/adopter/dashboard");
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: " . APP_URL . "/src/Views/adopter/dashboard");
            exit;
        }
    }

    public function getRequest($ngo_id)
    {
        return $this->Ngo->getRequests($ngo_id);
    }
}
