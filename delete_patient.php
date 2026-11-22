<?php
session_start();
include "../config/db.php";

if ($_SESSION['user']['role'] != 'admin') {
    header("Location: /OPMS/index.php");
    exit();
}

$id = (int) $_GET['id'];

// GET USER ID FIRST
$row = $conn->query("SELECT user_id FROM patients WHERE id=$id")->fetch_assoc();
$user_id = $row['user_id'] ?? 0;

// DELETE PATIENT
$conn->query("DELETE FROM patients WHERE id=$id");

// DELETE USER ACCOUNT
if ($user_id) {
    $conn->query("DELETE FROM users WHERE id=$user_id");
}

header("Location: view_patients.php");
exit();