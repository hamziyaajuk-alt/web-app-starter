<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;

if (!$user) return;

if ($user['role'] == 'admin') {
    include __DIR__ . "/sidebar_admin.php";
}
elseif ($user['role'] == 'doctor') {
    include __DIR__ . "/sidebar_doctor.php";
}
elseif ($user['role'] == 'pharmacist') {
    include __DIR__ . "/sidebar_pharmacist.php";
}
elseif ($user['role'] == 'patient') {
    include __DIR__ . "/sidebar_patient.php";
}
?>