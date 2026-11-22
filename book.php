<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "../config/db.php";

// CHECK LOGIN
if (!isset($_SESSION['user'])) {
    header("Location: /OPMS/index.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// GET PATIENT
$getPatient = $conn->query("SELECT * FROM patients WHERE user_id = $user_id");
$patient = $getPatient->fetch_assoc();

if (!$patient) {
    die("❌ Patient not linked!");
}

$patient_id = $patient['id'];

// SMART DOCTOR (LEAST BUSY)
$smartDoctorQuery = $conn->query("
    SELECT d.id, d.name, COUNT(a.id) as total
    FROM doctors d
    LEFT JOIN appointments a ON d.id = a.doctor_id
    GROUP BY d.id
    ORDER BY total ASC
    LIMIT 1
");

$smartDoctor = $smartDoctorQuery->fetch_assoc();

// FETCH ALL DOCTORS
$doctors = $conn->query("SELECT * FROM doctors");

// SAVE APPOINTMENT
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $doctor_id = (int) $_POST['doctor_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $conn->query("
        INSERT INTO appointments (patient_id, doctor_id, date, time, status)
        VALUES ($patient_id, $doctor_id, '$date', '$time', 'pending')
    ");

    $msg = "✅ Appointment booked (Pending approval)";
}

include "../layout/header.php";
include "../layout/sidebar.php";
?>

<div class="container">

<h3 class="mb-4">📅 Book Appointment</h3>

<?php if(isset($msg)): ?>
<div class="alert alert-success"><?= $msg ?></div>
<?php endif; ?>

<form method="POST">

<!-- SMART DOCTOR INFO -->
<div class="alert alert-info">
🤖 Recommended Doctor: <strong><?= htmlspecialchars($smartDoctor['name']) ?></strong>
</div>

<!-- DOCTOR SELECT -->
<div class="mb-3">
<label>Select Doctor</label>
<select name="doctor_id" id="doctorSelect" class="form-control" required>

<?php while($doc = $doctors->fetch_assoc()): ?>
<option value="<?= $doc['id'] ?>"
<?= ($doc['id'] == $smartDoctor['id']) ? 'selected' : '' ?>>

<?= htmlspecialchars($doc['name']) ?> (<?= $doc['specialty'] ?? 'General' ?>)

</option>
<?php endwhile; ?>

</select>
</div>

<!-- DATE -->
<div class="mb-3">
<label>Date</label>
<input type="date" name="date" class="form-control" required>
</div>

<!-- TIME -->
<div class="mb-3">
<label>Time</label>
<input type="time" name="time" class="form-control" required>
</div>

<button class="btn btn-primary">Book Appointment</button>

</form>

</div>

<?php include "../layout/footer.php"; ?>