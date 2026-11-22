<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'doctor') {
    header("Location: /OPMS/index.php");
    exit();
}

$doctor_user_id = $_SESSION['user']['id'];

// GET doctor_id
$getDoctor = $conn->query("SELECT * FROM doctors WHERE user_id='$doctor_user_id'");
$doctor = $getDoctor->fetch_assoc();
$doctor_id = $doctor['id'];

// ADD SLOT
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $date = $_POST['date'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];

    $conn->query("
        INSERT INTO doctor_slots (doctor_id, slot_date, start_time, end_time, status)
        VALUES ('$doctor_id','$date','$start','$end','available')
    ");

    $msg = "✅ Slot added!";
}

// GET ALL SLOTS
$slots = $conn->query("
    SELECT * FROM doctor_slots 
    WHERE doctor_id='$doctor_id'
    ORDER BY slot_date DESC, start_time ASC
");

include "../layout/header.php";
include "../layout/sidebar.php";
?>

<div class="main">

<h3 class="mb-4">🕒 Manage Time Slots</h3>

<?php if(isset($msg)): ?>
<div class="alert alert-success"><?= $msg ?></div>
<?php endif; ?>

<!-- ADD SLOT FORM -->
<div class="card p-4 mb-4 shadow-sm">

<form method="POST">

<div class="row">

<div class="col-md-4">
<label>Date</label>
<input type="date" name="date" class="form-control" required>
</div>

<div class="col-md-4">
<label>Start Time</label>
<input type="time" name="start_time" class="form-control" required>
</div>

<div class="col-md-4">
<label>End Time</label>
<input type="time" name="end_time" class="form-control" required>
</div>

</div>

<button class="btn btn-success mt-3">➕ Add Slot</button>

</form>

</div>

<!-- SLOT LIST -->
<div class="card p-3 shadow-sm">

<h5 class="mb-3">📋 Your Slots</h5>

<table class="table table-bordered table-hover">

<thead class="table-dark">
<tr>
<th>Date</th>
<th>Start</th>
<th>End</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php if($slots->num_rows > 0): ?>
<?php while($s = $slots->fetch_assoc()): ?>
<tr>
<td><?= $s['slot_date'] ?></td>
<td><?= $s['start_time'] ?></td>
<td><?= $s['end_time'] ?></td>
<td>
<?php if($s['status'] == 'available'): ?>
<span class="badge bg-success">Available</span>
<?php else: ?>
<span class="badge bg-danger">Booked</span>
<?php endif; ?>
</td>

<td>
    <?php if($s['status'] == 'available'): ?>

        <a href="edit_slot.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-warning">
            ✏ Edit
        </a>

        <a href="delete_slot.php?id=<?= $s['id'] ?>" 
           class="btn btn-sm btn-danger"
           onclick="return confirm('Delete this slot?')">
           🗑 Delete
        </a>

    <?php else: ?>
        <span class="text-muted">Locked</span>
    <?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="4" class="text-center">No slots added yet</td>
</tr>
<?php endif; ?>

</tbody>

</table>

</div>

</div>

<?php include "../layout/footer.php"; ?>
```
