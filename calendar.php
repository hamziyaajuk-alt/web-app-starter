<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: /OPMS/index.php");
    exit();
}

// FETCH APPOINTMENTS
$events = [];

$res = $conn->query("
SELECT a.*, d.name AS doctor 
FROM appointments a
JOIN doctors d ON a.doctor_id = d.id
");

while($row = $res->fetch_assoc()){
    $events[] = [
        "title" => $row['doctor'],
        "start" => $row['date'] . "T" . $row['time']
    ];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Calendar Booking</title>

<!-- FULLCALENDAR -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<style>
body { font-family: Arial; }
#calendar { max-width: 900px; margin: 40px auto; }
</style>

</head>

<body>

<h2 style="text-align:center;">📅 Appointment Calendar</h2>

<div id="calendar"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',

        events: <?= json_encode($events) ?>,

        dateClick: function(info) {
            let date = info.dateStr;

            if(confirm("Book appointment on " + date + "?")){
                window.location.href = "book.php?date=" + date;
            }
        }
    });

    calendar.render();
});
</script>

</body>
</html>