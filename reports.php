<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: /OPMS/index.php");
    exit();
}

$type = $_GET['type'] ?? 'all';

/* DATA */
$patients = $conn->query("SELECT * FROM patients");

$doctors = $conn->query("SELECT * FROM doctors");

$appointments = $conn->query("
    SELECT a.*, pt.name AS patient, d.name AS doctor
    FROM appointments a
    LEFT JOIN patients pt ON a.patient_id = pt.id
    LEFT JOIN doctors d ON a.doctor_id = d.id
");

$prescriptions = $conn->query("
    SELECT p.*, pt.name AS patient, d.name AS doctor
    FROM prescriptions p
    LEFT JOIN patients pt ON p.patient_id = pt.id
    LEFT JOIN doctors d ON p.doctor_id = d.id
");
?>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="main">

<h3 class="mb-4">📊 Reports</h3>

<!-- FILTER -->
<form method="GET" class="mb-3 d-flex gap-2">
    <select name="type" class="form-select" style="max-width:250px;">
        <option value="all" <?= $type=='all'?'selected':'' ?>>All</option>
        <option value="patients" <?= $type=='patients'?'selected':'' ?>>Patients</option>
        <option value="doctors" <?= $type=='doctors'?'selected':'' ?>>Doctors</option>
        <option value="appointments" <?= $type=='appointments'?'selected':'' ?>>Appointments</option>
        <option value="prescriptions" <?= $type=='prescriptions'?'selected':'' ?>>Prescriptions</option>
    </select>

    <button class="btn btn-primary">Filter</button>

    <button type="button" onclick="printSection()" class="btn btn-success">
        🖨 Print
    </button>
</form>

<!-- PRINT AREA -->
<div id="printArea">

<!-- ================= PATIENTS ================= -->
<?php if($type=='patients' || $type=='all'): ?>
<div class="card p-3 mb-3">
<h5>👤 Patients</h5>

<table class="table table-bordered">
<tr>
<th>ID</th>
<th>Name</th>
<th>Age</th>
<th>Gender</th>
<th>Phone</th>
<th>Address</th>
<th>Email</th>
</tr>

<?php while($row = $patients->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['age'] ?></td>
<td><?= $row['gender'] ?></td>
<td><?= $row['phone'] ?? '' ?></td>
<td><?= $row['address'] ?? '' ?></td>
<td><?= htmlspecialchars($row['email']) ?></td>
</tr>
<?php endwhile; ?>

</table>
</div>
<?php endif; ?>

<!-- ================= DOCTORS ================= -->
<?php if($type=='doctors' || $type=='all'): ?>
<div class="card p-3 mb-3">
<h5>🩺 Doctors</h5>

<table class="table table-bordered">
<tr>
<th>ID</th>
<th>Name</th>
<th>Specialization</th>
</tr>

<?php while($row = $doctors->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['specialization'] ?? '' ?></td>
</tr>
<?php endwhile; ?>

</table>
</div>
<?php endif; ?>

<!-- ================= APPOINTMENTS ================= -->
<?php if($type=='appointments' || $type=='all'): ?>
<div class="card p-3 mb-3">
<h5>📅 Appointments</h5>

<table class="table table-bordered">
<tr>
<th>ID</th>
<th>Patient</th>
<th>Doctor</th>
<th>Status</th>
<th>Date</th>
</tr>

<?php while($row = $appointments->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['patient'] ?? '' ?></td>
<td><?= $row['doctor'] ?? '' ?></td>
<td><?= $row['status'] ?></td>
<td><?= $row['created_at'] ?? '' ?></td>
</tr>
<?php endwhile; ?>

</table>
</div>
<?php endif; ?>

<!-- ================= PRESCRIPTIONS ================= -->
<?php if($type=='prescriptions' || $type=='all'): ?>
<div class="card p-3 mb-3">
<h5>💊 Prescriptions</h5>

<table class="table table-bordered">
<tr>
<th>ID</th>
<th>Patient</th>
<th>Doctor</th>
<th>Diagnosis</th>
<th>Medicine</th>
<th>Status</th>
<th>Date</th>
</tr>

<?php while($row = $prescriptions->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['patient'] ?? '' ?></td>
<td><?= $row['doctor'] ?? '' ?></td>
<td><?= $row['diagnosis'] ?></td>
<td style="white-space: pre-line;"><?= $row['medicine'] ?></td>
<td><?= $row['status'] ?></td>
<td><?= $row['created_at'] ?? '' ?></td>
</tr>
<?php endwhile; ?>

</table>
</div>
<?php endif; ?>

</div>

</div>

<!-- PRINT SCRIPT -->
<script>
function printSection() {
    var content = document.getElementById("printArea").innerHTML;
    var win = window.open('', '', 'height=700,width=900');
    win.document.write('<html><head><title>Report</title>');
    win.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
    win.document.write('</head><body>');
    win.document.write(content);
    win.document.write('</body></html>');
    win.document.close();
    win.print();
}
</script>

</body>
</html>