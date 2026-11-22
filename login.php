<?php
session_start();
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username == '' || $password == '') {
        $error = "⚠️ Please enter username and password!";
    } else {

        // ✅ safer query
        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username=?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] == 'admin') {
        header("Location: /OPMSS/admin/dashboard.php");
    } elseif ($user['role'] == 'doctor') {
        header("Location: /OPMSS/doctor/dashboard.php");
    } else {
        header("Location: /OPMSS/patient/dashboard.php");
    }

    exit();

} else {
    $error = "❌ Invalid username or password!";
}
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: /opms/admin/dashboard.php");
            } elseif ($user['role'] == 'doctor') {
                header("Location: /opms/doctor/dashboard.php");
            } else {
                header("Location: /opms/patient/dashboard.php");
            }

            exit();

        } else {
            $error = "❌ Invalid username or password!";
        }
    }
}
?>