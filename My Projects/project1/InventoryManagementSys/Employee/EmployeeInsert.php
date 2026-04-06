<?php
include_once '../dbconnect.php'; 


if(isset($_POST['add_userinfo'])){

        $name = $_POST['username'];
        $email = $_POST['email'];
        $mobile = $_POST['mobileNo'];
        $pass = $_POST['password'];
        $role = ['Anonymous'];

    if ($name=="" || empty($name)) {
        header('location:Employee.php?message=Empty fields not allowed');
    }else if ($email=="" || empty($email)) {
        header('location:Employee.php?message=Empty fields not allowed');
    }else if ($mobile=="" || empty($mobile)) {
        header('location:Employee.php?message=Empty fields not allowed');
    }else if ($pass=="" || empty($pass)) {
        header('location:Employee.php?message=Empty fields not allowed');
    }else if ($role=="" || empty($role)) {
        header('location:Employee.php?message=Empty fields not allowed');
    }else {
        $query = "insert into `userinfo` (`username`,`email`,`mobileNo`,`password`,`Anonymous`) values('$name','$email','$mobile','$pass','$role')";
       $result = mysqli_query($conn,$query);
        
        if (!$result ) {
            die("Request failed".$mysqli_error());
        }else {
            header('location:Employee.php?insert_msg=Updated Successfully');
        }
    }

}