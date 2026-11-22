<?php
include "../config/db.php";

$doctor_id = 0;
$count = 0;
$pendingAppointments = 0;

if (isset($_SESSION['user'])) {

    $doctor_user_id = $_SESSION['user']['id'];

    $getDoctor = $conn->query("SELECT * FROM doctors WHERE user_id='$doctor_user_id'");

    if ($getDoctor && $getDoctor->num_rows > 0) {
        $doctor = $getDoctor->fetch_assoc();
        $doctor_id = $doctor['id'];
    }

    if ($doctor_id) {

        // PENDING PRESCRIPTIONS
        $countResult = $conn->query("
            SELECT COUNT(*) as total 
            FROM prescriptions 
            WHERE status='pending' AND doctor_id='$doctor_id'
        ");

        if ($countResult) {
            $count = $countResult->fetch_assoc()['total'];
        }

        // PENDING APPOINTMENTS
        $apptResult = $conn->query("
            SELECT COUNT(*) as total 
            FROM appointments 
            WHERE doctor_id='$doctor_id' AND status='pending'
        ");

        if ($apptResult) {
            $pendingAppointments = $apptResult->fetch_assoc()['total'];
        }
    }
}
?>

<div class="sidebar">
<h4>🏥 OPMS</h4>

    <a href="/OPMS/doctor/dashboard.php">
        <i class="fas fa-home"></i> Dashboard
    </a>

    <a href="/OPMS/doctor/appointments.php">
        <i class="fas fa-calendar"></i> Appointments
        <?php if($pendingAppointments > 0): ?>
            <span class="badge bg-danger float-end"><?= $pendingAppointments ?></span>
        <?php endif; ?>
    </a>

    <a href="/OPMS/doctor/prescription.php">
        <i class="fas fa-notes-medical"></i> Add Prescription
    </a>

    <a href="/OPMS/doctor/view_prescriptions.php">
        <i class="fas fa-file-medical"></i> View Prescriptions
        <?php if($count > 0): ?>
            <span class="badge bg-danger float-end"><?= $count ?></span>
        <?php endif; ?>
    </a>

    <a href="/OPMS/doctor/patients.php">
        <i class="fas fa-user-injured"></i> Patients
    </a>

    <a href="/OPMS/doctor/add_slots.php">
        <i class="fas fa-clock"></i> Time Slots
    </a>


    <a href="/OPMS/logout.php">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>


</div>