<!DOCTYPE html>
<html>

<head>
    <title>Submissions</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    .table-wrapper {
        overflow-x: auto;
    }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Submissions</h1>
        <form method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-group row">
                <label for="university" class="col-sm-2 col-form-label">University:</label>
                <div class="col-sm-10">
                <select class="form-control" id="university" name="university">
                    <option value="">All</option>
                    <option value="International Information Technology University">IITU</option>
                    <option value="Kazakh-British Technical University">KBTU</option>
                    <option value="Satbayev University">Satbayev</option>
                    <option value="L.N.Gumilyov Eurasian National University">Gumilev</option>
                    <option value="Al-Farabi Kazakh National University">Al-Farabi</option>
                </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
        <div class="table-wrapper">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Date of Birth</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>ID Code</th>
                        <th>Educating Language</th>
                        <th>High School Diploma</th>
                        <th>English Certificate</th>
                        <th>University</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <?php
                    require_once "db_connect.php";
                // SQL query to retrieve data from all applications table and universities table
                $sql = "SELECT 
                i.user_id, 
                i.full_name, 
                i.dob, 
                i.email, 
                i.phone, 
                i.id_code, 
                i.edu_language, 
                i.hs_diploma, 
                i.eng_cert, 
                u.name AS name,
                NULL AS status
            FROM 
                iitu_applications AS i 
                JOIN universities AS u ON FIND_IN_SET(i.user_id, u.user_id) 
                LEFT JOIN kbtu_applications AS k ON k.user_id = i.user_id
                LEFT JOIN satbayev_applications AS s ON s.user_id = i.user_id
                LEFT JOIN gumilev_applications AS g ON g.user_id = i.user_id
                LEFT JOIN alfarabi_applications AS a ON a.user_id = i.user_id";

                // Check if university filter is selected
                if (isset($_GET['university']) && !empty($_GET['university'])) {
                $university = $_GET['university'];
                // Add WHERE clause to filter by university name
                $sql .= " WHERE u.name = '$university'";
                }

                $result = $mysqli->query($sql);

                // Check if there are any rows returned
                if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["user_id"] . "</td><td>" . $row["full_name"] . "</td><td>" . $row["dob"] . "</td>
                    <td>" . $row["email"] . "</td><td>" . $row["phone"] . "</td><td>" . $row["id_code"] . "</td>
                    <td>" . $row["edu_language"] . "</td><td><a href='" . $row["hs_diploma"] . "' download>" . $row["hs_diploma"] . "</a></td>
                    <td><a href='" . $row["eng_cert"] . "' download>" . $row["eng_cert"] . "</a></td><td>" . $row["name"] . "</td></tr>";
                }
                } else {
                echo "0 results";
                }

                $mysqli->close();
                ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>