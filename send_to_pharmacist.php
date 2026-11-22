<?php
include "../config/db.php";

$id = (int) $_GET['id'];

$conn->query("UPDATE prescriptions SET status='sent' WHERE id=$id");

header("Location: view_prescriptions.php");
exit();