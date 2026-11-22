<?php
include "../config/db.php";

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="report.csv"');

$output = fopen("php://output", "w");

/* HEADERS */
fputcsv($output, ['ID','Patient','Doctor','Diagnosis','Status','Date']);

/* DATA */
$result = $conn->query("
    SELECT p.id, pt.name, d.name,
           p.diagnosis, p.status, p.created_at
    FROM prescriptions p
    JOIN patients pt ON p.patient_id = pt.id
    JOIN doctors d ON p.doctor_id = d.id
");

while($row = $result->fetch_assoc()){
    fputcsv($output, $row);
}

fclose($output);
exit();