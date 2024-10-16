<?php
require "./config.php";
$a=array();
session_start();

if(isset($_POST['submit']))
{
    $email = mysqli_real_escape_string($conn,$_POST['email']);
	$password =mysqli_real_escape_string($conn, $_POST['password']);
    // $currentpass = mysqli_real_escape_string($conn,$_POST['currentpass']);
    $newpassword = mysqli_real_escape_string($conn,$_POST['newPassword']);
    $confirmnewpassword = mysqli_real_escape_string($conn,$_POST['confirmPassword']);
        
    if($email == NULL){
        $a["email_null"] = true;
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $a["email_format"] = true;
    }

    if($password == NULL){
        $a["currentpass_null"] = true;
    }
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $a["currentpass_format"] = true;
    }

    if($newpassword == NULL){
        $a["password_null"] = true;
    }
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $newpassword)) {
        $a["password_format"] = true;
    }

    if($confirmnewpassword == NULL){
        $a["repassword_null"] = true;
      }
    elseif($newpassword != $confirmnewpassword){
        $a["passmatch"] = true;
    }

    if(count($a) == 0){
        $result = mysqli_query($conn, "SELECT * FROM user WHERE email='$email' && password='$password' ");
        $res = mysqli_num_rows($result);
        if($res == 1)
        {
            // if($newpassword != $confirmnewpassword){
            //     // echo "Passwords do not match";
            //     $a['match'] = true; 
            // }
            $sql=mysqli_query($conn, "UPDATE user SET istemppasschange = 1, password='$newpassword' where email='$email' && password='$password' ");
            header("Location: login.php");
        }else{
            $a['alredyExist'] = true;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recover Password</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <style>
    #p {
        color: red;
    }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
        <b></b>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Recover Your Password</p>
                <form action="" method="post" onSubmit="return validatePassword()" name="frmChange">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email" id="email" 
                        value="<?php if(isset($_POST['submit'])){ echo $email; }  ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1">
                        <span style="color:red"><?php 
                          if(array_key_exists("email_null",$a)){
                            echo 'Please enter your email.';
                          }
                          elseif(array_key_exists("email_format",$a)){
                            echo "Please enter valid email.";
                          }
                          elseif(array_key_exists("alredyExist",$a)){
                            echo "Record does not exist, please check your email.";
                          }
                        ?></span>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Current Password" name="password"
                            id="password" value="<?php if(isset($_POST['submit'])){ echo $password; }  ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1">
                        <?php 
                          if(array_key_exists("currentpass_null",$a)){
                            echo '<span style="color:red">Please enter your password.';
                          }
                          elseif(array_key_exists("currentpass_format",$a)){
                            echo '<span style="color:red">Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter,one number, and one special character.';
                          }
                        ?>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="newPassword"
                            id="newPassword" value="<?php if(isset($_POST['submit'])){ echo $newpassword; }  ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1">
                        <?php 
                          if(array_key_exists("password_null",$a)){
                            echo '<span style="color:red">Please enter your password.';
                          }
                          elseif(array_key_exists("password_format",$a)){
                            echo '<span style="color:red">Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter,one number, and one special character.';
                          }
                        ?>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Confirm Password" name="confirmPassword" 
                        id="confirmPassword" value="<?php if(isset($_POST['submit'])){ echo $confirmnewpassword; }?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <?php 
                          if(array_key_exists("repassword_null",$a)){
                            echo '<span style="color:red">Please enter your confirm password.';
                          }
                          elseif(array_key_exists("passmatch",$a)){
                            echo '<span style="color:red">Password and retype password does not match.';
                          }
                          elseif(array_key_exists("password_format",$a)){
                            echo '<span style="color:red">Please enter valid confirm password.';
                          }
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block" name="submit">Change
                                password</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <div class="mb-2">
                    <?php 
                        if(array_key_exists("msg",$a)){
                        echo '<span style="color:red">Please enter valid Id/Password.';
                        }
                    ?>
                </div>

                <p class="mt-3 mb-1">
                    <a href="login.php">Login</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
</body>

</html>