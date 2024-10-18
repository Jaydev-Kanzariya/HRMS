<?php
require "config.php";
session_start();

// Check for delete message in session
if (isset($_SESSION["delete"])) {
    unset($_SESSION["delete"]);
}

// Handle attendance submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date']; // Get the date from the form

    foreach ($_POST['attendance'] as $employee_id => $status) {
        // Check if attendance for this employee and date already exists
        $checkSql = "SELECT * FROM attendance WHERE employee_id = ? AND date = ?";
        $stmt = $conn->prepare($checkSql);
        $stmt->bind_param("is", $employee_id, $date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Attendance already marked
        } else {
            // Insert attendance record
            $sql = "INSERT INTO attendance (employee_id, date, status) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $employee_id, $date, $status);
            $stmt->execute();
        }
    }
}

// Fetch all employees without pagination
$sql = "SELECT id, name FROM employee";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employee Attendance</title>
    <link rel="icon" href="../HRMS_LOGO.png" type="image/png">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
                <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a></li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        <i class="nav-icon fas fa-user-tie"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
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

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Employee Attendance</h1>
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
                                    <form action="" method="POST" class="text-center">
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="date">Date:</label>
                                                <input type="date" name="date" class="form-control" required
                                                    min="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Employee ID</th>
                                                        <th>Employee Name</th>
                                                        <th>Attendance Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        $count = 1; 
                                                        while ($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?= $count++ ?></td>
                                                        <td><?= htmlspecialchars($row['id']) ?></td>
                                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                                        <td>
                                                            <div>
                                                                <input type="radio" id="present_<?= $row['id'] ?>"
                                                                    name="attendance[<?= $row['id'] ?>]" value="present"
                                                                    required checked>
                                                                <label
                                                                    for="present_<?= $row['id'] ?>">Present</label>&nbsp;
                                                                <input type="radio" id="absent_<?= $row['id'] ?>"
                                                                    name="attendance[<?= $row['id'] ?>]" value="absent">
                                                                <label for="absent_<?= $row['id'] ?>">Absent</label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <br>
                                        <input type="submit" value="Submit Attendance"
                                            class="btn btn-primary">
                                    </form>
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