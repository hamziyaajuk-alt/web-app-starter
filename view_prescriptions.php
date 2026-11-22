<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'doctor') {
    header("Location: /OPMS/index.php");
    exit();
}

$doctor_user_id = $_SESSION['user']['id'];

// GET doctor_id
$getDoctor = $conn->query("SELECT * FROM doctors WHERE user_id = '$doctor_user_id'");
$doctor = $getDoctor->fetch_assoc();
$doctor_id = $doctor['id'];

// FETCH prescriptions
$result = $conn->query("
    SELECT p.*, pt.name as patient_name
    FROM prescriptions p
    JOIN patients pt ON p.patient_id = pt.id
    WHERE p.doctor_id = '$doctor_id'
    ORDER BY p.id DESC
");

include "../layout/header.php";
include "../layout/sidebar.php";
?>

<div class="main">

<h3 class="mb-4">💊 My Prescriptions</h3>

<div class="card p-3">

<table class="table table-bordered">

<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Patient</th>
    <th>Diagnosis</th>
    <th>Medicine</th>
    <th>Status</th>
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

    <!-- STATUS -->
    <td>
        <?php if($row['status'] == 'pending'): ?>
            <span class="badge bg-warning">Pending</span>
        <?php elseif($row['status'] == 'sent'): ?>
            <span class="badge bg-primary">Sent</span>
        <?php else: ?>
            <span class="badge bg-success">Dispensed</span>
        <?php endif; ?>
    </td>

    <!-- DATE -->
    <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>

    <!-- TIME -->
    <td><?= date('H:i:s', strtotime($row['created_at'])) ?></td>

    <!-- ACTION -->
    <td>

        <?php if($row['status'] == 'pending'): ?>
            <a href="send_prescription.php?id=<?= $row['id'] ?>" 
               class="btn btn-sm btn-success">Send</a>
        <?php endif; ?>

        <button onclick="window.print()" class="btn btn-sm btn-primary">
            Print
        </button>

    </td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>
</div>

<?php include "../layout/footer.php"; ?>