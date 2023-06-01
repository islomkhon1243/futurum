<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Application Form</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <div class="container">
        <h1 class="text-center my-5">KBTU University Application Form</h1>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
            enctype="multipart/form-data">
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" class="form-control" id="dob" name="dob" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="id_code">IIN:</label>
                <input type="text" class="form-control" id="id_code" name="id_code" required>
            </div>

            <div class="form-group">
                <label for="hs_diploma">High School Diploma File:</label>
                <input type="file" class="form-control-file" id="hs_diploma" name="hs_diploma" required>
            </div>

            <div class="form-group">
                <label for="edu_language">Education Language:</label>
                <select class="form-control" id="edu_language" name="edu_language" required>
                    <option value="Russian">Kazakh</option>
                    <option value="Russian">Russian</option>
                    <option value="English">English</option>
                </select>
            </div>

            <div class="form-group">
                <label for="eng_cert">English Proficiency Certificate File:</label>
                <input type="file" class="form-control-file" id="eng_cert" name="eng_cert" required>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
            <br><br>
        </form>

        <?php
        // Include the database connection file
        include 'db_connect.php';

        session_start();

        // Check if the user is logged in
        if(isset($_SESSION['loggedin'])) {
        // Get the login id from the session
            $user_id = $_SESSION['id'];
        }

        // Check if the form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $full_name = $_POST['full_name'];
        $dob = $_POST['dob'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $id_code = $_POST['id_code'];
        $edu_language = $_POST['edu_language'];

        // Get the file paths and names
        $hs_diploma_file = $_FILES['hs_diploma']['name'];
        $hs_diploma_tmp = $_FILES['hs_diploma']['tmp_name'];
        $hs_diploma_size = $_FILES['hs_diploma']['size'];
        $hs_diploma_ext = pathinfo($hs_diploma_file, PATHINFO_EXTENSION);
        $hs_diploma_new_name = uniqid('', true) . '.' . $hs_diploma_ext;
        $hs_diploma_path = 'uploads/' . $hs_diploma_new_name;

        $eng_cert_file = $_FILES['eng_cert']['name'];
        $eng_cert_tmp = $_FILES['eng_cert']['tmp_name'];
        $eng_cert_size = $_FILES['eng_cert']['size'];
        $eng_cert_ext = pathinfo($eng_cert_file, PATHINFO_EXTENSION);
        $eng_cert_new_name = uniqid('', true) . '.' . $eng_cert_ext;
        $eng_cert_path = 'uploads/' . $eng_cert_new_name;

        // Check if the user has already applied
        $sql_check = "SELECT sub_universities, submissions FROM users WHERE user_id = '$user_id'";
        $result_check = mysqli_query($mysqli, $sql_check);

        if (mysqli_num_rows($result_check) > 0) {
            $row = mysqli_fetch_assoc($result_check);
            $sub_universities = $row['sub_universities'];
            $submissions = $row['submissions'];
            
            if (strpos($sub_universities, 'kbtu') !== false) {
                echo "<p class='alert alert-danger'>You have already submitted an application for KBTU.</p>";
            } 
            else if ($submissions >= 4) {
                echo "<p class='alert alert-danger'>You have already submitted 4 application forms.</p>";
            }
            else {
                try {
                    // Update the user's submission count and add "iitu" to sub_universities
                    $sql_update = "UPDATE users SET submissions = submissions + 1, sub_universities = CONCAT(sub_universities, 'kbtu,') WHERE user_id = '$user_id'";
                    mysqli_query($mysqli, $sql_update);

                    // Insert data into the database and move files to the uploads folder
                    if (move_uploaded_file($hs_diploma_tmp, $hs_diploma_path) && move_uploaded_file($eng_cert_tmp, $eng_cert_path)) {
                        $sql_insert = "INSERT INTO kbtu_applications (user_id, full_name, dob, email, phone, id_code, edu_language, hs_diploma, eng_cert) VALUES ('$user_id', '$full_name', '$dob', '$email', '$phone', '$id_code', '$edu_language', '$hs_diploma_path', '$eng_cert_path')";
                        mysqli_query($mysqli, $sql_insert);
                        $sql_insert_2 = "UPDATE universities SET user_id = CONCAT(user_id, '$user_id,') WHERE id = '4'";
                        mysqli_query($mysqli, $sql_insert_2);
                        $sql_insert_status = "INSERT INTO applications_status (user_id, user_status, university_name) VALUES ('$user_id', 'PROCESSING', 'kbtu')";
                        mysqli_query($mysqli, $sql_insert_status);
                        echo "<p class='alert alert-success'>Application submitted successfully.</p>";
                    } else {
                        echo "<p class='alert alert-danger'>Error uploading files.</p>";
                    }
                } catch (mysqli_sql_exception $e) {
                    // handle MySQL exception
                    echo "MySQL Error: " . $e->getMessage();
                } catch (Exception $e) {
                    // handle other exceptions
                    echo "Error: " . $e->getMessage();
                }
            } 
        } else {
            // Insert data into the database and move files to the uploads folder
            if (move_uploaded_file($hs_diploma_tmp, $hs_diploma_path) && move_uploaded_file($eng_cert_tmp, $eng_cert_path)) {
                $sql_update = "UPDATE users SET submissions = submissions + 1, sub_universities = CONCAT(sub_universities, 'kbtu,') WHERE user_id = '$user_id'";
                mysqli_query($mysqli, $sql_update);
                $sql_insert = "INSERT INTO kbtu_applications (user_id, full_name, dob, email, phone, id_code, edu_language, hs_diploma, eng_cert) VALUES ('$user_id', '$full_name', '$dob', '$email', '$phone', '$id_code', '$edu_language', '$hs_diploma_path', '$eng_cert_path')";
                mysqli_query($mysqli, $sql_insert);
                $sql_insert_2 = "UPDATE universities SET user_id = CONCAT(user_id, '$user_id,') WHERE id = '4'";
                mysqli_query($mysqli, $sql_insert_2);
                echo "<p class='alert alert-success'>Application submitted successfully.</p>";
            } else {
                echo "<p class='alert alert-danger'>Error uploading files.</p>";
            }
        }
    }

// Close database connection
mysqli_close($mysqli);
?>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>