<?php
require "config.php";
// session_start();

$email = $_SESSION["login"]; 
if ($email == NULL) {
    header("Location: login.php");
    exit(); // It's good practice to exit after a redirect
}

// Get the current page name
$active = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="../HRMS_LOGO.png" type="image/png">
    <title>Employee Dashboard</title>
    <style>
    #logo {
        height: 50px;
        width: 150px;
    }
    .nav-link.active {
        color: orange !important; 
        font-weight: bold; 
    }
    </style>
</head>

<body>
    <header class="bg-dark text-white p-3">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <a class="navbar-brand" href="#"><img src="../logo.png" alt="" id="logo"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link <?= $active == 'employee.php' ? 'active' : ''; ?>" href="./employee.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $active == 'profile.php' || $active == 'edit_profile.php' ? 'active' : ''; ?>" href="./profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $active == 'view_my_leaves.php' || $active == 'request_leave.php' ? 'active' : ''; ?>" href="./view_my_leaves.php">Leave Requests</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $active == 'payroll.php' ? 'active' : ''; ?>" href="payroll.php">Payroll</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $active == 'logout.php' ? 'active' : ''; ?>" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
