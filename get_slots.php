<?php
include "../config/db.php";

$doctor_id = (int)$_GET['doctor_id'];

$result = $conn->query("
    SELECT * FROM doctor_slots 
    WHERE doctor_id='$doctor_id' 
    AND status='available'
");

if ($result->num_rows == 0) {
    echo "<option>No slots available</option>";
    exit;
}

echo "<option value=''>-- Select Slot --</option>";

while($row = $result->fetch_assoc()) {
    echo "<option value='{$row['id']}'>
        {$row['slot_date']} ({$row['start_time']} - {$row['end_time']})
    </option>";
}