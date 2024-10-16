<?php
require "config.php";
session_start();

// Check if there's a session message to display
if (isset($_SESSION["delete"])) {
    unset($_SESSION["delete"]);
}

// Fetch employees for dropdown
$employees = [];
$sql = "SELECT id, name FROM employee";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employee_id'];
    $month = $_POST['month'];
    $salary = $_POST['salary'];

    // Prepare the SQL statement
    $sql = "INSERT INTO payroll (employee_id, month, salary) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("isd", $employee_id, $month, $salary);
        
        // Execute the statement
        if ($stmt->execute()) {
            // $_SESSION["success_message"] = "Payslip generated successfully.";
            // Redirect to payroll history
            header("Location: payroll_history.php");
            exit(); // Make sure to exit after header redirect
        } else {
            $_SESSION["error_message"] = "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        $_SESSION["error_message"] = "Error preparing statement: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Generate Payslip</title>
    <link rel="icon" href="../HRMS_LOGO.png" type="image/png">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">

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
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="nav-icon fas fa-user-tie"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Setting</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require "./sidebar.php"; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper">
            <!-- Navbar and Sidebar -->
            <?php require "./sidebar.php"; ?>

            <div class="content-wrapper">
                <div class="content-header">
                    <h1 class="m-0">Generate Payslip</h1>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <?php if (isset($_SESSION["success_message"])): ?>
                                        <div class="alert alert-success">
                                            <?= htmlspecialchars($_SESSION["success_message"]) ?>
                                            <?php unset($_SESSION["success_message"]); ?>
                                        </div>
                                        <?php elseif (isset($_SESSION["error_message"])): ?>
                                        <div class="alert alert-danger">
                                            <?= htmlspecialchars($_SESSION["error_message"]) ?>
                                            <?php unset($_SESSION["error_message"]); ?>
                                        </div>
                                        <?php endif; ?>

                                        <form action="" method="POST" class="text-center">
                                            <div class="row justify-content-center">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label for="employee_id"
                                                            class="col-sm-4 col-form-label">Employee:</label>
                                                        <div class="col-sm-8">
                                                            <select name="employee_id" required class="form-control">
                                                                <option value="">Select an employee</option>
                                                                <?php foreach ($employees as $employee): ?>
                                                                <option
                                                                    value="<?= htmlspecialchars($employee['id']) ?>">
                                                                    <?= htmlspecialchars($employee['name']) ?>
                                                                </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="month"
                                                            class="col-sm-4 col-form-label">Month:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="month" placeholder="e.g., 2024/01"
                                                                required class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="salary"
                                                            class="col-sm-4 col-form-label">Salary:</label>
                                                        <div class="col-sm-8">
                                                            <input type="number" name="salary" min="1" required
                                                                class="form-control">
                                                        </div>
                                                    </div>

                                                    <input type="submit" value="Generate Payslip"
                                                        class="btn btn-primary">
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <footer class="main-footer">
                <strong>Copyright &copy; Human Resource Management System 2024-25.</strong> All rights reserved.
            </footer>
        </div>


        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; Human Resource Management System 2024-25.</strong>All rights reserved.
            <!-- <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 3.2.0
            </div> -->
        </footer>
    </div>
    <!-- ./wrapper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
    $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard.js"></script>
</body>

</html>