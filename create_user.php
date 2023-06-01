<?php
session_start();

// Check if user is not admin, redirect to index.php
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: index.php');
    exit();
}

// Include database connection file
require_once 'db_connect.php';

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Escape user inputs for security
    $name = $mysqli->real_escape_string($_POST['name']);
    $password = $mysqli->real_escape_string($_POST['password']);
    $email = $mysqli->real_escape_string($_POST['email']);

    if( strlen($password) < 8) {
        // If password does not meet validation criteria, display an error message
        echo 'Error: Password should be at least 8 characters in length';
    } else {
        // Check if user with the same username already exists in the database
        $result = $mysqli->query("SELECT * FROM users WHERE name='$name'");

    if ($result->num_rows > 0) {
        // If user with the same username already exists, display an error message
        echo 'Error: User with the same username already exists.';
    } else {
        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $sql = "INSERT INTO users (name, password, email) VALUES ('$name', '$hashed_password', '$email')";

        if ($mysqli->query($sql) === true) {
            // If user is successfully inserted, redirect to users.php
            header('Location: index.php');
            exit();
        } else {
            // If there is an error while inserting user, display an error message
            echo 'Error: ' . $mysqli->error;
        }
    }
    }
}

// Close database connection
$mysqli->close();
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="create_user.css">
</head>

<body>
    <h1>Add User</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
        </div>
        <div>
            <input type="submit" name="submit" value="Add">
        </div>
    </form>
    <script src="script.js"></script>
</body>

</html>