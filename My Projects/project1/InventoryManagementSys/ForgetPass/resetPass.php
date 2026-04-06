<?php
session_start();
include('../dbconnect.php');

if (isset($_POST['submit']) && isset($_SESSION['mobileNo'])) {
    $newpassword = $_POST['password'];
    $phone = $_SESSION['mobileNo'];

    $query = "SELECT id FROM userinfo WHERE mobileNo='$phone'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $id = $user['id'];   // ✅ sahi tarika

        $query = "UPDATE userinfo SET password='$newpassword' WHERE id='$id'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            header("location: ../ForgetPass/updatePass.php");
            exit;
        } else {
            echo "Failed to update password.";
        }
    } else {
        echo "Mobile number not found.";
    }
}
?>
