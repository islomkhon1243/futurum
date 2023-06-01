<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

require_once('db_connect.php');

$user_id = $_SESSION['id'];

// Fetch user data
$user_query = "SELECT * FROM user_documents WHERE user_id = $user_id";
$user_result = mysqli_query($mysqli, $user_query);
$user_data = mysqli_fetch_assoc($user_result);

// Fetch user's courses
$courses_query = "SELECT courses.name FROM course_enrollments INNER JOIN courses ON courses.course_id = course_enrollments.course_id WHERE course_enrollments.user_id = $user_id";
$courses_result = mysqli_query($mysqli, $courses_query);

// Fetch user's application submissions
$applications_query = "SELECT * FROM universities WHERE FIND_IN_SET($user_id, REPLACE(user_id, ' ', ',')) > 0";
$applications_result = mysqli_query($mysqli, $applications_query);

// Fetch user's attached documents
$documents_query = "SELECT * FROM user_documents WHERE user_id = $user_id";
$documents_result = mysqli_query($mysqli, $documents_query);
$num_rows = mysqli_num_rows($documents_result);

if ($num_rows == 0) {
    $sql_users = "SELECT user_id, name FROM users WHERE user_id = $user_id";
    $sql_users_result = mysqli_query($mysqli, $sql_users);

    if ($sql_users_result) {
    $row = mysqli_fetch_assoc($sql_users_result);
    $user_id = $row['user_id'];
    $full_name = $row['name'];

    $sql_user_documents = "INSERT INTO user_documents (user_id, full_name) VALUES ('$user_id', '$full_name')";
    mysqli_query($mysqli, $sql_user_documents);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $full_name = $_POST['full_name'];
    $dob = $_POST['dob'];
    $iin = $_POST['iin'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];

    // Update user data
    $update_query = "UPDATE user_documents SET full_name='$full_name', dob='$dob', iin='$iin', phone_number='$phone_number', address='$address' WHERE user_id=$user_id";
    $result = mysqli_query($mysqli, $update_query);
    $update_query_2 = "UPDATE users SET name='$full_name' WHERE user_id=$user_id";
    $result_2 = mysqli_query($mysqli, $update_query_2);

    if ($result || $result_2) {
        // Redirect to user profile page
        header('Location: profile.php');
        exit();
    } else {
        echo "Error updating user data: " . mysqli_error($mysqli);
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-md bg-dark navbar-dark sticky-top">
        <div class="container">
            <!-- Back button -->
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-arrow-left"></i> Back
            </a>

            <!-- Brand name -->
            <a class="navbar-brand mx-auto text-center" href="#">
                <?php echo "Profile"; ?>
            </a>

            <!-- Profile and logout links -->
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php"><?php echo $_SESSION['name']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav><br><br>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="<?php echo $user_data['avatar']; ?>" class="rounded-circle img-thumbnail mb-3" alt="User Avatar" style="max-width: 150px;">
                        <h5 class="card-title"><?php echo $user_data['full_name']; ?></h5>
                        <p class="card-text"><?php echo $user_data['phone_number']; ?></p>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Enrolled Courses</h5>
                        <ul class="list-group list-group-flush">
                            <?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
                                <li class="list-group-item"><?php echo $course['name']; ?></li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Application Submissions</h5>
                        <ul class="list-group list-group-flush">
                        <?php
                            $query = "SELECT * FROM applications_status WHERE user_id = $user_id";
                            $status_result = mysqli_query($mysqli, $query);

                            while ($application = mysqli_fetch_assoc($applications_result)):
                                $status_rows = mysqli_fetch_assoc($status_result); // Fetch status for each application
                                $status = $status_rows['user_status'];
                                $backgroundColor = "";
                                $heading = "";

                                if ($status == "PROCESSING") {
                                    $backgroundColor = "background-color: yellow;";
                                    $heading = "Processing";
                                } elseif ($status == "DECLINED") {
                                    $backgroundColor = "background-color: red; color: white;";
                                    $heading = "Declined";
                                } elseif ($status == "ACCEPTED") {
                                    $backgroundColor = "background-color: green; color: white;";
                                    $heading = "Accepted";
                                }
                            ?>
                                <li class="list-group-item" style="<?php echo $backgroundColor; ?>">
                                    <span class="badge badge-primary"><?php echo $heading; ?></span>
                                    <?php echo $application['name']; ?>
                                </li>
                            <?php
                            endwhile;
                            ?>
                        </ul>
                    </div>
                </div>
                <br><br>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Personal Information</h5>
                        <form method="post">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="full_name">Full Name</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $user_data['full_name']; ?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                <label for="dob">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $user_data['dob']; ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="iin">IIN</label>
                            <input type="text" class="form-control" id="iin" name="iin" value="<?php echo $user_data['iin']; ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone_number">Phone Number</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" value="<?php echo $user_data['phone_number']; ?>" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="address">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2" required><?php echo $user_data['address']; ?></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Attached Documents</h5>
                        <ul class="list-group list-group-flush">
                            <?php while ($document = mysqli_fetch_assoc($documents_result)): ?>
                                <li class="list-group-item"><?php echo $document['high_school_diploma']; ?></li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>