<?php
session_start();
include "../config/db.php";
require "../mail/send_mail.php";

// CHECK ADMIN LOGIN
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: /OPMS/index.php");
    exit();
}

$msg = "";

// APPROVE USER
if (isset($_GET['approve'])) {

    $id = (int) $_GET['approve'];

    $update = $conn->query("UPDATE users SET status='approved' WHERE id=$id");

    if ($update) {

        // GET USER ROLE
        $user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
        $role = $user['role'];

        // GET EMAIL + NAME FROM PATIENT OR DOCTOR
        if ($role == 'patient') {
            $data = $conn->query("SELECT name, email FROM patients WHERE user_id=$id")->fetch_assoc();
        } else {
            $data = $conn->query("SELECT name, email FROM doctors WHERE user_id=$id")->fetch_assoc();
        }

        if (!empty($data['email'])) {

            $name = $data['name'] ?? 'User';
            $email = $data['email'];

            $subject = "Account Approved - OPMS";

            $message = "
                <h3>Hello $name,</h3>
                <p>Your account has been approved by the admin.</p>
                <p>You can now login and access the system.</p>
                <br>
                <p>Thank you,<br>OPMS System</p>
            ";

            sendMail($email, $subject, $message);
        }

        $msg = "✅ User approved and email sent!";
    } else {
        $msg = "❌ Failed to approve user!";
    }
}

// REJECT USER
if (isset($_GET['reject'])) {

    $id = (int) $_GET['reject'];

    $update = $conn->query("UPDATE users SET status='rejected' WHERE id=$id");

    if ($update) {
        $msg = "❌ User rejected!";
    } else {
        $msg = "❌ Failed to reject user!";
    }
}

// GET PENDING USERS
$result = $conn->query("SELECT * FROM users WHERE status='pending'");
?>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="main">

<h3 class="mb-4">👥 Approve Users</h3>

<?php if($msg): ?>
<div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="card p-3">

<table class="table table-bordered table-hover">

<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Role</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php if($result && $result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td><?= ucfirst($row['role']) ?></td>
        <td>
            <a href="?approve=<?= $row['id'] ?>" class="btn btn-success btn-sm">
                ✅ Approve
            </a>
            <a href="?reject=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
               onclick="return confirm('Reject this user?')">
                ❌ Reject
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center">No pending users</td>
    </tr>
<?php endif; ?>

</tbody>

</table>

</div>

</div>