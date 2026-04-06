<?php
// Safe session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if user is not logged in
if (!isset($_SESSION['userName'])) {
    header('Location: ../Login/login.html');
    exit();
}

// Set role if not already set (and $row['role'] exists)
if (!isset($_SESSION['role']) && isset($row['role'])) {
    $_SESSION['role'] = $row['role'];
}
?>
