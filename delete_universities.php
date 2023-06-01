<?php
    require_once 'db_connect.php';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Delete the university from the universities table
        $sql = "DELETE FROM universities WHERE id = $id";
        if (mysqli_query($mysqli, $sql)) {
            echo "University deleted successfully.";
        } else {
            echo "Error deleting university: " . mysqli_error($mysqli);
        }

        mysqli_close($mysqli);
    }
?>
