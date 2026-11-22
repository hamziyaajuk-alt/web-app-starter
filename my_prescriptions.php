<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient') {
    header("Location: /OPMS/index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$p = $conn->query("SELECT * FROM patients WHERE user_id=$user_id")->fetch_assoc();
$patient_id = $p['id'];

$data = $conn->query("
SELECT p.*, d.name AS doctor 
FROM prescriptions p
JOIN doctors d ON p.doctor_id=d.id
WHERE p.patient_id=$patient_id
ORDER BY p.id DESC
");

include "../layout/header.php";
include "../layout/sidebar.php";
?>

<div class="main">

<h3>📄 My Prescriptions</h3>

<table class="table mt-3">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>Doctor</th>
<th>Diagnosis</th>
<th>Medicine</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php while($row = $data->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['doctor'] ?></td>
<td><?= $row['diagnosis'] ?></td>
<td><?= nl2br($row['medicine']) ?></td>

<td>
<?php if($row['status']=='dispensed'): ?>
<span class="badge bg-success">Ready</span>
<?php else: ?>
<span class="badge bg-warning">Pending</span>
<?php endif; ?>
</td>

<td>
<a href="../doctor/print.php?id=<?= $row['id'] ?>" target="_blank"
class="btn btn-sm btn-primary">
Print
</a>
</td>

</tr>
<?php endwhile; ?>

</tbody>
</table>

</div>