<?php

session_start();

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/Database.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

define('APP_URL', $_ENV['APP_URL']);
$appUrl = $_ENV['APP_URL'];

require_once __DIR__ . '/../Components/header.php';
require_once __DIR__ . '/../Components/footer.php';
