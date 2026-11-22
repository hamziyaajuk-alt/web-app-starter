<?php
session_start();
include "../config/db.php";

$id = (int) $_GET['id'];

// GET USER ID FIRST
$row = $conn->query("SELECT user_id FROM doctors WHERE id=$id")->fetch_assoc();
$user_id = $row['user_id'] ?? 0;

// DELETE DOCTOR
$conn->query("DELETE FROM doctors WHERE id=$id");

// DELETE USER
if ($user_id) {
    $conn->query("DELETE FROM users WHERE id=$user_id");
}

header("Location: view_doctors.php");
exit();