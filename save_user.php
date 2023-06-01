<?php
session_start();

// Check if user is not admin, redirect to index.php
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: index.php');
    exit();
}

// Include database connection file
require_once 'db_connect.php';

// Get the user ID, name, email, and role from the POST data
$user_id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$role = $_POST['role'];

// Update the user's information in the database
$sql = "UPDATE users SET name='$name', email='$email', role='$role' WHERE id=$user_id";
$mysqli->query($sql);

// Close database connection
$mysqli->close();

// Return a success response
echo "success";
?>
