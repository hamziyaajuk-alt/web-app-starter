<?php
include "../config/db.php";

$id = (int) $_GET['id'];

$conn->query("UPDATE prescriptions SET status='dispensed' WHERE id=$id");

header("Location: view_prescriptions.php");
exit();