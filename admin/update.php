<?php
include "config.php"; // Include database connection file

//update doctor data
if (isset($_POST['update'])) {
    // Retrieve data from the form
    $id = $_POST['id'];
    $name = $_POST['fname'];
    $phone = $_POST['no'];
    $email = $_POST['email'];
    $photo = $_FILES["uploadfile"]["name"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $folder = "./doctor_photo/" . $photo;

    // Prepare and execute the update query
    $query = "UPDATE doctor_master SET Full_name='$name', Phone='$phone', Email='$email', photo='$photo' WHERE id='$id'";
    $result = mysqli_query($conn, $query);

    if (move_uploaded_file($tempname, $folder)) {
        echo "<h3>upload image!</h3>";
      }else{
        echo "<h3>failed to upload image!</h3>";
      }

    if($result) {
        header("location:doctor-data.php");
    } else {
        echo "Error updating record ";
    }
}

//update user data
if (isset($_POST['submit'])) {
    // Retrieve data from the form
    $id = $_POST['id'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone = $_POST['no'];

    // Prepare and execute the update query
    $query = "UPDATE user_master SET name='$name', gender='$gender', Email='$email', phone='$phone' WHERE id='$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("location:user-data.php");
    } else {
        echo "Error updating record";
    }
}

//update appoinmtment data
if(isset($_POST['edit'])){

    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $DoctorName = $_POST['DoctorName'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $appo = $_POST['appo'];
    $state_input = $_POST['state_input'];
    $district_input = $_POST['district_input'];
    // $msg = $_POST['message'];

    $qry = "UPDATE appointment SET name='$name',phone='$phone',doctor_name='$DoctorName',gender='$gender',email='$email',dob='$dob',appointment_date='$appo',state='$state_input',city='$district_input' WHERE id='$id'";
    $result = mysqli_query($conn, $qry);

    if ($result) {
        header("location:appointment-data.php");
    } else {
        echo "Error updating record";
    }
}
?>
