<?php
include "../config/db.php";

$id = $_GET['id'];

$conn->query("UPDATE appointments SET status='completed' WHERE id='$id'");

header("Location: view.php");
exit();
?>