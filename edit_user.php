<?php
session_start();

// Include database connection file
require_once 'db_connect.php';

// Check if the user ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the user data from the database
    $sql = "SELECT * FROM users WHERE user_id = '$id'";
    $result = $mysqli->query($sql);

    // Check if the user exists
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $email = $row['email'];
        $password = $row['password'];
    } else {
        // If the user doesn't exist, redirect to the users page
        header("Location: users.php");
        exit;
    }
} else {
    // If the user ID is not provided, redirect to the users page
    header("Location: users.php");
    exit;
}

// If the user submits the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the new values from the form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Update the user data in the database
    $sql = "UPDATE users SET name = '$name', email = '$email', password = '$password' WHERE user_id = '$id'";
    $result = $mysqli->query($sql);

    // If the update is successful, redirect to the users page
    if ($result) {
        header("Location: index.php#users");
        exit;
    } else {
        // If the update fails, display an error message
        $error_message = 'Failed to update user.';
    }
}

// Close database connection
$mysqli->close();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="edit_user.css">
</head>
<body>
    <h1>Edit User</h1>
    <?php if (isset($error_message)) { ?>
    <p class="error"><?php echo $error_message; ?></p>
    <?php } ?>
    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $name; ?>" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>"
            required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="<?php echo $password; ?>"
            required><br>
        <input type="submit" name="submit" value="Update">
    </form>
</body>
</html>