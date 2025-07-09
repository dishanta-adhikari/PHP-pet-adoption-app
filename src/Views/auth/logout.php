<?php
session_start();

require_once __DIR__ . "/../includes/_init.php";

session_unset();

session_destroy();

header("Location: " . $appUrl);

exit();
