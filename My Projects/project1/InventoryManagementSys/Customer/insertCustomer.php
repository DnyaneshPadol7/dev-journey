<?php
include_once '../dbconnect.php'; 


if(isset($_POST['add_customers'])){

        $name = $_POST['user_name'];
        $mobile = $_POST['mobileNo'];
        $email = $_POST['email'];
        $address = $_POST['address'];

    if ($name=="" || empty($name)) {
        header('location:viewCustomer.php?message=Empty fields not allowed');
    }else if ($mobile=="" || empty($mobile)) {
        header('location:viewCustomer.php?message=Empty fields not allowed');
    }else if ($email=="" || empty($email)) {
        header('location:viewCustomer.php?message=Empty fields not allowed');
    }else if ($address=="" || empty($address)) {
        header('location:viewCustomer.php?message=Empty fields not allowed');
    }else {
        $query = "insert into `customers` (`user_name`,`mobileNo`,`email`,`address`) values('$name','$mobile','$email','$address')";
       $result = mysqli_query($conn,$query);
        
        if (!$result ) {
            die("Request failed".$mysqli_error());
        }else {
            header('location:viewCustomer.php?insert_msg=Updated Successfully');
        }
    }

}