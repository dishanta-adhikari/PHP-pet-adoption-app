<?php
// Composer autoload
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Database.php';

$appUrl = $_ENV['APP_URL'];

require_once __DIR__.'/../src/Views/Components/header.php';
require_once __DIR__.'/../src/Views/Components/footer.php';

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
// $dotenv->load();

