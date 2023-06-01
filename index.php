<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once 'db_connect.php';
// get the user_id of the current user
$user_id = $_SESSION['id'];

?>
<!DOCTYPE html>
<html>

<head>
    <title>Admission</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.2/dist/umd/popper.min.js"></script>
    <script type="text/javascript">
    function loadContent(page) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.querySelector('.content').innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", page, true);
        xhttp.send();
    }
    </script>
</head>

<body>
    <header class="navbar navbar-expand-lg navbar-dark sticky-top bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Futurum Admission</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <?php 
                        $name = $_SESSION['name'];
                        // Check if the session variable "loggedin" is set
                        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                            // User is logged in, show logout button only
                            echo '<div class="row">';
                            echo '<a class="nav-link" href="profile.php">' . $name . '</a>';
                            echo '<div class="col-md-3">';
                            echo '<a class="nav-link" href="logout.php">Logout</a>';
                            echo '</div>';
                            echo '</div>';
                        } else {
                            // User is not logged in, show login button only
                            echo '<a class="nav-link" href="login.php">Login</a>';
                        }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <?php 
                        // Check if the user is an admin
                        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
                            // User is an admin, show the links for Users and Submissions
                            echo '<li class="nav-item"><a class="nav-link" href="#" onclick="loadContent(\'universities_admin.php\')">Universities</a></li>';
                            echo '<li class="nav-item"><a class="nav-link" href="#" onclick="loadContent(\'users.php\')">Users</a></li>';
                            echo '<li class="nav-item"><a class="nav-link" href="#submissions" onclick="loadContent(\'submissions.php\')">Submissions</a></li>';
                        } else {
                            echo '<li class="nav-item"><a class="nav-link" href="#" onclick="loadContent(\'universities.php\')">Universities</a></li>';
                            echo '<li class="nav-item"><a class="nav-link" href="#" onclick="loadContent(\'courses.php\')">Courses</a></li>';
                            echo '<li class="nav-item"><a class="nav-link" href="calendar.php">Calendar</a></li>';
                            echo '<li class="nav-item"><a class="nav-link" href="#" onclick="loadContent(\'application_form.php\')">Application Form Demo</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <div class="col-lg-10 px-md-4">
                <div class="content">
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
                        include 'universities_admin.php';
                    } else {
                        include 'universities.php';
                    }?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>