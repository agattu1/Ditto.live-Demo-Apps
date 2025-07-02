<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_role'])) {
    // Determine the correct path to login.php based on current script location
    $login_path = (strpos($_SERVER['SCRIPT_NAME'], '/sales/') !== false) ? '../login.php' : 'login.php';
    header('Location: ' . $login_path);
    exit();
}

// Check access permissions for customerwon page
if ($currentPage === 'customerwon' && $_SESSION['user_role'] !== 'manager') {
    header('Location: task.php'); // Redirect to task.php within the same directory
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Our Custom CRM</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Add your existing CSS links here -->
</head>
<body>
<div class="container">