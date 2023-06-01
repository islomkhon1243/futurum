<?php
// Include the database connection file
include('db_connect.php');

// Check if the course ID is set in the URL
if (isset($_GET['id'])) {
  // Sanitize the course ID
  $id = mysqli_real_escape_string($mysqli, $_GET['id']);

  // Query the database for the course with the given ID
  $query = "SELECT * FROM courses WHERE course_id = '$id'";
  $result = mysqli_query($mysqli, $query);

  // Check if a course was found
  if (mysqli_num_rows($result) == 1) {
    // Fetch the course data
    $row = mysqli_fetch_assoc($result);

    // Set the page title to the course name
    $title = $row['name'];
  } else {
    // If no course was found, redirect to the courses page
    header('Location: courses.php');
    exit();
  }
} else {
  // If the course ID is not set, redirect to the courses page
  header('Location: courses.php');
  exit();
}

// Check if the user is logged in
session_start();
if (!isset($_SESSION['id'])) {
  // If the user is not logged in, redirect to the login page
  header('Location: login.php');
  exit();
}

// Get the user ID from the session
$user_id = $_SESSION['id'];

// Check if the user is already enrolled in the course
$enrollment_query = "SELECT * FROM course_enrollments WHERE user_id = '$user_id' AND course_id = '$id'";
$add_enroll_query = "UPDATE courses SET enrolled_students = enrolled_students + 1 WHERE course_id = '$id';";
$enrollment_result = mysqli_query($mysqli, $enrollment_query);
$is_enrolled = mysqli_num_rows($enrollment_result) > 0;

// If the user clicks the "enroll" button, add a new enrollment record
if (isset($_POST['enroll'])) {
  $enrollment_query = "INSERT INTO course_enrollments (user_id, course_id) VALUES ('$user_id', '$id')";
  mysqli_query($mysqli, $enrollment_query);
  $is_enrolled = true;
}

$query1 = "SELECT COUNT(*) as count FROM course_enrollments WHERE course_id = '$id'";
$result1 = mysqli_query($mysqli, $query1);
$row1 = mysqli_fetch_assoc($result1);
if ($row1['count'] == 0) {
  // Update the enrolled_students column in the courses table to 0 for the given $id
  $update_query1 = "UPDATE courses SET enrolled_students = 0 WHERE course_id = '$id'";
  mysqli_query($mysqli, $update_query1);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $title; ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-md bg-dark navbar-dark">
        <div class="container">
            <!-- Back button -->
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-arrow-left"></i> Back
            </a>

            <!-- Brand name -->
            <a class="navbar-brand mx-auto" href="#">
                <?php echo $row['name']; ?>
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
    </nav>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <h1><?php echo $row['name']; ?></h1>
                <p><?php echo $row['description']; ?></p>
                <p><strong>Duration:</strong> <?php echo $row['duration']; ?></p>
                <p><strong>Students Enrolled:</strong> <?php echo $row['enrolled_students']; ?></p>
            </div>
            <div class="col-md-4">
                <?php if (!$is_enrolled) { ?>
                <form method="post">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="course_id" value="<?php echo $id; ?>">
                    <button type="submit" name="enroll" class="btn btn-primary btn-block">Enroll in Course</button>
                </form>
                <?php } else { ?>
                <button class="btn btn-success btn-block" disabled>You are Enrolled</button>
                <a href="indiv_course.php?course_id=<?php echo $id; ?>" class="btn btn-primary btn-block">Continue</a>
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- Bootstrap JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
<!-- Add a success message if the user enrolls successfully -->
<?php if ($is_enrolled && isset($_POST['enroll'])) { 
    mysqli_query($mysqli, $add_enroll_query);
?>
<div class="alert alert-success mt-4">
    You have successfully enrolled in the course.
</div>
<?php } ?>