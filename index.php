<?php
session_start();
include "config/db.php";

$message = "";

// If already logged in → redirect
if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            header("Location: /OPMS/admin/dashboard.php"); break;
        case 'doctor':
            header("Location: /OPMS/doctor/dashboard.php"); break;
        case 'pharmacist':
            header("Location: /OPMS/pharmacist/dashboard.php"); break;
        default:
            header("Location: /OPMS/patient/dashboard.php");
    }
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Use prepared statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {

        $user = $result->fetch_assoc();

        // ✅ Support BOTH hashed and old plain passwords
        $passwordValid = false;

        if (password_verify($password, $user['password'])) {
            $passwordValid = true;
        } elseif ($password === $user['password']) {
            // old plain password → upgrade it
            $passwordValid = true;

            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt2 = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $stmt2->bind_param("si", $newHash, $user['id']);
            $stmt2->execute();
        }

        if ($passwordValid) {

            if ($user['status'] != 'approved') {
                $message = "⏳ Wait for admin approval!";
            } else {

                // Set session
                $_SESSION['user'] = $user;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username'];

                // Redirect
                switch ($user['role']) {
                    case 'admin':
                        header("Location: /OPMS/admin/dashboard.php"); break;
                    case 'doctor':
                        header("Location: /OPMS/doctor/dashboard.php"); break;
                    case 'pharmacist':
                        header("Location: /OPMS/pharmacist/dashboard.php"); break;
                    case 'patient':
                        header("Location: /OPMS/patient/dashboard.php"); break;
                    default:
                        $message = "❌ Unknown role!";
                }
                exit();
            }

        } else {
            $message = "❌ Invalid password!";
        }

    } else {
        $message = "❌ User not found!";
    }
}
?>

<!DOCTYPE html>

<html>
<head>
<title>OPMS Login</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body {
    height:100vh;
    margin:0;
    display:flex;
    align-items:center;
    justify-content:center;
    font-family:'Segoe UI';
    background: linear-gradient(-45deg, #1e3c72, #2a5298, #1c92d2, #0f2027);
    background-size: 400% 400%;
    animation: gradientMove 10s ease infinite;
}
@keyframes gradientMove {
    0% { background-position:0% 50%; }
    50% { background-position:100% 50%; }
    100% { background-position:0% 50%; }
}
.login-card {
    width:100%;
    max-width:420px;
    background:#ffffff;
    padding:35px;
    border-radius:15px;
    box-shadow:0 10px 40px rgba(0,0,0,0.3);
}
.login-title {
    text-align:center;
    margin-bottom:25px;
    color:#333;
}
.input-group-text {
    background:#f0f0f0;
    border:none;
}
.form-control {
    border:none;
    background:#f9f9f9;
}
.form-control:focus {
    box-shadow:none;
    background:#fff;
}
.btn-login {
    background: linear-gradient(45deg, #007bff, #00c6ff);
    border:none;
    color:white;
    font-weight:bold;
    border-radius:25px;
}
.btn-login:hover {
    opacity:0.9;
}
.register-link a {
    text-decoration:none;
    color:#007bff;
}
</style>

</head>

<body>

<div class="login-card">

<h3 class="login-title">
    <i class="fa fa-hospital text-primary"></i> OPMS Login
</h3>

<?php if($message): ?>

<div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="POST">

<div class="input-group mb-3">
    <span class="input-group-text"><i class="fa fa-user"></i></span>
    <input type="text" name="username" class="form-control" placeholder="Username" required>
</div>

<div class="input-group mb-4">
    <span class="input-group-text"><i class="fa fa-lock"></i></span>
    <input type="password" name="password" class="form-control" placeholder="Password" required>
</div>

<button class="btn btn-login w-100">Login</button>

</form>

<p class="text-center mt-3 register-link">
    New user? <a href="register.php">Register here</a>
</p>

</div>

</body>
</html>
