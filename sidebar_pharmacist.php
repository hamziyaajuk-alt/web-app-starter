<?php
include __DIR__ . "/../config/db.php";

// GET PENDING COUNT
$count = $conn->query("SELECT COUNT(*) as total FROM prescriptions WHERE status='sent'")
              ->fetch_assoc()['total'];
?>

<div class="sidebar">
    <h4>🏥 OPMS</h4>

    <p class="text-center text-light small">
    </p>

    <a href="dashboard.php">
        <i class="fa fa-home"></i> Dashboard
    </a>

    <a href="prescriptions.php">
        <i class="fa fa-pills"></i> Prescriptions
        
        <!-- 🔴 BADGE -->
        <?php if($count > 0): ?>
            <span style="
                float:right;
                background:red;
                color:white;
                border-radius:50%;
                padding:4px 8px;
                font-size:12px;">
                <?= $count ?>
            </span>
        <?php endif; ?>
    </a>

    <a href="dispensed.php">
        <i class="fa fa-check"></i> Dispensed
    </a>


    <a href="../logout.php">
        <i class="fa fa-sign-out-alt"></i> Logout
    </a>
</div>