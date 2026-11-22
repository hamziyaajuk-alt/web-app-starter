<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: /OPMS/index.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $check = $conn->query("SELECT * FROM users WHERE username='$username'");

    if ($check->num_rows > 0) {
        $message = "❌ Username already exists!";
    } else {

        // USER
        $conn->query("
            INSERT INTO users (username, password, role, status)
            VALUES ('$username', '$password', 'patient', 'approved')
        ");

        $user_id = $conn->insert_id;

        // PATIENT
        $conn->query("
            INSERT INTO patients (user_id, name, age, gender, phone, address)
            VALUES ($user_id, '$name', '$age', '$gender', '$phone', '$address')
        ");

        $message = "✅ Patient added successfully!";
    }
}
?>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="main">

<h3 class="mb-4">👤 Add Patient</h3>

<?php if($message): ?>
<div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<div class="card p-4">

<form method="POST">

<div class="mb-3">
<label>Name</label>
<input type="text" name="name" class="form-control" required>
</div>

<div class="mb-3">
<label>Age</label>
<input type="number" name="age" class="form-control" required>
</div>

<div class="mb-3">
<label>Gender</label>
<select name="gender" class="form-control" required>
<option>Male</option>
<option>Female</option>
<option>Other</option>
</select>
</div>

<div class="mb-3">
<label>Phone</label>
<input type="text" name="phone" class="form-control">
</div>

<div class="mb-3">
<label>Address</label>
<textarea name="address" class="form-control"></textarea>
</div>

<hr>

<h5>Login</h5>

<div class="mb-3">
<label>Username</label>
<input type="text" name="username" class="form-control" required>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<button class="btn btn-primary w-100">Add Patient</button>

</form>

</div>
</div>

