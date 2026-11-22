<?php
session_start();
include "../config/db.php";

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = (int) $_GET['id'];

// GET PRESCRIPTION DATA
$result = $conn->query("
    SELECT p.*, d.name AS doctor, pt.name AS patient
    FROM prescriptions p
    JOIN doctors d ON p.doctor_id = d.id
    JOIN patients pt ON p.patient_id = pt.id
    WHERE p.id = $id
");

$data = $result->fetch_assoc();

if (!$data) {
    die("Prescription not found");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Prescription</title>

<style>
body {
    font-family: 'Segoe UI', Arial;
    background: #f5f5f5;
}

.container {
    width: 700px;
    margin: auto;
    background: white;
    padding: 30px;
    border: 2px solid #000;
}

.header {
    text-align: center;
    margin-bottom: 20px;
}

.header h1 {
    margin: 0;
}

.section {
    margin-bottom: 15px;
}

.label {
    font-weight: bold;
}

.medicine-box {
    border: 1px solid #000;
    padding: 10px;
    min-height: 80px;
}

.footer {
    margin-top: 40px;
    display: flex;
    justify-content: space-between;
}

.print-btn {
    text-align: center;
    margin-top: 20px;
}

button {
    padding: 10px 20px;
    font-size: 16px;
}
</style>

</head>

<body>

<div class="container">

<!-- HEADER -->
<div class="header">
    <h1>🏥 OPMS Hospital</h1>
    <p>Medical Prescription</p>
</div>

<hr>

<!-- DETAILS -->
<div class="section">
    <span class="label">Patient:</span>
    <?= htmlspecialchars($data['patient']) ?>
</div>

<div class="section">
    <span class="label">Doctor:</span>
    <?= htmlspecialchars($data['doctor']) ?>
</div>

<div class="section">
    <span class="label">Date:</span>
    <?= htmlspecialchars($data['created_at'] ?? date('Y-m-d')) ?>
</div>

<hr>

<!-- DIAGNOSIS -->
<div class="section">
    <span class="label">Diagnosis:</span><br>
    <?= nl2br(htmlspecialchars($data['diagnosis'])) ?>
</div>

<!-- MEDICINE -->
<div class="section">
    <span class="label">Medicine:</span>
    <div class="medicine-box">
        <?= nl2br(htmlspecialchars($data['medicine'])) ?>
    </div>
</div>

<!-- FOOTER -->
<div class="footer">
  
    <div>
        <p>______________________</p>
        <p>Doctor Signature</p>
    </div>
</div>

<!-- PRINT BUTTON -->
<div class="print-btn">
    <button onclick="window.print()">🖨 Print</button>
</div>

</div>

</body>
</html>