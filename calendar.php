<?php
// Include the database connection file
require_once 'db_connect.php';

// Query the universities table for admission start and end dates
$sql = "SELECT name, start_date, end_date FROM universities";
$result = mysqli_query($mysqli, $sql);

// Define an array of colors for events
$colors = array(
    '#007bff', '#6610f2', '#6f42c1', '#e83e8c', '#dc3545', '#fd7e14', '#ffc107', '#28a745', '#20c997', '#17a2b8'
);

// Create an array of events for the calendar
$events = array();
$i = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $name = $row['name'];
    $start_date = date('Y-m-d', strtotime($row['start_date']));
    $end_date = date('Y-m-d', strtotime($row['end_date']));
    $color = $colors[$i % count($colors)]; // Get a color from the array based on the index
    $events[] = array(
        'title' => $name,
        'start' => $start_date,
        'end' => $end_date,
        'color' => $color
    );
    $i++;
}

// Close the database connection
mysqli_close($mysqli);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <title>University Admission Dates</title>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css' />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css' />
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js'></script>
    <script>
    $(document).ready(function() {
        // Initialize the calendar
        $('#calendar').fullCalendar({
            themeSystem: 'bootstrap4',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: <?php echo json_encode($events); ?>,
            eventRender: function(event, element) {
                var tooltip = event.title + ' - ' + event.start.format('MMM D, YYYY') + ' to ' +
                    event.end.format('MMM D, YYYY');
                element.attr('title', tooltip);
            }
        });
    });
    </script>
    <style>
    .fc-event {
        color: #fff;
        border-color: #fff;
    }
    </style>
</head>

<body>
    <div class='container'>
        <br>
        <h1 class="text-center">University Admission Dates</h1>
        <br>
        <button onclick="history.back()" class="btn btn-primary mb-3"><i class="fas fa-arrow-left"></i> Back</button>
        <div id='calendar'></div>
        <br>
    </div>
</body>

</html>