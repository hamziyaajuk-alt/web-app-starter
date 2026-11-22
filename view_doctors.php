<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: /OPMS/index.php");
    exit();
}

/* JOIN WITH USERS */
$result = $conn->query("
    SELECT d.*, u.username 
    FROM doctors d
    LEFT JOIN users u ON d.user_id = u.id
    ORDER BY d.id DESC
");
?>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar_admin.php"; ?>

<div class="main">

<h3 class="mb-4">👨‍⚕️ Doctors List</h3>

<div class="card p-3">

<table class="table table-bordered table-hover">

<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Username</th>
    <th>Specialization</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php if($result && $result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>

        <td><?= htmlspecialchars($row['name']) ?></td>

        <td>
            <?= $row['username'] ?? '<span class="text-danger">Not linked</span>' ?>
        </td>

        <td><?= htmlspecialchars($row['specialization'] ?? '-') ?></td>

        <td>
            <a href="edit_doctor.php?id=<?= $row['id'] ?>" 
               class="btn btn-sm btn-warning">Edit</a>

            <a href="delete_doctor.php?id=<?= $row['id'] ?>" 
               class="btn btn-sm btn-danger"
               onclick="return confirm('Delete this doctor?')">
               Delete
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="5" class="text-center">No doctors found</td>
    </tr>
<?php endif; ?>

</tbody>

</table>

</div>

</div>