<?php
require "config.php";
session_start();
$a = array();

if (!isset($_GET['id'])) {
    // Redirect if no ID is provided
    header("Location: employee-data.php");
    exit();
}

$id = $_GET['id'];

// Fetch current Employee data
$sql = "SELECT e.id, e.name, e.first_name, e.last_name, 
        e.fk_departmentid, e.fk_designationid, 
        d.name AS department_name, des.name AS designation_name,
        e.modifiedate
        FROM employee e
        LEFT JOIN department d ON e.fk_departmentid = d.id
        LEFT JOIN designation des ON e.fk_designationid = des.id
        WHERE e.id = $id";
$res = mysqli_query($conn, $sql);

if (mysqli_num_rows($res) === 0) {
    // Redirect if no record is found
    header("Location: employee-data.php");
    exit();
}

$data = mysqli_fetch_assoc($res);

// Get current date and time
$currentDateTime = date('Y-m-d H:i:s');

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $department = $_POST['department'];
    $designation = $_POST['designation'];

    if ($name == NULL) {
        $a["name_null"] = true;
    }    

    if ($fname == NULL) {
        $a["fname_null"] = true;
    }    

    if ($lname == NULL) {
        $a["lname_null"] = true;
    }    
    
    if ($department == 'Select' || empty($department)) {
        $a["department_null"] = true;
    }

    if ($designation == 'Select' || empty($designation)) {
        $a["designation_null"] = true;
    }

    if (count($a) == 0) {
        // Update Employee data with modifiedate
        $update = "UPDATE employee SET name = '$name', first_name = '$fname', last_name = '$lname', 
            fk_departmentid = '$department', fk_designationid = '$designation',
            modifiedate = '$currentDateTime' WHERE id = '$id'";
        $uresult = mysqli_query($conn, $update);

        if ($uresult) {
            $_SESSION["update"] = true;
            header("Location: employee-data.php");
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Employeee</title>
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

    .errors {
        color: red;
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
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Edit Employee</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->

            <body>
                <div class="container">
                    <div class="modal-body border border-dark rounded">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="col">
                                <label for="name">Name:</label>
                                <input class="form-control mb-2 text-capitalize" type="text" name="name" id="name"
                                    value="<?php echo $data['name']; ?>">
                            </div>
                            <div class="mb-2">
                            <?php 
                                if(array_key_exists("name_null",$a)){
                                    echo '<span style="color:red">Please enter your name.';
                                }
                            ?>
                            </div>
                            <div class="col">
                                <label for="fname">First Name:</label>
                                <input class="form-control mb-2 text-capitalize" type="text" name="fname" id="fname"
                                    value="<?php echo $data['first_name']; ?>">
                            </div>
                            <div class="mb-2">
                            <?php 
                                if(array_key_exists("fname_null",$a)){
                                    echo '<span style="color:red">Please enter your first name.';
                                }
                            ?>
                            </div>
                            <div class="col">
                                <label for="lname">Last Name:</label>
                                <input class="form-control mb-2 text-capitalize" type="text" name="lname" id="lname"
                                    value="<?php echo $data['last_name']; ?>">
                            </div>
                            <div class="mb-2">
                            <?php 
                                if(array_key_exists("lname_null",$a)){
                                    echo '<span style="color:red">Please enter your last name.';
                                }
                            ?>
                            </div>
                            <div class="row dropdown">
                                <div class="col form-group">
                                    <label for="department">Department:</label>
                                    <?php
                                        $qry = "SELECT * FROM department";
                                        $result = mysqli_query($conn, $qry);
                                    ?>
                                    <select id="department" class="form-control mb-2" name="department">
                                        <?php
                                            while($row = mysqli_fetch_assoc($result)){
                                                $selected = ($row['id'] == $data['fk_departmentid']) ? 'selected' : '';
                                                echo "<option value=\"{$row['id']}\" $selected>{$row['name']}</option>";
                                            }
                                        ?>
                                    </select>
                                    <span style="color:red"><?php 
                                        if(array_key_exists("department_null",$a)){
                                            echo 'Please select department.';
                                        }
                                    ?></span>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="designation">Designation:</label>
                                        <?php
                                            $qry = "SELECT * FROM designation";
                                            $result = mysqli_query($conn, $qry);
                                        ?>
                                        <select id="designation" class="form-control mb-2" name="designation">
                                            <?php
                                                while($row = mysqli_fetch_assoc($result)){
                                                    $selected = ($row['id'] == $data['fk_designationid']) ? 'selected' : '';
                                                    echo "<option value=\"{$row['id']}\" $selected>{$row['name']}</option>";
                                                }
                                            ?>
                                        </select>
                                        <span style="color:red"><?php 
                                            if(array_key_exists("designation_null",$a)){
                                                echo 'Please select designation.';
                                            }
                                        ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="display:flex;justify-content: right;">
                                <button class="btn btn-outline-success" type="submit" name="update">Update</button>
                                <a href="employee-data.php">
                                    <button type="button" class="btn btn-outline-danger float-right">Cancel</button>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </body>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; Human Resource Management System 2024-2025.</strong>All rights reserved.
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