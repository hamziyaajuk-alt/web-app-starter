<?php
include "../config/db.php";

$doctors = $conn->query("SELECT * FROM doctors");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = $_POST['doctor_id'];
    $day = $_POST['day'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];

    $sql = "INSERT INTO schedules (doctor_id, day, start_time, end_time)
            VALUES ('$doctor_id', '$day', '$start', '$end')";

    if ($conn->query($sql)) {
        echo "Schedule added!";
    }
}
?>

<h2>Add Schedule</h2>

<form method="POST">
    Doctor:
    <select name="doctor_id">
        <?php while($doc = $doctors->fetch_assoc()): ?>
            <option value="<?= $doc['id'] ?>">
                <?= $doc['name'] ?>
            </option>
        <?php endwhile; ?>
    </select><br>

    Day:
    <select name="day">
        <option>Monday</option>
        <option>Tuesday</option>
        <option>Wednesday</option>
        <option>Thursday</option>
        <option>Friday</option>
    </select><br>

    Start Time: <input type="time" name="start_time"><br>
    End Time: <input type="time" name="end_time"><br>

    <button type="submit">Add Schedule</button>
</form>