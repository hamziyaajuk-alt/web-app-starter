<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'pharmacist') {
    header("Location: /OPMS/index.php");
    exit();
}

// SHOW ONLY SENT PRESCRIPTIONS
$result = $conn->query("
    SELECT p.*, pt.name as patient_name
    FROM prescriptions p
    JOIN patients pt ON p.patient_id = pt.id
    WHERE p.status = 'sent'
    ORDER BY p.id DESC
");

include "../layout/header.php";
include "../layout/sidebar.php";
?>

<div class="main">

<h3 class="mb-4">💊 Prescriptions to Dispense</h3>

<div class="card p-3">

<table class="table table-bordered">

<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Patient</th>
    <th>Diagnosis</th>
    <th>Medicine</th>
    <th>Date</th>
    <th>Time</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php while($row = $result->fetch_assoc()): ?>

<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['patient_name'] ?></td>
    <td><?= $row['diagnosis'] ?></td>
    <td><?= nl2br($row['medicine']) ?></td>

    <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
    <td><?= date('H:i:s', strtotime($row['created_at'])) ?></td>

    <td>
<a href="dispensed.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">
    Dispense
</a>
    </td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>
</div>

<?php include "../layout/footer.php"; ?>