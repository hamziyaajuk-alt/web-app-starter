<?php
include "../config/db.php";

$sql = "SELECT s.*, d.name 
        FROM schedules s
        JOIN doctors d ON s.doctor_id = d.id";

$result = $conn->query($sql);
?>

<h2>Doctor Schedule</h2>

<table border="1">
<tr>
    <th>Doctor</th>
    <th>Day</th>
    <th>Start</th>
    <th>End</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['name'] ?></td>
    <td><?= $row['day'] ?></td>
    <td><?= $row['start_time'] ?></td>
    <td><?= $row['end_time'] ?></td>
</tr>
<?php endwhile; ?>

</table>