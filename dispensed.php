<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'pharmacist') {
    header("Location: /OPMS/index.php");
    exit();
}

$data = $conn->query("
    SELECT p.*, pa.name AS patient, d.name AS doctor
    FROM prescriptions p
    JOIN patients pa ON p.patient_id = pa.id
    JOIN doctors d ON p.doctor_id = d.id
    WHERE p.status = 'dispensed'
    ORDER BY p.created_at DESC
");

include "../layout/header.php";
include "../layout/sidebar.php";
?>

<div class="main">

<h3 class="mb-3">✔ Dispensed Prescriptions</h3>

<div class="card p-3 shadow-sm">

<table class="table table-striped">

<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Patient</th>
    <th>Doctor</th>
    <th>Medicine</th>
    <th>Date</th>
</tr>
</thead>

<tbody>

<?php while($row = $data->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['patient'] ?></td>
    <td><?= $row['doctor'] ?></td>
    <td><?= nl2br($row['medicine']) ?></td>
    <td><?= $row['created_at'] ?></td>
</tr>
<?php endwhile; ?>

</tbody>

</table>

</div>

</div>

<?php include "../layout/footer.php"; ?>