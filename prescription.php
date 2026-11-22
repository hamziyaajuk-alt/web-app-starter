<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "../config/db.php";
require "../mail/send_mail.php";

// CHECK DOCTOR LOGIN
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'doctor') {
    header("Location: /OPMS/index.php");
    exit();
}

$doctor_user_id = (int)$_SESSION['user']['id'];

// GET DOCTOR
$getDoctor = $conn->query("SELECT * FROM doctors WHERE user_id = $doctor_user_id");

if (!$getDoctor || $getDoctor->num_rows == 0) {
    die("❌ Doctor not linked!");
}

$doctor = $getDoctor->fetch_assoc();
$doctor_id = $doctor['id'];

$msg = "";

// SAVE PRESCRIPTION
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $patient_id = (int)$_POST['patient_id'];
    $diagnosis = trim($_POST['diagnosis']);
    $medicine = trim($_POST['medicine']);

    $diagnosis = $conn->real_escape_string($diagnosis);
    $medicine = $conn->real_escape_string($medicine);

    if ($patient_id <= 0) {
        $msg = "❌ Please select a patient!";
    } elseif (empty($diagnosis) || empty($medicine)) {
        $msg = "❌ Diagnosis and medicine are required!";
    } else {

        // INSERT PRESCRIPTION
        $insert = $conn->query("
            INSERT INTO prescriptions (patient_id, doctor_id, diagnosis, medicine, status)
            VALUES ($patient_id, $doctor_id, '$diagnosis', '$medicine', 'sent')
        ");

        if ($insert) {

            // GET PATIENT DETAILS
            $p = $conn->query("SELECT * FROM patients WHERE id=$patient_id");

            if ($p && $p->num_rows > 0) {

                $p = $p->fetch_assoc();
                $patient_email = $p['email'] ?? '';
                $patient_user_id = $p['user_id'] ?? 0;
                $patient_name = $p['name'] ?? 'Patient';

                // INSERT NOTIFICATION
                if ($patient_user_id > 0) {
                    $conn->query("
                        INSERT INTO notifications (user_id, role, message)
                        VALUES ($patient_user_id, 'patient', 'Your prescription has been sent by doctor.')
                    ");
                }

                // SEND EMAIL ONLY IF BUTTON CLICKED
                if (isset($_POST['save_send']) && !empty($patient_email)) {

                    $email_subject = "Prescription from OPMS";

                    $email_body = "
                        <h3>Hello $patient_name,</h3>
                        <p>Your prescription is sent to the pharmacist.</p>
                        <p><b>Diagnosis:</b> $diagnosis</p>
                        <p><b>Medicine:</b><br>$medicine</p>
                        <br>
                        <p>Please follow the instructions carefully.</p>
                        <p>Thank you,<br>OPMS System</p>
                    ";

                    $mailSent = sendMail($patient_email, $email_subject, $email_body);

                    if ($mailSent) {
                        $msg = "✅ Prescription saved and email sent!";
                    } else {
                        $msg = "⚠️ Prescription saved but email failed!";
                    }

                } else {
                    $msg = "✅ Prescription saved successfully!";
                }

            } else {
                $msg = "⚠️ Prescription saved but patient data not found!";
            }

        } else {
            $msg = "❌ Failed to save prescription!";
        }
    }
}

// FETCH PATIENTS
$patients = $conn->query("SELECT * FROM patients");

include "../layout/header.php";
include "../layout/sidebar.php";
?>

<div class="main">

<h3 class="mb-4">💊 Add Prescription</h3>

<?php if($msg): ?>
<div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="card shadow p-4">
<form method="POST">

<div class="mb-3">
<label>Patient</label>
<select name="patient_id" class="form-select" required>
<option value="">Select Patient</option>

<?php while($p = $patients->fetch_assoc()): ?>
<option value="<?= $p['id'] ?>">
<?= htmlspecialchars($p['name']) ?>
</option>
<?php endwhile; ?>

</select>
</div>

<!-- DIAGNOSIS -->
<div class="mb-3">
<label>Diagnosis</label>
<input type="text" id="diagnosis" name="diagnosis" class="form-control" placeholder="Enter diagnosis" required>
</div>

<!-- MEDICINE -->
<div class="mb-3">
<label>Medicine</label>
<textarea id="medicine" name="medicine" class="form-control" rows="4" placeholder="Medicine will be suggested..." required></textarea>

<small id="suggestionBox" style="color: green; display: none;">
💡 Suggested: <span id="suggestionText"></span>
</small>
</div>

<div class="d-flex gap-2">

    <!-- SAVE ONLY -->
    <button type="submit" name="save_only" class="btn btn-secondary w-50">
        💾 Save
    </button>

    <!-- SAVE + SEND -->
    <button type="submit" name="save_send" class="btn btn-success w-50">
        📧 Save & Send
    </button>

</div>

</form>
</div>
</div>

<!-- AI SUGGESTION SCRIPT -->
<script>
document.getElementById('diagnosis').addEventListener('input', function() {

    let text = this.value.toLowerCase();
    let medicineField = document.getElementById('medicine');
    let suggestionBox = document.getElementById('suggestionBox');
    let suggestionText = document.getElementById('suggestionText');

    let suggestions = {
        "fever": "Paracetamol 500mg - 2 times daily",
        "cold": "Cetirizine 10mg - once daily",
        "cough": "Cough Syrup - 3 times daily",
        "pain": "Ibuprofen 400mg - after meals",
        "headache": "Paracetamol + rest",
        "infection": "Amoxicillin 500mg - 3 times daily",
        "allergy": "Loratadine 10mg - once daily"
    };

    let found = false;

    for (let key in suggestions) {
        if (text.includes(key)) {

            let fullSuggestion = suggestions[key] +
                "\\n• Drink plenty of water" +
                "\\n• Take proper rest";

            medicineField.value = fullSuggestion;

            suggestionText.innerText = suggestions[key];
            suggestionBox.style.display = "block";

            found = true;
            break;
        }
    }

    if (!found) {
        suggestionBox.style.display = "none";
    }

});
</script>

<?php include "../layout/footer.php"; ?>