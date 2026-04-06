<?php
include '../dbconnect.php'; 


if(isset($_POST['add_employee'])){

    $name = $_POST['userName'];
    $email = $_POST['email'];
    $mobile = $_POST['mobileNo'];
    $pass = $_POST['password'];
    $role = $_POST['role'];
    $idnew = $_POST['id'];

    if ($name=="" || empty($name)) {
        header('location:../Employee.php?message=Empty fields not allowed');
    } else if ($email=="" || empty($email)) {
         header('location:../Employee.php?message=Empty fields not allowed');
    } else if ($mobile=="" || empty($mobile)) {
         header('location:../Employee.php?message=Empty fields not allowed');
    } else if ($pass=="" || empty($pass)) {
         header('location:../Employee.php?message=Empty fields not allowed');
    } else if ($role=="" || empty($role)) {
        header('location:../Employee.php?message=Empty fields not allowed');
    } else {
        
        $query = "UPDATE `userinfo` SET 
        `userName` = '$name',
        `email` = '$email', 
        `mobileNo` = '$mobile',
        `password` = '$pass',
        `role` = '$role'
        where `id` = '$idnew'";

        $result = mysqli_query($conn,$query);
        
        if (!$result ) {
            die("Request failed".$mysqli_error($conn));
        }else {
            header('location:../Employee.php?insert_msg=User Added Successfully');
        }
    }

}

?>