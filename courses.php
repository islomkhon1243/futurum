<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    .card-img-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    .card:hover .card-img-overlay {
        opacity: 1;
    }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1>Courses</h1>
        <br>
        <div class="row">
            <?php
				// Include database connection file
				require_once 'db_connect.php';

				// Fetch all courses data from database
				$sql = "SELECT * FROM courses";
				$result = $mysqli->query($sql);

				// Check if any data found
				if ($result->num_rows > 0) {
					// Loop through each row of data and print it in a card
					while ($row = $result->fetch_assoc()) {
						echo '<div class="col-md-4 mb-3">';
						echo '<a href="course.php?id=' . $row['course_id'] . '">';
						echo '<div class="card">';
						echo '<img src="' . $row['image_url'] . '" class="card-img-top" alt="' . $row['name'] . '" width="300" height="200">';
						echo '<div class="card-img-overlay">';
						echo '<h5 class="card-title text-light">' . $row['name'] . '</h5>';
						echo '<p class="card-text text-light">' . substr($row['description'], 0, 100) . '...</p>';
						echo '</div></div></a></div>';
					}
				} else {
					echo '<div class="alert alert-warning">No courses found.</div>';
				}
			?>
        </div>
    </div>
</body>

</html>