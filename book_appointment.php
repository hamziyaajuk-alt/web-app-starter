<?php
session_start();
include "../config/db.php";
require "../mail/send_mail.php";

// CHECK LOGIN
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient') {
    header("Location: /OPMS/index.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

// GET PATIENT DETAILS
$p = $conn->query("SELECT * FROM patients WHERE user_id=$user_id");

if (!$p || $p->num_rows == 0) {
    die("❌ Patient not found!");
}

$p = $p->fetch_assoc();

$patient_id = $p['id'];
$patient_email = $p['email'] ?? '';
$patient_name = $p['name'] ?? 'Patient';

$msg = "";

// SAVE APPOINTMENT
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $doctor_id = (int)$_POST['doctor_id'];
    $slot_id = (int)$_POST['slot_id'];
    $reason = $conn->real_escape_string($_POST['reason']);

    // GET SLOT
    $slot = $conn->query("SELECT * FROM doctor_slots WHERE id=$slot_id AND status='available'");

    if ($slot && $slot->num_rows > 0) {

        $slot = $slot->fetch_assoc();
        $date = $slot['slot_date'];
        $time = $slot['start_time'];

        // INSERT APPOINTMENT
        $insert = $conn->query("
            INSERT INTO appointments 
            (patient_id, doctor_id, date, time, reason, status)
            VALUES ($patient_id, $doctor_id, '$date', '$time', '$reason', 'pending')
        ");

        if ($insert) {

            // INSERT NOTIFICATION
            $message = "Your appointment is booked successfully on $date at $time.";
            $conn->query("
                INSERT INTO notifications (user_id, role, message) 
                VALUES ($user_id, 'patient', '$message')
            ");

            // UPDATE SLOT STATUS
            $conn->query("
                UPDATE doctor_slots 
                SET status='booked' 
                WHERE id=$slot_id
            ");

            // SEND EMAIL
            if (!empty($patient_email)) {

                $email_subject = "Appointment Confirmation - OPMS";

                $email_body = "
                    <h3>Hello $patient_name,</h3>
                    <p>Your appointment has been successfully booked.</p>
                    <p><b>Date:</b> $date</p>
                    <p><b>Time:</b> $time</p>
                    <br>
                    <p>Please arrive 10 minutes early.</p>
                    <p>Thank you,<br>OPMS System</p>
                ";

                sendMail($patient_email, $email_subject, $email_body);
            }

            $msg = "✅ Appointment booked successfully!";

        } else {
            $msg = "❌ Failed to book appointment!";
        }

    } else {
        $msg = "❌ Selected slot is not available!";
    }
}

// GET DOCTORS
$doctors = $conn->query("SELECT * FROM doctors");

include "../layout/header.php";
include "../layout/sidebar.php";
?>

<div class="main">

<h3>📅 Book Appointment</h3>

<?php if($msg): ?>
<div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="card p-4">

<form method="POST">

<!-- DOCTOR -->
<div class="mb-3">
<label>Select Doctor</label>
<select id="doctorSelect" name="doctor_id" class="form-control" required>
<option value="">-- Select Doctor --</option>

<?php while($d = $doctors->fetch_assoc()): ?>
<option value="<?= $d['id'] ?>">
<?= htmlspecialchars($d['name']) ?> (<?= htmlspecialchars($d['specialization']) ?>)
</option>
<?php endwhile; ?>

</select>
</div>

<!-- SLOT -->
<div class="mb-3">
<label>Select Time Slot</label>
<select id="slotSelect" name="slot_id" class="form-control" required>
<option value="">-- Select Doctor First --</option>
</select>
</div>

<!-- REASON -->
<div class="mb-3">
<label>Reason</label>
<textarea name="reason" class="form-control" rows="3" placeholder="Describe your issue..."></textarea>
</div>

<button class="btn btn-primary w-100">Book Appointment</button>

</form>

</div>

</div>

<!-- AJAX SLOT LOADER -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    const doctorSelect = document.getElementById("doctorSelect");
    const slotSelect = document.getElementById("slotSelect");

    doctorSelect.addEventListener("change", function () {

        let doctorId = this.value;

        if (!doctorId) {
            slotSelect.innerHTML = "<option>-- Select Doctor First --</option>";
            return;
        }

        slotSelect.innerHTML = "<option>Loading...</option>";

        fetch("/OPMS/patient/get_slots.php?doctor_id=" + doctorId)
        .then(res => res.text())
        .then(data => {
            slotSelect.innerHTML = data;
        })
        .catch(err => {
            console.error(err);
            slotSelect.innerHTML = "<option>Error loading slots</option>";
        });

    });

});
</script>