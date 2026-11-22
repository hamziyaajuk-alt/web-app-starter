<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>OPMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            display: flex;
            font-family: Arial;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #0f172a;
            color: white;
            position: fixed;
            padding: 20px;
        }

        .sidebar a {
            color: #cbd5e1;
            display: block;
            padding: 10px;
            text-decoration: none;
            border-radius: 8px;
        }

        .sidebar a:hover {
            background: #1e293b;
            color: white;
        }

        .content {
            margin-left: 250px;
            width: 100%;
            padding: 20px;
            background: #f1f5f9;
            min-height: 100vh;
        }

        .topbar {
            background: linear-gradient(135deg,#0f172a,#1e293b);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <?php
    if ($_SESSION['role'] == 'admin') {
        include 'sidebar_admin.php';
    } elseif ($_SESSION['role'] == 'doctor') {
        include 'sidebar_doctor.php';
    } else {
        include 'sidebar_patient.php';
    }
    ?>
</div>

<!-- CONTENT -->
<div class="content">

    <div class="topbar d-flex justify-content-between">
        <div>
            <h5>🏥 OPMS System</h5>
            <small>Welcome, <?php echo $_SESSION['username']; ?></small>
        </div>

        <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>