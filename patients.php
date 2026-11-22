<?php
session_start();
include "../config/db.php";

// CHECK LOGIN
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'doctor') {
    header("Location: /OPMS/index.php");
    exit();
}

// SEARCH & FILTER
$search = $_GET['search'] ?? '';
$gender = $_GET['gender'] ?? '';

$query = "SELECT * FROM patients WHERE 1";

// Search by name
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " AND name LIKE '%$search%'";
}

// Filter by gender
if (!empty($gender)) {
    $gender = $conn->real_escape_string($gender);
    $query .= " AND gender = '$gender'";
}

$query .= " ORDER BY id DESC";

$patients = $conn->query($query);

include "../layout/header.php";
include "../layout/sidebar.php";
?>

<div class="main">

<h3 class="mb-3">🧑 Patients</h3>

<!-- SEARCH & FILTER -->
<div class="card p-3 shadow-sm mb-3">

<form method="GET" class="row g-2">

    <div class="col-md-4">
        <input type="text" name="search" class="form-control"
               placeholder="Search by name..."
               value="<?= htmlspecialchars($search) ?>">
    </div>

    <div class="col-md-3">
        <select name="gender" class="form-select">
            <option value="">All Gender</option>
            <option value="Male" <?= ($gender=='Male')?'selected':'' ?>>Male</option>
            <option value="Female" <?= ($gender=='Female')?'selected':'' ?>>Female</option>
        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary w-100">
            <i class="fa fa-search"></i> Search
        </button>
    </div>

    <div class="col-md-2">
        <a href="patients.php" class="btn btn-secondary w-100">
            Reset
        </a>
    </div>

</form>

</div>

<!-- TABLE -->
<div class="card p-3 shadow-sm">

<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>

    <?php if($patients && $patients->num_rows > 0): ?>
        <?php while($row = $patients->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['age'] ?></td>
            <td><?= $row['gender'] ?></td>
            <td><?= $row['created_at'] ?></td>
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

<?php include "../layout/footer.php"; ?>