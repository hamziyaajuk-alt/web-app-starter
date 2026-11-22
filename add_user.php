<?php
include "../config/db.php";

if (isset($_POST['save'])) {

    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // INSERT USER
    $conn->query("
        INSERT INTO users (name, username, password, role, status)
        VALUES ('$name', '$username', '$password', '$role', 'active')
    ");

    // GET LAST INSERTED USER ID
    $user_id = $conn->insert_id;

    // ✅ AUTO CREATE PATIENT
    if ($role == 'patient') {
        $conn->query("
            INSERT INTO patients (name, user_id)
            VALUES ('$name', '$user_id')
        ");
    }

    // ✅ AUTO CREATE DOCTOR (optional but recommended)
    if ($role == 'doctor') {
        $conn->query("
            INSERT INTO doctors (name, user_id, status)
            VALUES ('$name', '$user_id', 'active')
        ");
    }

    echo "✅ User created successfully!";
}
?>