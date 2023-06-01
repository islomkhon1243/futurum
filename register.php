<?php
// Start session
session_start();

// Include database connection file
require_once 'db_connect.php';

// Define variables and initialize with empty values
$name = $email = $password = $confirm_password = '';
$name_err = $email_err = $password_err = $confirm_password_err = '';

// Process form data when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Validate name
    if (empty(trim($_POST['name']))) {
        $name_err = 'Please enter your name.';
    } else {
        $name = trim($_POST['name']);
    }

    // Validate email
    if (empty(trim($_POST['email']))) {
        $email_err = 'Please enter your email.';
    } else {
        // Prepare a select statement
        $sql = 'SELECT user_id FROM users WHERE email = ?';

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param('s', $param_email);

            // Set parameters
            $param_email = trim($_POST['email']);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $email_err = 'This email is already taken.';
                } else {
                    $email = trim($_POST['email']);
                }
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
            }

            // Close statement
            $stmt->close();
        }
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter your password.';
    } elseif (strlen(trim($_POST['password'])) < 8) {
        $password_err = 'Password must have at least 8 characters.';
    } else {
        $password = trim($_POST['password']);
    }

    // Validate confirm password
    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = 'Please confirm your password.';
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if ($password != $confirm_password) {
            $confirm_password_err = 'Passwords do not match.';
        }
    }

    // Check input errors before inserting into database
    if (empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

        // Prepare an insert statement
        $sql = 'INSERT INTO users (name, email, password) VALUES (?, ?, ?)';

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param('sss', $param_name, $param_email, $param_password);

            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to login page
                header('location: login.php');
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/css/bootstrap.min.css">
        <style>
           body {
            background-color: #00DBDE;
            background-image: linear-gradient(90deg, #00DBDE 0%, #FC00FF 100%);
            transition: filter 0.3s ease;
            height: 705px
        }
        .text-center {
            color: white;
            font-weight: bold;
        }
        label{
            color: white;
            font-weight: bold;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 10px;
        }
        .card-header{
            border-radius: 100px;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <h2 class="text-center">Registration Form</h2>
                <br><br>
                <div class="card">
                    
                    <div class="card-body">
                        <form action="register.php" method="post" id="register-form">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo $name; ?>">
                                <span class="invalid-feedback"><?php echo $name_err; ?></span>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo $email; ?>">
                                <span class="invalid-feedback"><?php echo $email_err; ?></span>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="password" name="password">
                                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password">
                                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-block mt-4">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
            <script type="module">
            import { fairyDustCursor } from "https://unpkg.com/cursor-effects@latest/dist/esm.js";
            new fairyDustCursor();
</script>
</body>
</html>