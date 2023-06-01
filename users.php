<!DOCTYPE html>
<html>

<head>
    <title>Users</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <?php
		session_start();

		// Include database connection file
		require_once 'db_connect.php';

		// Handle form submission
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			// Validate input data
			$name = $_POST['name'];
			$email = $_POST['email'];
			$role = $_POST['role'];

			if (empty($name) || empty($email) || empty($password) || empty($role)) {
				echo '<div class="alert alert-danger" role="alert">Please fill in all the fields.</div>';
			} else {
				// Insert new user row into the database
				$sql = "INSERT INTO users (name, email, role) VALUES ('$name', '$email', '$role')";
				if ($mysqli->query($sql) === true) {
					echo '<div class="alert alert-success" role="alert">User created successfully.</div>';
				} else {
					echo '<div class="alert alert-danger" role="alert">Error creating user: ' . $mysqli->error . '</div>';
				}
			}
		}

		// Fetch all users data from database
		$sql = "SELECT * FROM users";
		$result = $mysqli->query($sql);

		// Check if any data found
		if ($result->num_rows > 0) {
			// Start table
			echo '<h1>Users Table</h1><br>';
			echo '<table class="table">';
			echo '<thead><tr><th>Name</th><th>Email</th><th>Role</th>';

			// Display Edit and Delete buttons only for admin users
			if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
				echo '<th>Actions</th>';
			}

			echo '</tr></thead><tbody>';

			// Loop through each row of data and print it in the table
			while ($row = $result->fetch_assoc()) {
				echo '<tr>';
				echo '<td>' . $row['name'] . '</td>';
				echo '<td>' . $row['email'] . '</td>';
				echo '<td>' . $row['role'] . '</td>';

				// Display Edit and Delete buttons only for admin users
				if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
					echo '<td>
							<a href="edit_user.php?id=' . $row['user_id'] . '">
								<button class="btn btn-primary mr-2">Edit</button>
							</a>
							<a href="delete_user.php?id=' . $row['user_id'] . '">
								<button class="btn btn-danger">Delete</button>
							</a>
						</td>';
				}

				echo '</tr>';
			}

			// End table
			echo '</tbody></table>';

			// Create button for adding a new user record
			if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
				echo '<button id="create" class="btn btn-success mb-3" onclick="location.href=\'create_user.php\'">Create</button><br><br><br><br>';
			}
		} else {
			// If no data found, display a message
			echo '<div class="alert alert-warning" role="alert">No users found.</div>';
            // Create button for adding a new user record
		if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
			echo '<button id="create" class="btn btn-success mb-3" onclick="location.href=\'create_user.php\'">Create</button>';
		}
	}

	// Close database connection
	$mysqli->close();
	?>
    </div>
</body>

</html>