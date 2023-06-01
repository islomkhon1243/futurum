<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $year_of_foundation = $_POST['year_of_foundation'];
    $number_of_students = $_POST['number_of_students'];
    $educating_language = $_POST['educating_language'];
    $scholarship_number = $_POST['scholarship_number'];
    $admission_requirements = $_POST['admission_requirements'];
    $application_form_url = $_POST['application_form_url'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $image_url_1 = $_POST['image_url_1'];
    $image_url_2 = $_POST['image_url_2'];
    $image_url_3 = $_POST['image_url_3'];
    $user_id = $_POST['user_id'];

    // Prepare insert statement
    $sql = "INSERT INTO universities (name, description, year_of_foundation, number_of_students, educating_language, scholarship_number, admission_requirements, application_form_url, start_date, end_date, image_url_1, image_url_2, image_url_3) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssssssss", $name, $description, $year_of_foundation, $number_of_students, $educating_language, $scholarship_number, $admission_requirements, $application_form_url, $start_date, $end_date, $image_url_1, $image_url_2, $image_url_3);

    // Execute statement
    if (mysqli_stmt_execute($stmt)) {
        // Redirect to universities table
        header('Location: index.php');
        exit;
    } else {
        echo 'Error inserting record: ' . mysqli_error($mysqli);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Add New University</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

	<div class="container">
        <br>
        <a href="index.php" class="btn btn-primary mb-2">Back to Universities Table</a>
		<h1>Add New University</h1>
        <form method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="year_of_foundation">Year of Foundation:</label>
                <input type="number" class="form-control" id="year_of_foundation" name="year_of_foundation" required>
            </div>
            <div class="form-group">
                <label for="number_of_students">Number of Students:</label>
                <input type="number" class="form-control" id="number_of_students" name="number_of_students" required>
            </div>
            <div class="form-group">
                <label for="educating_language">Educating Language:</label>
                <input type="text" class="form-control" id="educating_language" name="educating_language" required>
            </div>
            <div class="form-group">
                <label for="scholarship_number">Scholarship Number:</label>
                <input type="number" class="form-control" id="scholarship_number" name="scholarship_number" required>
            </div>
            <div class="form-group">
                <label for="admission_requirements">Admission Requirements:</label>
                <textarea class="form-control" id="admission_requirements" name="admission_requirements" required></textarea>
            </div>
            <div class="form-group">
                <label for="application_form_url">Application Form URL:</label>
                <input type="text" class="form-control" id="application_form_url" name="application_form_url" required>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" required>
            </div>
            <div class="form-group">
                <label for="image_url_1">Image URL 1:</label>
                <input type="url" class="form-control" id="image_url_1" name="image_url_1" required>
            </div>
            <div class="form-group">
                <label for="image_url_2">Image URL 2:</label>
                <input type="url" class="form-control" id="image_url_2" name="image_url_2">
            </div>
            <div class="form-group">
                <label for="image_url_3">Image URL 3:</label>
                <input type="url" class="form-control" id="image_url_3" name="image_url_3">
            </div>
            <div class="form-group">
                <input type="hidden" class="form-control" id="user_id" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
    <script>
    $(document).ready(function () {
        bsCustomFileInput.init();
    });
    </script>
    </body>
</html>