<?php

namespace App\Controllers;

use App\Models\Adopter;
use Exception;

class AdopterController
{
    private $Adopter;

    public function __construct($db)
    {
        $this->Adopter = new Adopter($db);
    }

    public function getRequest($adopter_id)
    {
        return $this->Adopter->getRequest($adopter_id);
    }
}
