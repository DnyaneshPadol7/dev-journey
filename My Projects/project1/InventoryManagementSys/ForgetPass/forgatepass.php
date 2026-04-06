<?php
session_start();
include('../dbconnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = mysqli_real_escape_string($conn, $_POST['mobileNo']);

    // check mobile number in userinfo
    $query = "SELECT id FROM userinfo WHERE mobileNo = '$phone'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $user_id = $user['id'];

        // OTP generate
        $otp = rand(100000, 999999);

        // insert into password_reset with mobile number
        $insert_sql = "INSERT INTO password_reset (otp) VALUES ('$otp')";
         mysqli_query($conn, $insert_sql);


        // store in session
        $_SESSION['mobileNo'] = $phone;

        // for testing purpose show OTP
        echo "OTP for $phone is: <strong>$otp</strong>";

        // redirect to validate page
        header('Location: ../ForgetPass/validateOTP.php');
        exit;
    } else {
        echo "Mobile number not found.";
    }
}
?>
