<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: /OPMS/index.php");
    exit();
}

$result = $conn->query("SELECT * FROM patients");
?>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="main">

<h3 class="mb-4">👤 Patients List</h3>

<div class="card p-3">

<!-- 🔍 SEARCH BOX -->
<input type="text" id="searchInput" class="form-control mb-3" placeholder="Search patient...">

<table class="table table-bordered table-hover" id="patientTable">

<thead class="table-dark">
<tr>
<th>ID</th>
<th>Name</th>
<th>Age</th>
<th>Gender</th>
<th>Phone</th>
<th>Address</th>
<th>Email</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php if($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td><?= $row['age'] ?? '-' ?></td>
    <td><?= $row['gender'] ?? '-' ?></td>
    <td><?= $row['phone'] ?? '-' ?></td>
    <td><?= $row['address'] ?? '-' ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    
        <td>
            <a href="edit_patient.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                ✏️ Edit
            </a>

            <a href="delete_patient.php?id=<?= $row['id'] ?>"
               class="btn btn-sm btn-danger"
               onclick="return confirm('Delete this patient?')">
               🗑 Delete
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="5" class="text-center">No patients found</td>
    </tr>
<?php endif; ?>

</tbody>

</table>

</div>

</div>

<!-- 🔥 SEARCH SCRIPT -->
<script>
document.getElementById("searchInput").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#patientTable tbody tr");

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});
</script>

</body>
</html>