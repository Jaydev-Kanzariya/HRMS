<?php
require "config.php";
session_start();

// Include the database connection
include 'config.php'; 

// Initialize an empty result variable
$result = null;

// Check if a date is submitted
if (isset($_POST['selected_date'])) {
    $selected_date = $_POST['selected_date'];
    
    // Fetch attendance records for the selected date with date formatted as DD-MM-YYYY
    $sql = "SELECT a.id, e.name, DATE_FORMAT(a.date, '%d-%m-%Y') AS formatted_date, a.status 
            FROM attendance a 
            JOIN employee e ON a.employee_id = e.id 
            WHERE a.date = ? 
            ORDER BY a.date DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selected_date);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // If no date is selected, fetch all attendance records
    $sql = "SELECT a.id, e.name, DATE_FORMAT(a.date, '%d-%m-%Y') AS formatted_date, a.status 
            FROM attendance a 
            JOIN employee e ON a.employee_id = e.id 
            ORDER BY a.date DESC";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance Records</title>
    <link rel="icon" href="../HRMS_LOGO.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <style>
        #logo {
            height: 12vmin;
            width: 30vmin;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        <i class="nav-icon fas fa-user-tie"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Setting</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require "./sidebar.php"; ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Attendance Records</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body mb-5">
                                    <form action="" method="POST" class="mb-4">
                                        <div class="form-group">
                                            <label for="selected_date">Select Date:</label>
                                            <input type="date" name="selected_date" class="form-control" required>
                                        </div>
                                        <input type="submit" value="Show Attendance" class="btn btn-primary">
                                    </form>
                                    <table class="table table-bordered table-striped table-responsive">
                                        <thead>
                                            <tr>
                                                <th>No.</th> <!-- Added header for numbering -->
                                                <th>Employee Name</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($result && $result->num_rows > 0) {
                                                $count = 1; // Initialize the counter
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>
                                                            <td>" . $count++ . "</td> <!-- Display the counter -->
                                                            <td>" . htmlspecialchars($row['name']) . "</td>
                                                            <td>" . htmlspecialchars($row['formatted_date']) . "</td> <!-- Use formatted date -->
                                                            <td>" . htmlspecialchars($row['status']) . "</td>
                                                        </tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='4'>No records found</td></tr>"; // Adjusted colspan
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; Human Resource Management System 2024-25.</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- Scripts -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
</body>

</html>
