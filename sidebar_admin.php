<?php
include "../config/db.php";

/* COUNT PENDING USERS */
$pendingUsers = $conn->query("
    SELECT COUNT(*) as total 
    FROM users 
    WHERE status='pending'
")->fetch_assoc()['total'];
?>

<div class="sidebar">
    <h4 class="text-center mb-4">
        <h4>🏥 OPMS</h4>
    </h4>

    <a href="/OPMS/admin/dashboard.php">
        <i class="fa fa-home"></i> Dashboard
    </a>

    <a href="/OPMS/admin/approve_users.php">
        <i class="fa fa-user-check"></i> Approve Users

        <?php if($pendingUsers > 0): ?>
            <span class="badge bg-danger float-end">
                <?= $pendingUsers ?>
            </span>
        <?php endif; ?>
    </a>

    <a href="/OPMS/admin/add_doctor.php">
        <i class="fa fa-user-md"></i> Add Doctor
    </a>

    <a href="/OPMS/admin/view_doctors.php">
        <i class="fa fa-stethoscope"></i> View Doctors
    </a>

    <a href="/OPMS/admin/add_patient.php">
        <i class="fa fa-user-plus"></i> Add Patient
    </a>

    <a href="/OPMS/admin/view_patients.php">
        <i class="fa fa-user-injured"></i> View Patients
    </a>

    <a href="/OPMS/admin/appointments.php">
        <i class="fa fa-calendar"></i> Appointments
    </a>

    <a href="/OPMS/admin/prescriptions.php">
        <i class="fa fa-file-medical"></i> Prescriptions
    </a>

    <a href="/OPMS/admin/reports.php">
        <i class="fa fa-chart-bar"></i> Reports
    </a>


    <a href="/OPMS/logout.php">
        <i class="fa fa-sign-out-alt"></i> Logout
    </a>
</div>