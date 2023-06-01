<?php
// Include the database connection file
include 'db_connect.php';
session_start();

// Get the course ID from the URL parameter
$course_id = $_GET['course_id'];

// Get the course title and content from the database
$sql = "SELECT title, content, name FROM courses WHERE course_id = $course_id";
$result = mysqli_query($mysqli, $sql);

if (mysqli_num_rows($result) == 1) {
  $row = mysqli_fetch_assoc($result);
  $title = $row['title'];
  $content = $row['content'];

  echo '<head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      </head>';

  
  // Display the navbar
  echo '<nav class="navbar navbar-expand-md bg-dark navbar-dark">
          <div class="container">
            <!-- Back button -->
            <a class="navbar-brand" href="index.php">
              <i class="fas fa-arrow-left"></i> Back
            </a>

            <!-- Brand name -->
            <a class="navbar-brand mx-auto" href="#">
              ' . $row['name'] . '
            </a>

            <!-- Profile and logout links -->
            <div class="collapse navbar-collapse" id="navbarCollapse">
              <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                  <a class="nav-link" href="profile.php">' . $_SESSION['name'] . '</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="logout.php">Logout</a>
                </li>
              </ul>
            </div>
          </div>
        </nav>';

  // Display the course title
  echo '<br><div class="text-center"><h1>' . $title . '</h1></div><br>';
  
  // Display the course content
  $videos = explode(',', $content);
  echo '<div class="container">

  <div class="row">';
    foreach ($videos as $video) {
    echo '<div class="col-md-6 mb-4">
            <div class="card">
                <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="' . $video . '?autoplay=0" allowfullscreen></iframe>
                </div>
                <div class="card-body">
                <h5 class="card-title">Video Title</h5>
                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean blandit euismod neque, vitae tristique velit mollis a. Nullam eget consectetur quam. </p>
                </div>
            </div>
            </div>';
    }
    echo '</div>
    </div>';

  
  // Check if the user has watched all the videos in the course
  $user_id = $_SESSION['id'];
  $sql_check = "SELECT video_checked, finished FROM course_enrollments WHERE user_id = $user_id AND course_id = $course_id";
  $result_check = mysqli_query($mysqli, $sql_check);
  
  if (mysqli_num_rows($result_check) == 1) {
    $row_check = mysqli_fetch_assoc($result_check);
    $video_checked = $row_check['video_checked'];
    $finished = $row_check['finished'];
    
    // Check if the user has already marked the video as watched
    if ($video_checked) {
        echo '<div class="d-flex justify-content-center">
        <button class="btn btn-success" disabled>Watched</button>
      </div><br>';
        } else {
        echo '<form method="post">';
        echo '<input type="hidden" name="user_id" value="' . $user_id . '">';
        echo '<input type="hidden" name="course_id" value="' . $course_id . '">';
        echo '<div class="d-flex justify-content-center"><button type="submit" name="watched" class="btn btn-success">Mark as watched</button></div><br>';
        echo '</form>';
        }
        
        } else {
        // If the user is not enrolled in the course, display an "Enroll" button
        echo '<form method="post">';
        echo '<input type="hidden" name="user_id" value="' . $user_id . '">';
        echo '<input type="hidden" name="course_id" value="' . $course_id . '">';
        echo '<button type="submit" name="enroll" class="btn btn-primary btn-block">Enroll in course</button>';
        echo '</form>';
        }
        }
        
        // Handle enrollments
        if (isset($_POST['enroll'])) {
        $user_id = $_POST['user_id'];
        $course_id = $_POST['course_id'];
        
        // Add the enrollment to the database
        $sql_enroll = "INSERT INTO course_enrollments (user_id, course_id) VALUES ($user_id, $course_id)";
        mysqli_query($mysqli, $sql_enroll);
        
        // Redirect to the course page
        header("Location: course.php?course_id=$course_id");
        exit();
        }
        
        // Handle video watched
        if (isset($_POST['watched'])) {
        $user_id = $_POST['user_id'];
        $course_id = $_POST['course_id'];
        
        // Update the video_checked field in the course_enrollments table
        $sql_watched = "UPDATE course_enrollments SET video_checked = 1 WHERE user_id = $user_id AND course_id = $course_id";
        mysqli_query($mysqli, $sql_watched);
        
        }
        
        // Handle course finished
        if (isset($_POST['finish'])) {
        $user_id = $_POST['user_id'];
        $course_id = $_POST['course_id'];
        
        // Update the finished field in the course_enrollments table
        $sql_finish = "UPDATE course_enrollments SET finished = 1 WHERE user_id = $user_id AND course_id = $course_id";
        mysqli_query($mysqli, $sql_finish);
        
        }
    // If the user has watched all the videos, enable the "Finish course" button
    if ($video_checked && !$finished) {
        echo '<form method="post">';
        echo '<input type="hidden" name="user_id" value="' . $user_id . '">';
        echo '<input type="hidden" name="course_id" value="' . $course_id . '">';
        echo '<button type="submit" name="finish" class="btn btn-primary btn-block">Finish course</button>';
        echo '</form>';
      } elseif ($finished) {
        echo '<button class="btn btn-primary btn-block" disabled>Finished</button>';
      } else {
        echo '<button class="btn btn-primary btn-block" disabled>Finish course</button>';
      }
?>