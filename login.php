<?php
require "./config.php";
$a=array();
session_start();

if(isset($_POST['login'])){
  $email = mysqli_real_escape_string($conn, $_POST['email']);
	$password =mysqli_real_escape_string($conn, $_POST['password']);
  $remeberme = isset($_POST['rememberme']) ? true : false;

  if($email == NULL){
    $a["email_null"] = true;
  }
  elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $a["email_format"] = true;
  }
  if($password == NULL){
    $a["password_null"] = true;
  }
  elseif(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $password)) {
    $a["password_format"] = true;
  }
  
  if(count($a) == 0){
    $sql="SELECT * FROM user where email = '$email' && password='$password' ";
    $result = mysqli_query($conn,$sql);

    if($result && mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      $_SESSION['login'] = true;
      $_SESSION['user_email'] = $row['email']; 
      $_SESSION['user_email1'] = $row['email']; 

      if ($remeberme) {
        // Checkbox is checked
        // Perform actions or logic for checked checkbox
        setcookie('remember_me_email', $email, time() + (24 * 60 * 60), '/');
        setcookie('remember_me_password', $password, time() + (24 * 60 * 60), '/');
      } else {
        // Checkbox is not checked
        // Perform actions or logic for unchecked checkbox
        setcookie('remember_me_email', '', time() - 3600, '/');
        setcookie('remember_me_password', '', time() - 3600, '/');
      }
    
      if ($row["istemppasschange"] == 0) {
        // Redirect to change.php if the password needs to be changed
        header("Location: recover-password.php");
        exit;
      } else {
        // Handle redirection based on user role
        if ($row["role"] == "admin") {
            header("Location: ./admin/admin.php");
            exit;
        } elseif ($row["role"] == "employee") {
            header("Location: ./employee/employee.php");
            exit;
        } else {
            // Handle the case where the role is not recognized
            header("Location: ./error.php"); // or another appropriate page
            exit;
        }
      }
    }
    else {
      $a["invalid_credentials"] = true;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body>
<body class="login-page bg-img">
<div class="login-box">
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Login Your Account</p>
      <form method="post" action="" class="">
        <div class="input-group mb-2">
          <input type="email" class="form-control" placeholder="Email" name="email" 
          value ="<?php if(isset($_POST['login'])){ echo $email; } else{if(isset($_COOKIE['remember_me_email'])){echo $_COOKIE['remember_me_email'];}}?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
          <div class="mb-2">
            <span style="color:red">
              <?php 
                if(array_key_exists("email_null",$a)){
                  echo 'Please enter your email.';
                }
                elseif(array_key_exists("email_format",$a)){
                  echo "Please enter valid email.";
                }
              ?>
            </span>
          </div>
        <div class="input-group mb-2" id="show_hide_password">
          <input type="password" class="form-control" placeholder="Password" name="password" 
          value="<?php if(isset($_POST['login'])){ echo $password; } else{if(isset($_COOKIE['remember_me_password'])){echo $_COOKIE['remember_me_password'];}} ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <a href="" class="decoration-none text-secondary"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
            </div>
          </div>
        </div>
        <div class="mb-2">
          <span style="color:red">
            <?php 
              if(array_key_exists("password_null",$a)){
                echo 'Please enter your password.';
              }
              elseif(array_key_exists("password_format",$a)){
                echo "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter,one number, and one special character.";
              }
            ?>
          </span>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
            <input type="checkbox" id="remember" name="rememberme" <?php if(isset($_COOKIE['remember_me_email']) || isset($_POST['rememberme'])){echo "checked";} ?>>
            <label for="remember">Remember Me</label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block" name="login">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <div class="mb-2" style="color:red">
        <?php 
            if(array_key_exists("invalid_credentials",$a)){
              echo "Your data not match in our database.";
            }
          ?>
        </div>
      <p class="mb-1">
        <a href="recover-password.php">I forgot my password</a>
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

<script>
  document.addEventListener('DOMContentLoaded', function(){
    const togglePassword = document.querySelector('#show_hide_password a');
    const passwordField = document.querySelector('#show_hide_password input');
    const passwordIcon = document.querySelector('#show_hide_password i');

    togglePassword.addEventListener('click',function(event){
      event.preventDefault();
      if(passwordField.type === 'password'){
        passwordField.type='text';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
      }else{
        passwordField.type='password';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
      }
    });
  });
</script>
</body>
</html>