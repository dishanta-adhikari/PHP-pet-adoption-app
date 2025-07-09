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
}
