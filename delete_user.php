<?php
session_start();

// Check if user is logged in and is an admin user
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // Redirect user to login page if not logged in or not an admin user
    header('Location: login.php');
    exit;
}

// Include database connection file
require_once 'db_connect.php';

// Check if user ID is provided in URL parameters
if (!isset($_GET['id'])) {
    // Redirect user to users page if ID is not provided
    header('Location: users.php');
    exit;
}

// Get user ID from URL parameters and sanitize it
$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

// Prepare SQL statement to delete user with the given ID
$sql = "DELETE FROM users WHERE user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $id);

// Execute SQL statement and check for errors
if (!$stmt->execute()) {
    // Display error message and exit if there was an error
    echo 'Error deleting user: ' . $stmt->error;
    exit;
}

// Close database connection and redirect user to users page
$mysqli->close();
header('Location: index.php');
exit;
?>
