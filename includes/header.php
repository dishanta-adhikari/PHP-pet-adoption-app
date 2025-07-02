<?php
session_start();

// Set user info if logged in
$userName = $_SESSION['name'] ?? 'Guest';
$userRole = $_SESSION['role'] ?? 'visitor';
?>
<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Nunito', sans-serif;
    }

    /* Sticky header */
    .sticky-header {
        position: sticky;
        top: 0;
        width: 100%;
        background-color: #ffb300;
        /* Bright amber */
        color: #212529;
        padding: 0.5rem 0;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        z-index: 1030;
        font-weight: 600;
    }

    .sticky-header .container {
        max-width: 1140px;
    }

    .sticky-header .site-title {
        color: #212529;
        font-size: 1.25rem;
        font-weight: 700;
        text-decoration: none;
    }

    .sticky-header .user-info {
        font-size: 1rem;
        color: #212529;
    }

    .sticky-header .logout .btn-danger {
        padding: 0.25rem 0.75rem;
        font-size: 0.9rem;
    }
</style>

<header class="sticky-header">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="logo">
            <a href="dashboard_admin.php" class="site-title">Pet Adoption Portal</a>
        </div>
        <div class="user-info">
            <span><?= htmlspecialchars(ucfirst($userRole)) ?>: <?= htmlspecialchars($userName) ?></span>
        </div>
        <div class="logout">
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</header>