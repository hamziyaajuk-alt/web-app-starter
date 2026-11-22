<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient') {
    header("Location: /OPMS/index.php");
    exit();
}

include "../layout/header.php";
include "../layout/sidebar.php";
?>

<div class="main">
<h3>📅 My Appointments</h3>

<div class="alert alert-info">
Appointment module coming soon...
</div>

</div>