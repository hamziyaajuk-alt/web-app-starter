<?php
session_start();
include "config/db.php";

$msg = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $email = trim($_POST['email']);

    // BASIC VALIDATION
    if (empty($name) || empty($username) || empty($email) || empty($role)) {
        $msg = "❌ All fields are required!";
    }
    else {

        // CHECK DUPLICATE USERNAME
        $check = $conn->query("SELECT id FROM users WHERE username='$username'");

        if ($check->num_rows > 0) {
            $msg = "❌ Username already exists!";
        } else {

            // INSERT USER
            $conn->query("
                INSERT INTO users (username, password, role, status)
                VALUES ('$username', '$password', '$role', 'pending')
            ");

            $user_id = $conn->insert_id;

            // INSERT PATIENT / DOCTOR
if ($role == 'patient') {
    $conn->query("INSERT INTO patients (user_id, name, email) VALUES ($user_id, '$name', '$email')");
}

            if ($role == 'doctor') {
                $conn->query("
                    INSERT INTO doctors (user_id, name)
                    VALUES ($user_id, '$name')
                ");
            }

            // SUCCESS MESSAGE
            $success = "✅ Registration successful! Please wait until admin approves your account.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register - OPMS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #4f46e5, #06b6d4);
    min-height: 100vh;
    display: flex;
    align-items: center;
}

.card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.form-control {
    border-radius: 8px;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

.title-icon {
    font-size: 40px;
    color: #4f46e5;
}
</style>
</head>

<body>

<div class="container">
<div class="row justify-content-center">

<div class="col-md-5">

<div class="card p-4">

<div class="text-center mb-3">
    <i class="fa-solid fa-user-plus title-icon"></i>
    <h4 class="mt-2">Create Account</h4>
    <p class="text-muted">Register to access OPMS system</p>
</div>

<?php if($msg): ?>
<div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<?php if($success): ?>
<div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST">

<div class="mb-2">
    <label>Full Name</label>
    <input type="text" name="name" class="form-control" required>
</div>

<div class="mb-2">
    <label>Username</label>
    <input type="text" name="username" class="form-control" required>
</div>

<div class="mb-2">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required>
</div>

<div class="mb-2">
    <label>Password</label>
    <input type="password" name="password" class="form-control" required>
</div>

<div class="mb-3">
    <label>Role</label>
    <select name="role" class="form-control" required>
        <option value="">Select Role</option>
        <option value="patient">Patient</option>
        <option value="doctor">Doctor</option>
    </select>
</div>

<button class="btn btn-primary w-100">
    <i class="fa-solid fa-user-plus"></i> Register
</button>

</form>

<hr>

<p class="text-center mb-0">
    Already have an account? 
    <a href="index.php">Login here</a>
</p>

</div>

</div>

</div>
</div>

</body>
</html>