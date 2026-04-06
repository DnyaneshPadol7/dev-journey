<?php 
include('headerCustomer.php');
include('../dbconnect.php'); 
include('../auth.php'); 



       if ($_SESSION['role'] !== 'SuperAdmin') {
        die("<div class='alert alert-danger text-center'>
            Access Denied. You are not allowed to delete users.
           </div>

        <div style='text-align:center; margin-top:10px;'>
            <a href='viewCustomer.php' class='btn btn-primary'>Click here to go Customer List</a>
        </div>");
        }



        if(isset($_GET['customer_id'])) {
            $id = $_GET['customer_id'];
        $query = "DELETE from `customers` WHERE `customer_id` = '$id'";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Query Failed".mysqli_error());
        }
        else{
          header('location:viewCustomer.php?delete_msg=You have Deleted the record.');
        }
    }


?>