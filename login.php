<?php
// Start session
session_start();


// Include database connection file
require_once 'db_connect.php';

// Define variables and initialize with empty values
$email = $password = '';
$email_err = $password_err = '';

// Process form data when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Validate email
    if (empty(trim($_POST['email']))) {
        $email_err = 'Please enter your email.';
    } else {
        $email = trim($_POST['email']);
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter your password.';
    } else {
        $password = trim($_POST['password']);
    }

    // Check input errors before attempting to login
    if (empty($email_err) && empty($password_err)) {

        // Prepare a select statement
        $sql = 'SELECT user_id, name, email, password FROM users WHERE email = ?';

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param('s', $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if email exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $name, $email, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION['loggedin'] = true;
                            $_SESSION['id'] = $id;
                            $_SESSION['name'] = $name;
                            $_SESSION['email'] = $email;

                            if ($email === 'admin@example.com') {
                                $_SESSION['is_admin'] = true;
                            }

                            // Redirect user to welcome page
                            header('location: index.php');
                        } else {
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered is not valid.';
                        }
                    }
                } else {
                    // Display an error message if email doesn't exist
                    $email_err = 'No account found with that email.';
                }
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
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="login.css">
    <style>
          body {
            background-color: #00DBDE;
            background-image: linear-gradient(90deg, #00DBDE 0%, #FC00FF 100%);
            transition: filter 0.3s ease;
            height: 753px;
          }
          .text-center{
            color: white;
          }

          label{
            color: white;
          }

          #login-form {
            background-color: rgba(255, 255, 255, 0.2); /* Adjust the opacity as needed */
            padding: 20px;
            border-radius: 10px;

          }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <h2 class="text-center">Login</h2>
                <form id="login-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                        <span class="help-block"><?php echo $email_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control">
                        <span class="help-block"><?php echo $password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary btn-block" value="Login">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="module">
            import { fairyDustCursor } from "https://unpkg.com/cursor-effects@latest/dist/esm.js";
            new fairyDustCursor();
    </script>
</body>

</html>