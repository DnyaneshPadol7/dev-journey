<?php
session_start();
include('../dbconnect.php');

if (isset($_SESSION['log_id'])) {
    $log_id = $_SESSION['log_id'];

    $update_query = "UPDATE users_logs SET logout_time = NOW() WHERE log_id = $log_id";
    mysqli_query($conn, $update_query);
}

session_unset();
session_destroy();

header('location:../Login/login.html');
exit();
?>