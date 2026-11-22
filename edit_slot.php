```php
<?php
session_start();
include "../config/db.php";

$id = (int) $_GET['id'];

// GET SLOT
$slot = $conn->query("SELECT * FROM doctor_slots WHERE id='$id'")->fetch_assoc();

if (!$slot) {
    die("Slot not found");
}

// UPDATE SLOT
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $date = $_POST['date'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];

    $conn->query("
        UPDATE doctor_slots 
        SET slot_date='$date', start_time='$start', end_time='$end'
        WHERE id='$id'
    ");

    header("Location: add_slots.php");
    exit();
}

include "../layout/header.php";
include "../layout/sidebar.php";
?>

<div class="main">

<h3>✏ Edit Slot</h3>

<form method="POST" class="card p-3">

<label>Date</label>
<input type="date" name="date" value="<?= $slot['slot_date'] ?>" class="form-control mb-2">

<label>Start Time</label>
<input type="time" name="start_time" value="<?= $slot['start_time'] ?>" class="form-control mb-2">

<label>End Time</label>
<input type="time" name="end_time" value="<?= $slot['end_time'] ?>" class="form-control mb-3">

<button class="btn btn-primary">Update Slot</button>

</form>

</div>
```
