<?php

session_start();
// Include the database connection file
include('db_connect.php');

// Check if the university ID is set in the URL
if (isset($_GET['id'])) {
  // Sanitize the university ID
  $id = mysqli_real_escape_string($mysqli, $_GET['id']);

  // Query the database for the university with the given ID
  $query = "SELECT * FROM universities WHERE id = '$id'";
  $result = mysqli_query($mysqli, $query);

  // Check if a university was found
  if (mysqli_num_rows($result) == 1) {
    // Fetch the university data
    $row = mysqli_fetch_assoc($result);

    // Set the page title to the university name
    $title = $row['name'];
  } else {
    // If no university was found, redirect to the universities page
    header('Location: universities.php');
    exit();
  }
} else {
  // If the university ID is not set, redirect to the universities page
  header('Location: index.php');
  exit();
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
    <nav class="navbar navbar-expand-md bg-dark navbar-dark sticky-top">
        <div class="container">
            <!-- Back button -->
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-arrow-left"></i> Back
            </a>

            <!-- Brand name -->
            <a class="navbar-brand mx-auto text-center" href="#">
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

    <!-- Image carousel -->
    <div id="carousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carousel" data-slide-to="0" class="active"></li>
            <li data-target="#carousel" data-slide-to="1"></li>
            <li data-target="#carousel" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="<?php echo $row['image_url_1']; ?>" class="d-block w-100" alt="Image 1">
            </div>
            <div class="carousel-item">
                <img src="<?php echo $row['image_url_2']; ?>" class="d-block w-100" alt="Image 2">
            </div>
            <div class="carousel-item">
                <img src="<?php echo $row['image_url_3']; ?>" class="d-block w-100" alt="Image 3">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <!-- University information -->
    <div class="container my-5">
        <h2><?php echo $row['name']; ?></h2>
        <p><?php echo $row['description']; ?></p>
        <h4>Admission Requirements</h4>
        <p><?php echo $row['admission_requirements']; ?></p>

        <?php 
            // Get the current date
            $current_date = date("Y-m-d");

            // Check if today's date is between the start and end dates
            if ($current_date >= $row['start_date'] && $current_date <= $row['end_date']) {
                // Enable the "Apply Now" button
                echo '<a href="'.$row['application_form_url'].'" class="btn btn-primary">Apply Now</a>';
            } else {
                // Disable the "Apply Now" button
                echo '<button class="btn btn-primary" disabled>Applications Closed</button>';
            }
        ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>