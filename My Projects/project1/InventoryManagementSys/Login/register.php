<?php
session_start();

include('../dbconnect.php');

if (isset($_POST['submit'])) {
    
    $name = $_POST['username'];
    $email = $_POST['email'];
    $mobile = $_POST['mobileNo'];
    $pass = $_POST['password'];
    $role = 'Anonymous';
    $query1="SELECT * FROM userinfo WHERE username='$name'";
    $result=mysqli_query($conn,$query1);
    if(mysqli_num_rows($result)>0){
       echo "<script> alert('user name already exist try another name')</script>";
        die();
    }
    $query = "INSERT INTO userinfo(username,email,mobileNo,password,role)values('$name','$email','$mobile','$pass','$role')";
    $result = mysqli_query($conn,$query);
    if ($result) {
        header('location:registerSuccess.php');
    }else {
        die("Data not inserted".mysqli_error($conn));
    }
}


?>
