<?php
require "config.php";
$a=array();
session_start();
 
define('ENCRYPTION_KEY', 'your-32-byte-long-encryption-key'); // Must be 16, 24, or 32 bytes for AES-256
define('ENCRYPTION_METHOD', 'aes-256-cbc');

function encrypt($data, $key) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(ENCRYPTION_METHOD));
    $encrypted = openssl_encrypt($data, ENCRYPTION_METHOD, $key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decrypt($data, $key) {
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, ENCRYPTION_METHOD, $key, 0, $iv);
}

$selectedDepartment = isset($_POST['department']) ? $_POST['department'] : '';
$selectedDesignation = isset($_POST['designation']) ? $_POST['designation'] : '';

if (isset($_POST['insert'])) {
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $fname = mysqli_real_escape_string($conn,$_POST['fname']);
    $lname = mysqli_real_escape_string($conn,$_POST['lname']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $department = $_POST['department'];
    $designation = $_POST['designation'];
    $password = mysqli_real_escape_string($conn,$_POST['password']);

    if($email == NULL){
        $a["email_null"] = true;
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $a["email_format"] = true;
    }
  
    if($name == NULL){
        $a["name_null"] = true;
    }    

    if($fname == NULL){
        $a["fname_null"] = true;
    }    

    if($lname == NULL){
        $a["lname_null"] = true;
    }    
    
    if ($department == 'Select' || empty($department)) {
        $a["department_null"] = true;
    }

    if ($designation == 'Select' || empty($designation)) {
        $a["designation_null"] = true;
    }

    if($password == NULL){
        $a["password_null"] = true;
    }
    elseif(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $password)) {
        $a["password_format"] = true;
    }
    
   
    if(count($a) == 0){
        $sql="SELECT email FROM employee where email = '$email'";
        $result = mysqli_query($conn,$sql);
  
        $rows = mysqli_num_rows($result);
        if ($rows > 0) {
            $a["recordExist"] = true;
        } else {
            // $pass = password_hash($password, PASSWORD_DEFAULT);
            $encryptedPassword = encrypt($password, ENCRYPTION_KEY);

            $sql1 = "INSERT INTO user(email,password,role)VALUES('$email','$encryptedPassword','employee')";
            if (mysqli_query($conn, $sql1)) {
                // Get the id of the newly inserted user
                $fk_userid = mysqli_insert_id($conn);

                // Insert into the `employee` table
                $sqlInsert = "INSERT INTO employee (fk_userid, fk_departmentid, fk_designationid, name, first_name, last_name, email) 
                              VALUES ('$fk_userid', '$department', '$designation', '$name', '$fname', '$lname', '$email')";
                if (mysqli_query($conn, $sqlInsert)) {
                    $_SESSION["insert"] = true;
                    header("Location: employee-data.php");
                    exit(); 
                } else {
                    $a["insert_error"] = "Failed to insert employee record.";
                }
            } else {
                $a["insert_error"] = "Failed to insert user record.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Employee</title>
    <link rel="icon" href="../HRMS_LOGO.png" type="image/png">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <!-- <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css"> -->
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <!-- <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css"> -->
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <!-- <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css"> -->

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
        <div class="content-wrapper mb-5">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="">New Employee</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container">
                <div class="modal-body border border-dark rounded">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="col">
                            <label for="name">Name:</label><br>
                            <input class="form-control mb-2 text-capitalize" type="text" name="name" id="name"
                                placeholder="Enter Your Full Name" value="<?php if(isset($_POST['insert'])){ echo $name; }  ?>">
                        </div>
                        <div class="mb-2">
                            <?php 
                                if(array_key_exists("name_null",$a)){
                                    echo '<span style="color:red">Please enter your name.';
                                }
                            ?>
                        </div>
                        <div class="col">
                            <label for="fname">First Name:</label><br>
                            <input class="form-control mb-2 text-capitalize" type="text" name="fname" id="name"
                                placeholder="Enter Your First Name" value="<?php if(isset($_POST['insert'])){ echo $fname; }  ?>">
                        </div>
                        <div class="mb-2">
                            <?php 
                                if(array_key_exists("fname_null",$a)){
                                    echo '<span style="color:red">Please enter your first name.';
                                }
                            ?>
                        </div>
                        <div class="col">
                            <label for="lname">Last Name:</label><br>
                            <input class="form-control mb-2 text-capitalize" type="text" name="lname" id="number"
                                placeholder="Enter Your Last Name"
                                value="<?php if(isset($_POST['insert'])){ echo $lname; }  ?>">
                        </div>
                        <div class="mb-2">
                            <?php 
                                if(array_key_exists("lname_null",$a)){
                                    echo '<span style="color:red">Please enter your last name.';
                                }
                            ?>
                        </div>
                        <div class="col">
                            <label for="email">Email:</label><br>
                            <input class="form-control mb-2" type="email" name="email" id="email"
                                placeholder="Enter Your Email"
                                value="<?php if(isset($_POST['insert'])){ echo $email; }  ?>">
                        </div>
                        <div class="mb-2">
                            <span style="color:red"><?php 
                                if(array_key_exists("email_null",$a)){
                                    echo 'Please enter your email.';
                                }
                                elseif(array_key_exists("email_format",$a)){
                                    echo "Please enter valid email.";
                                }
                            ?></span>
                        </div>
                        <div class="col">
                            <label for="password">Temporary Password:</label>
                        </div>
                        <div class="input-group mb-3" id="show_hide_password">
                            <input type="text" class="form-control" minlength="8" name="password" id="password"
                                placeholder="Temporary Password Generate" aria-label="Password"
                                aria-describedby="generatePasswordBtn"
                                value="<?php if(isset($_POST['insert'])){ echo $password; }  ?>">
                            <div class="input-group-append">
                            <!-- <div class="input-group-text">
                                <a href="" class="decoration-none text-secondary"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                            </div> -->
                                <button class="btn btn-outline-primary" type="button" id="generatePasswordBtn"
                                    onclick="generatePassword()">
                                    Auto Generate Password
                                </button>
                            </div>
                        </div>
                        <div class="mb-2">
                            <span style="color:red">
                                <?php 
                                    if(array_key_exists("password_null",$a)){
                                        echo 'Please auto generate password.';
                                    }
                                    elseif(array_key_exists("password_format",$a)){
                                        echo "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter,one number, and one special character.";
                                    }
                                ?>
                            </span>
                        </div>
                        <div class="row dropdown">
                            <div class="col form-group">
                                <label for="department">Department:</label>
                                <?php
                                    $qry = "SELECT * FROM department";
                                    $result = mysqli_query($conn, $qry);
                                ?>
                                <select id="department" class="form-control mb-2" name="department">
                                    <option value="Select">Select</option>
                                    <?php
                                        while($row = mysqli_fetch_row($result)){
                                    ?>
                                    <option value="<?php echo $row[0]?>"<?php echo ($row[0] == $selectedDepartment) ? 'selected' : ''; ?>>
                                        <?php echo $row[1];?>
                                    </option>
                                    <?php
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
                                        <option value="Select">Select</option>
                                        <?php
                                            while($row = mysqli_fetch_row($result)){
                                        ?>
                                        <option value="<?php echo $row[0]?>"<?php echo ($row[0] == $selectedDesignation) ? 'selected' : ''; ?>>
                                            <?php echo $row[1]; ?>
                                        </option>
                                        <?php
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
                            <button type="submit" class="btn btn-outline-success" id="insert" name="insert">
                                ADD
                            </button>
                            <a href="employee-data.php">
                                <button type="button" class="btn btn-outline-danger float-right">Cancel</button>
                            </a>
                        </div>
                    </form>
                    <div class="mb-2" >
                        <?php 
                            if(array_key_exists("recordExist",$a)){
                                echo '<span style="color:red">Record exist in our system.';
                            }
                        ?>
                    </div>
                </div>
            </div>
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
    <!-- <script src="plugins/sparklines/sparkline.js"></script> -->
    <!-- JQVMap -->
    <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <!-- <script src="plugins/jquery-knob/jquery.knob.min.js"></script> -->
    <!-- daterangepicker -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <!-- <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script> -->
    <!-- Summernote -->
    <!-- <script src="plugins/summernote/summernote-bs4.min.js"></script> -->
    <!-- overlayScrollbars -->
    <!-- <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script> -->
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard.js"></script>
    <script>
    function generatepass(length = 8, usechars = true, usenumbers = true, usesymbols = true) {
        const charset = [];

        if (usechars) {
            charset.push('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        }
        if (usenumbers) {
            charset.push('0123456789');
        }
        if (usesymbols) {
            charset.push('!@#$%^&*()-_=+[{]};:",<.>/?|');
        }
        if (charset.length === 0) {
            throw new Error('At least one character set must be selected.');
        }

        const allchars = charset.join('');
        let password = '';

        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * allchars.length);
            password += allchars[randomIndex];
        }

        return password;
    }

    function generatePassword() {
        const passwordField = document.getElementById('password');
        try {
            const newPassword = generatepass();
            passwordField.value = newPassword;
        } catch (error) {
            console.error(error.message);
            alert(error.message);
        }
    }
    </script>
</body>

</html>