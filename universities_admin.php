<!DOCTYPE html>
<html>
<head>
	<title>Admin Page</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
    </style>
</head>
<body>

	<div class="container">
        <br>
		<h1>Universities Table</h1>
		<a href="create_universities.php" class="btn btn-primary mb-2">Add New University</a>
        <div class="table-responsive">
        <table class="table table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Description</th>
					<th>Year of Foundation</th>
					<th>Number of Students</th>
					<th>Educating Language</th>
					<th>Scholarship Number</th>
					<th>Admission Requirements</th>
					<th>Application Form URL</th>
					<th>Start Date</th>
					<th>End Date</th>
					<th>Image URL 1</th>
					<th>Image URL 2</th>
					<th>Image URL 3</th>
					<th>User ID</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
                    require_once 'db_connect.php';
					// Retrieve data from universities table
					$sql = "SELECT * FROM universities";
					$result = mysqli_query($mysqli, $sql);

					// Display data in table
					while ($row = mysqli_fetch_assoc($result)) {
						echo "<tr>";
						echo "<td>{$row['id']}</td>";
						echo "<td>{$row['name']}</td>";
						$description = strlen($row['description']) > 100 ? substr($row['description'], 0, 100) . '...' : $row['description'];
						echo "<td>{$description}</td>";
						echo "<td>{$row['year_of_foundation']}</td>";
						echo "<td>{$row['number_of_students']}</td>";
						echo "<td>{$row['educating_language']}</td>";
						echo "<td>{$row['scholarship_number']}</td>";
						$admission_requirements = strlen($row['admission_requirements']) > 100 ? substr($row['admission_requirements'], 0, 100) . '...' : $row['admission_requirements'];
						echo "<td>{$admission_requirements}</td>";
						echo "<td>{$row['application_form_url']}</td>";
						echo "<td>{$row['start_date']}</td>";
						echo "<td>{$row['end_date']}</td>";
						echo "<td>{$row['image_url_1']}</td>";
						echo "<td>{$row['image_url_2']}</td>";
						echo "<td>{$row['image_url_3']}</td>";
						echo "<td>{$row['user_id']}</td>";
						echo "<td>
								<a href='update_universities.php?id={$row['id']}' class='btn btn-sm btn-info'>Edit</a>
								<a href='delete_universities.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this university?\")'>Delete</a>
							</td>";
						echo "</tr>";
					}
				?>
			</tbody>
		</table>
        </div>
	</div>
    	<!-- jQuery and Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
