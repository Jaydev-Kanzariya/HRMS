<?php
require "config.php";
$a=array();
session_start();

if (isset($_SESSION["delete"])) {
    unset($_SESSION["delete"]);
}

// Pagination settings
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Total records query
$total_query = "SELECT COUNT(*) FROM employee";
$total_result = mysqli_query($conn, $total_query);
$total_records = mysqli_fetch_array($total_result)[0];
$total_pages = ceil($total_records / $limit);

// Main query with LIMIT and OFFSET
$query = "SELECT e.id, e.first_name, e.last_name, e.email, 
        e.fk_departmentid, e.fk_designationid,
        d.name AS department_name, 
        des.name AS designation_name,
        DATE_FORMAT(e.createdate, '%d/%m/%Y') AS created_date,
        DATE_FORMAT(e.modifiedate, '%d/%m/%Y') AS modified_date,
        e.fk_userid
    FROM employee e
    LEFT JOIN department d ON e.fk_departmentid = d.id
    LEFT JOIN designation des ON e.fk_designationid = des.id
    LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $query);
$no = $offset + 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employee</title>
    <link rel="icon" href="../HRMS_LOGO.png" type="image/png">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
                        <li><hr class="dropdown-divider"></li>
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
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Employee</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <a href="add_employee.php">
                                        <button type="button" class="btn btn-outline-primary float-right"
                                            data-toggle="tooltip" data-placement="bottom" title="Add New Employee">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    </a>
                                </div>

                                <div class="card-body">
                                    <form action="" enctype="multipart/form-data" method="Post" class="text-center">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Email</th>
                                                    <th>Department</th>
                                                    <th>Designation</th>
                                                    <th>Created Date</th>
                                                    <th>Modified Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    while ($data = mysqli_fetch_row($result)) {
                                                ?>
                                                <tr id="<?php echo $data[0]; ?>">
                                                    <td><?php echo $no ?></td>
                                                    <td class="text-capitalize"><?php echo $data[1]; ?></td>
                                                    <td class="text-capitalize"><?php echo $data[2]; ?></td>
                                                    <td><?php echo $data[3]; ?></td>
                                                    <td><?php echo $data[6]; ?></td>
                                                    <td><?php echo $data[7]; ?></td>
                                                    <td><?php echo $data[8]; ?></td>
                                                    <td><?php echo $data[9]; ?></td>
                                                    <td>
                                                        <div style="display:flex;justify-content: center;">
                                                            <a href="edit_employee.php?id=<?php echo $data[0]; ?>">
                                                                <button type="button"
                                                                    class="btn btn-outline-success float-right"
                                                                    data-toggle="tooltip" data-placement="bottom"
                                                                    title="Update">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                            </a> &nbsp;&nbsp;
                                                            <a href="delete_employee.php?id=<?php echo $data[0]; ?>&fk_userid=<?php echo $data[10] ?>">
                                                                <button type="button" class="btn btn-outline-danger float-right"
                                                                    data-toggle="tooltip" data-placement="bottom" title="Delete">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                                    $no++;
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </form>
                                    <!-- Pagination controls -->
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination d-flex justify-content-end">
                                            <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                                                <a class="page-link" href="?page=<?php echo $page - 1; ?>"
                                                    aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <?php
                                                for ($i = 1; $i <= $total_pages; $i++) {
                                                    echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '">';
                                                    echo '<a class="page-link" href="?page=' . $i . '">' . $i . '</a>';
                                                    echo '</li>';
                                                }
                                            ?>
                                            <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                                                <a class="page-link" href="?page=<?php echo $page + 1; ?>"
                                                    aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
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