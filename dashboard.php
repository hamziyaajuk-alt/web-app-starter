<?php
session_start();
include "../config/db.php";

// CHECK LOGIN
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'pharmacist') {
    header("Location: /OPMS/index.php");
    exit();
}

// STATS
$pending = $conn->query("SELECT COUNT(*) as total FROM prescriptions WHERE status='sent'")
                ->fetch_assoc()['total'];

$done = $conn->query("SELECT COUNT(*) as total FROM prescriptions WHERE status='dispensed'")
             ->fetch_assoc()['total'];

$total = $conn->query("
    SELECT COUNT(*) as total 
    FROM prescriptions 
    WHERE status IN ('sent','dispensed')
")->fetch_assoc()['total'];

// CHART DATA (last days)
$chartData = $conn->query("
    SELECT DATE(created_at) as date, COUNT(*) as total
    FROM prescriptions
    WHERE status='dispensed'
    GROUP BY DATE(created_at)
    ORDER BY date ASC
");

$dates = [];
$totals = [];

while ($row = $chartData->fetch_assoc()) {
    $dates[] = $row['date'];
    $totals[] = $row['total'];
}

include "../layout/header.php";
include "../layout/sidebar.php";
?>

<div class="main">

<h3 class="mb-1">💊 Pharmacist Dashboard</h3>
<p class="text-muted"></p>

<!-- CARDS -->
<div class="row">

    <div class="col-md-4">
        <div class="card bg-warning text-white p-3 shadow-sm">
            <h5>Pending</h5>
            <h2><?= $pending ?></h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-success text-white p-3 shadow-sm">
            <h5>Dispensed</h5>
            <h2><?= $done ?></h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-primary text-white p-3 shadow-sm">
            <h5>Total</h5>
            <h2><?= $total ?></h2>
        </div>
    </div>

</div>

<!-- CHART -->
<div class="row mt-4">

    <div class="col-md-12">
        <div class="card p-3 shadow-sm">
            <h5>📈 Dispensed Trend</h5>
            <div style="height:300px;">
                <canvas id="chart"></canvas>
            </div>
        </div>
    </div>

</div>

<!-- QUICK ACTION -->
<div class="mt-4">
    <a href="prescriptions.php" class="btn btn-primary">
        💊 View Prescriptions
    </a>
</div>

</div>

<script>
new Chart(document.getElementById('chart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($dates) ?>,
        datasets: [{
            label: 'Dispensed',
            data: <?= json_encode($totals) ?>,
            borderWidth: 2,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>

<?php include "../layout/footer.php"; ?>