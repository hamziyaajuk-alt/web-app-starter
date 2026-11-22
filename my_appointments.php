<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient') {
    header("Location: /OPMS/index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// GET PATIENT
$p = $conn->query("SELECT * FROM patients WHERE user_id=$user_id")->fetch_assoc();
$patient_id = $p['id'];

// CANCEL APPOINTMENT
if (isset($_GET['cancel'])) {
    $id = (int)$_GET['cancel'];

    $conn->query("
        UPDATE appointments 
        SET status='cancelled' 
        WHERE id=$id AND patient_id=$patient_id
    ");

    header("Location: my_appointments.php");
    exit();
}

// FETCH APPOINTMENTS
$data = $conn->query("
    SELECT a.*, d.name AS doctor
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.id
    WHERE a.patient_id = $patient_id
    ORDER BY a.date DESC, a.time DESC
");

include "../layout/header.php";
include "../layout/sidebar.php";
?>

<div class="main">

<h3>📅 My Appointments</h3>

<div class="card p-3 mt-3">

<table class="table table-striped">

<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Doctor</th>
    <th>Date</th>
    <th>Time</th>
    <th>Reason</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php while($row = $data->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['doctor']) ?></td>
    <td><?= $row['date'] ?></td>
    <td><?= $row['time'] ?></td>
    <td><?= htmlspecialchars($row['reason']) ?></td>

    <td>
        <?php if($row['status']=='pending'): ?>
            <span class="badge bg-warning text-dark">Pending</span>
        <?php elseif($row['status']=='approved'): ?>
            <span class="badge bg-success">Approved</span>
        <?php elseif($row['status']=='rejected'): ?>
            <span class="badge bg-danger">Rejected</span>
        <?php elseif($row['status']=='cancelled'): ?>
            <span class="badge bg-secondary">Cancelled</span>
        <?php endif; ?>
    </td>

    <td>
        <?php if($row['status']=='pending'): ?>
            <a href="?cancel=<?= $row['id'] ?>" 
               class="btn btn-sm btn-danger"
               onclick="return confirm('Cancel this appointment?')">
               Cancel
            </a>
        <?php else: ?>
            <span class="text-muted">—</span>
        <?php endif; ?>
    </td>

</tr>
<?php endwhile; ?>

</tbody>

</table>

</div>

</div>