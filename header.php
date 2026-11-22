<!DOCTYPE html>
<html>
<head>
    <title></title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }

        /* SIDEBAR */
        .sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            top: 0;          
            left: 0;         
            background: #1e293b;
            color: white;
            padding-top: 20px;
        }

        .sidebar h4 {
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: bold;
        }

        .sidebar a {
            display: block;
            color: #cbd5e1;
            padding: 12px 20px;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .sidebar a:hover {
            background: #334155;
            color: white;
        }

        /* TOP NAVBAR */
      .topbar {
    position: fixed;
    top: 0;
    left: 240px;
    right: 0;
    height: 60px;
    background: white;
    display: flex;
    align-items: center;
    padding: 0 20px;
    border-bottom: 1px solid #ddd;
    z-index: 999;
}

        /* MAIN CONTENT */
.main {
    margin-left: 240px;
    margin-top: 20px; /* 🔥 IMPORTANT */
    padding: 20px;
}


        /* CARDS */
        .card {
            border-radius: 12px;
            border: none;
        }

        /* BUTTONS */
        .btn {
            border-radius: 8px;
        }

        /* FORM */
        .form-control, .form-select {
            border-radius: 8px;
        }

        /* MOBILE */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .topbar {
                margin-left: 0;
            }

            .main {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>



<?php include __DIR__ . "/../chatbot.php"; ?>

