<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient') {
    header("Location: /OPMS/index.php");
    exit();
}

include "../layout/header.php";
include "../layout/sidebar.php";
?>

<div class="main">
<h3>🧠 AI Symptom Checker</h3>

<div class="card p-4">

<form method="POST">
    <label>Enter your symptoms:</label>
    <textarea name="symptoms" class="form-control" rows="3" placeholder="e.g. fever, headache, cough"></textarea>
    
    <button class="btn btn-primary mt-3">Check</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $input = strtolower($_POST['symptoms']);

    $result = "";
    $doctor = "";
    $urgency = "";

    // 🔍 RULE-BASED AI LOGIC

    if (strpos($input, "fever") !== false && strpos($input, "body pain") !== false) {
        $result = "Possible Dengue or Viral Fever";
        $doctor = "General Physician";
        $urgency = "⚠️ Medium";
    }
    elseif (strpos($input, "cough") !== false && strpos($input, "fever") !== false) {
        $result = "Possible Flu or COVID-19";
        $doctor = "General Physician";
        $urgency = "⚠️ Medium";
    }
    elseif (strpos($input, "chest pain") !== false) {
        $result = "Possible Heart Problem";
        $doctor = "Cardiologist";
        $urgency = "🚨 High - Go Immediately";
    }
    elseif (strpos($input, "headache") !== false) {
        $result = "Possible Migraine or Stress";
        $doctor = "Neurologist / General";
        $urgency = "🟢 Low";
    }
    elseif (strpos($input, "stomach") !== false) {
        $result = "Possible Gastric Issue";
        $doctor = "General Physician";
        $urgency = "🟢 Low";
    }
    else {
        $result = "Condition unclear";
        $doctor = "General Physician";
        $urgency = "⚠️ Medium";
    }

    echo "
    <div class='alert alert-info mt-4'>
        <b>Result:</b> $result <br>
        <b>Recommended Doctor:</b> $doctor <br>
        <b>Urgency:</b> $urgency
    </div>
    ";
}
?>

</div>
</div>