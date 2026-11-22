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
    $specialization = $_POST['specialization'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // CHECK USER EXISTS
    $check = $conn->query("SELECT * FROM users WHERE username='$username'");

    if ($check->num_rows > 0) {
        $message = "❌ Username already exists!";
    } else {

        // INSERT INTO USERS
        $conn->query("
            INSERT INTO users (username, password, role, status)
            VALUES ('$username', '$password', 'doctor', 'approved')
        ");

        $user_id = $conn->insert_id;

        // INSERT INTO DOCTORS
        $conn->query("
            INSERT INTO doctors (user_id, name, specialization)
            VALUES ($user_id, '$name', '$specialization')
        ");

        $message = "✅ Doctor added successfully!";
    }
}
?>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="main">

<h3 class="mb-4">👨‍⚕️ Add Doctor</h3>

<?php if($message): ?>
<div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<div class="card p-4">

<form method="POST">

<div class="mb-3">
    <label>Doctor Name</label>
    <input type="text" name="name" class="form-control" required>
</div>

<div class="mb-3">
    <label>Specialization</label>
    <input type="text" name="specialization" class="form-control" required>
</div>

<hr>

<h5>Login Details</h5>

<div class="mb-3">
    <label>Username</label>
    <input type="text" name="username" class="form-control" required>
</div>

<div class="mb-3">
    <label>Password</label>
    <input type="password" name="password" class="form-control" required>
</div>

<button class="btn btn-success w-100">
    ➕ Add Doctor
</button>

</form>

</div>

</div>

