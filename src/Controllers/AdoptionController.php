<?php

namespace App\Controllers;

use App\Models\Ngo;
use App\Models\Pet;
use App\Models\Adopter;
use Exception;

class AdoptionController
{
    private $Adopter, $Ngo, $Pet;

    public function __construct($db)
    {
        $this->Adopter = new Adopter($db);
        $this->Ngo = new Ngo($db);
        $this->Pet = new Pet($db);
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

    public function UpdateRequest($values)
    {
        try {
            $values['adoption_id'] = intval($values['id']);
            $values['ngo_id'] = $_SESSION['user_id'];
            $action = $values['action'];
            $status = ($action === 'approve') ? 'Approved' : 'Rejected';

            if (empty($values['adoption_id']) || empty($values['ngo_id']) || empty($values['action'])) {
                throw new Exception('Required Fields are empty!');
            }

            if (!in_array($action, ['approve', 'reject'])) {
                die("Invalid action.");
            }

            // Check if this adoption belongs to a pet under this NGO
            $result = $this->Pet->checkValidAdoption($values);
            if ($result->num_rows === 0) {
                throw new Exception("Unauthorized request.");
            }


            $param = [
                'status' => $status,
                'adoption_id' => $values['adoption_id'],
            ];
            // Update status in adoptions table
            $update = $this->Pet->updateStatus($param);
            if (!$update) {
                throw new Exception('Error Updating request!');
            }

            $petRow = $result->fetch_assoc();
            $pet_id = $petRow['pet_id'];

            // If approved, update pet status
            if ($status === 'Approved') {
                $res = $this->Pet->petStatus($pet_id);
                if (!$res) {
                    throw new Exception('Error Updating pet status!');
                }
                $_SESSION['success'] = "Request Approved!";
                header("Location: " . APP_URL . "/src/Views/ngo/adoption/request?msg=Request " . $status);
                exit;
            }

            $_SESSION['success'] = "Request Rejected!";
            header("Location: " . APP_URL . "/src/Views/ngo/adoption/request?msg=Request " . $status);
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: " . APP_URL . "/src/Views/ngo/dashboard");
            exit;
        }
    }
}
