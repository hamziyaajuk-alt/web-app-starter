<?php
session_start();
include "../config/db.php";

if ($_SESSION['user']['role'] != 'admin') {
    header("Location: /OPMS/index.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = (int) $_GET['id'];

// FETCH DOCTOR
$doctor = $conn->query("SELECT * FROM doctors WHERE id=$id")->fetch_assoc();

if (!$doctor) {
    die("Doctor not found");
}

// UPDATE
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $specialization = $_POST['specialization'];

    $conn->query("
        UPDATE doctors 
        SET name='$name', specialization='$specialization' 
        WHERE id=$id
    ");

    header("Location: view_doctors.php");
}
?>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="main">

<h3 class="mb-4">✏️ Edit Doctor</h3>

<div class="card p-4">

<form method="POST">

<div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" class="form-control"
           value="<?= htmlspecialchars($doctor['name']) ?>" required>
</div>

<div class="mb-3">
    <label>Specialization</label>
    <input type="text" name="specialization" class="form-control"
           value="<?= htmlspecialchars($doctor['specialization']) ?>" required>
</div>

<button class="btn btn-warning w-100">Update Doctor</button>

</form>

</div>

</div>