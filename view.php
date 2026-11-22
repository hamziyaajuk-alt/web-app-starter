<?php
session_start();
include "../config/db.php";

// ADMIN ONLY
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// FETCH DOCTORS
$result = $conn->query("SELECT * FROM doctors");

include "../layout/header.php";
include "../layout/sidebar.php";
?>

<div class="container-fluid">

<h3>Doctors List</h3>

<table class="table table-bordered">

<tr class="table-dark text-center">
<th>ID</th>
<th>Name</th>
<th>Specialization</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr class="text-center">
<td><?= $row['id'] ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['specialty'] ?></td>
</tr>
<?php endwhile; ?>

</table>

</div>

<?php include "../layout/footer.php"; ?>