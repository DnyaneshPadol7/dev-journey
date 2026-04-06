<?php 

include('../dbconnect.php'); 
include('../auth.php'); 



        if ($_SESSION['role']  !== 'SuperAdmin'){
            die("Access Denied. You are not allowed to delete users.");
        }

        if(isset($_GET['id'])) {
            $id = $_GET['id'];
        $query = "DELETE from `userinfo` WHERE `id` = '$id'";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Query Failed".mysqli_error());
        }
        else{
            header('location:Employee.php?delete_msg=You have Deleted the record.');
        }
    }


?>