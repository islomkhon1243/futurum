<?php
require_once 'db_connect.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = mysqli_real_escape_string($mysqli, $_POST['id']);
    $name = mysqli_real_escape_string($mysqli, $_POST['name']);
    $description = mysqli_real_escape_string($mysqli, $_POST['description']);
    $year_of_foundation = mysqli_real_escape_string($mysqli, $_POST['year_of_foundation']);
    $number_of_students = mysqli_real_escape_string($mysqli, $_POST['number_of_students']);
    $educating_language = mysqli_real_escape_string($mysqli, $_POST['educating_language']);
    $scholarship_number = mysqli_real_escape_string($mysqli, $_POST['scholarship_number']);
    $admission_requirements = mysqli_real_escape_string($mysqli, $_POST['admission_requirements']);
    $application_form_url = mysqli_real_escape_string($mysqli, $_POST['application_form_url']);
    $start_date = mysqli_real_escape_string($mysqli, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($mysqli, $_POST['end_date']);
    $image_url_1 = mysqli_real_escape_string($mysqli, $_POST['image_url_1']);
    $image_url_2 = mysqli_real_escape_string($mysqli, $_POST['image_url_2']);
    $image_url_3 = mysqli_real_escape_string($mysqli, $_POST['image_url_3']);
    $user_id = mysqli_real_escape_string($mysqli, $_POST['user_id']);

    // Update university in database
    $sql = "UPDATE universities SET name='$name', description='$description', year_of_foundation='$year_of_foundation', number_of_students='$number_of_students', educating_language='$educating_language', scholarship_number='$scholarship_number', admission_requirements='$admission_requirements', application_form_url='$application_form_url', start_date='$start_date', end_date='$end_date', image_url_1='$image_url_1', image_url_2='$image_url_2', image_url_3='$image_url_3', user_id='$user_id' WHERE id='$id'";
    if (mysqli_query($mysqli, $sql)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error updating university: " . mysqli_error($mysqli);
    }
} else {
    // Retrieve university from database
    $id = mysqli_real_escape_string($mysqli, $_GET['id']);
    $sql = "SELECT * FROM universities WHERE id='$id'";
    $result = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_assoc($result);

    // Display form for updating university
    echo "<h1>Update university</h1>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='id' value='{$row['id']}'/>";
    echo "<div class='form-group'>";
    echo "<label>Name</label>";
    echo "<input type='text' name='name' value='{$row['name']}' class='form-control' required/>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label>Description</label>";
    echo "<textarea name='description' class='form-control' required>{$row['description']}</textarea>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label>Year of Foundation</label>";
    echo "<input type='text' name='year_of_foundation' value='{$row['year_of_foundation']}' class='form-control' required/>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label>Number of Students</label>";
    echo "<input type='number' name='number_of_students' value='{$row['number_of_students']}' class='form-control' required/>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label>Educating Language</label>";
    echo "<input type='text' name='educating_language' value='{$row['educating_language']}' class='form-control' required/>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label>Scholarship Number</label>";
    echo "<input type='number' name='scholarship_number' value='{$row['scholarship_number']}' class='form-control' required/>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label>Admission Requirements</label>";
    echo "<textarea name='admission_requirements' class='form-control' required>{$row['admission_requirements']}</textarea>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label>Application Form URL</label>";
    echo "<input type='text' name='application_form_url' value='{$row['application_form_url']}' class='form-control' required/>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label>Start Date</label>";
    echo "<input type='date' name='start_date' value='{$row['start_date']}' class='form-control' required/>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label>End Date</label>";
    echo "<input type='date' name='end_date' value='{$row['end_date']}' class='form-control' required/>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label>Image URL 1</label>";
    echo "<input type='url' name='image_url_1' value='{$row['image_url_1']}' class='form-control' required/>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label>Image URL 2</label>";
    echo "<input type='url' name='image_url_2' value='{$row['image_url_2']}' class='form-control'/>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label>Image URL 3</label>";
    echo "<input type='url' name='image_url_3' value='{$row['image_url_3']}' class='form-control'/>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label>User ID</label>";
    echo "<input type='text' name='user_id' value='{$row['user_id']}' class='form-control' required/>";
    echo "</div>";
    echo "<input type='submit' value='Update' class='btn btn-primary'/>";
    echo "</form>";
}
?>

<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            /* Center the form on the page */
            form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.3);
            }

            /* Style the form labels */
            label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            }

            /* Style the form input fields */
            input[type="text"],
            input[type="number"],
            input[type="url"],
            textarea {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 3px;
            margin-bottom: 20px;
            }

            textarea {
            height: 150px;
            }

            /* Style the submit button */
            button[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 3px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            }

            button[type="submit"]:hover {
            background-color: #3e8e41;
            }

            /* Style the error message */
            .error {
            color: red;
            margin-bottom: 20px;
            }

            h1 {
                text-align: center;
                margin: 30px;
            }
        </style>
    </head>

</html>