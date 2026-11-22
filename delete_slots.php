<?php
session_start();
include "../config/db.php";

$id = (int) $_GET['id'];

// ONLY DELETE IF AVAILABLE
$conn->query("
    DELETE FROM doctor_slots 
    WHERE id='$id' AND status='available'
");

header("Location: add_slots.php");
exit();
