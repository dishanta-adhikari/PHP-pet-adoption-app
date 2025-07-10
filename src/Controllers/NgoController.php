<?php

namespace App\Controllers;

use App\Models\Ngo;
use Exception;

class NgoController
{
    private $Ngo;

    public function __construct($db)
    {
        $this->Ngo = new Ngo($db);
    }

    public function verifyNgo()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ngo') {
            header("Location: " . APP_URL);
            exit;
        }
    }
}
