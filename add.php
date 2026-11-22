<?php
include "../config/db.php";

$message = "";
$type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $specialty = trim($_POST['specialty']);
    $phone = trim($_POST['phone']);

    // Validation
    if (empty($name) || empty($specialty)) {
        $message = "Name and Specialty are required!";
        $type = "danger";
    } else {

        $sql = "INSERT INTO doctors (name, specialty, phone)
                VALUES ('$name', '$specialty', '$phone')";

        if ($conn->query($sql)) {
            $message = "Doctor added successfully!";
            $type = "success";
        } else {
            $message = "Error: " . $conn->error;
            $type = "danger";
        }
    }
}

include "../layout/header.php";
include "../layout/sidebar.php";
?>

<div class="container">

<h2 class="mb-3">Add Doctor</h2>

<?php if($message): ?>
    <div class="alert alert-<?= $type ?>"><?= $message ?></div>
<?php endif; ?>

<form method="POST" class="card p-4 shadow">

    <label>Name *</label>
    <input type="text" name="name" class="form-control mb-3" required>

    <label>Specialty *</label>
    <input type="text" name="specialty" class="form-control mb-3" required>

    <label>Phone</label>
    <input type="text" name="phone" class="form-control mb-3">

    <button class="btn btn-primary">Add Doctor</button>

</form>

</div>

<?php include "../layout/footer.php"; ?>