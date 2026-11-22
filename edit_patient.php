<?php
session_start();
include "../config/db.php";

// CHECK ADMIN
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: /OPMS/index.php");
    exit();
}

// GET ID
$id = (int) $_GET['id'];

// FETCH PATIENT
$result = $conn->query("SELECT * FROM patients WHERE id=$id");

if (!$result || $result->num_rows == 0) {
    die("❌ Patient not found");
}

$patient = $result->fetch_assoc();

// UPDATE
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $conn->real_escape_string($_POST['name']);
    $age = $conn->real_escape_string($_POST['age']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $email = $conn->real_escape_string($_POST['email']);

    $update = $conn->query("
        UPDATE patients 
        SET 
            name='$name',
            age='$age',
            gender='$gender',
            phone='$phone',
            address='$address',
            email='$email'
        WHERE id=$id
    ");

    if ($update) {
        header("Location: view_patients.php?updated=1");
        exit();
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>

<?php include "../layout/header.php"; ?>

<?php include "../layout/sidebar.php"; ?>

<div class="main">

<h3>✏️ Edit Patient</h3>

<div class="card p-4">

<form method="POST">

<div class="mb-3">
<label>Name</label>
<input type="text" name="name" class="form-control"
value="<?= htmlspecialchars($patient['name']) ?>" required>
</div>

<div class="mb-3">
<label>Age</label>
<input type="number" name="age" class="form-control"
value="<?= htmlspecialchars($patient['age']) ?>">
</div>

<div class="mb-3">
<label>Gender</label>
<select name="gender" class="form-control">
<option <?= $patient['gender']=="Male"?"selected":"" ?>>Male</option>
<option <?= $patient['gender']=="Female"?"selected":"" ?>>Female</option>
<option <?= $patient['gender']=="Other"?"selected":"" ?>>Other</option>
</select>
</div>

<div class="mb-3">
<label>Phone</label>
<input type="text" name="phone" class="form-control"
value="<?= htmlspecialchars($patient['phone']) ?>">
</div>

<div class="mb-3">
<label>Address</label>
<textarea name="address" class="form-control"><?= htmlspecialchars($patient['address']) ?></textarea>
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control"
value="<?= htmlspecialchars($patient['email']) ?>">
</div>

<button class="btn btn-warning w-100">Update</button>

</form>

</div>
</div>
