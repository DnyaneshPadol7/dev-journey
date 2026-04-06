<?php
session_start();
include('../dbconnect.php');

if (isset($_POST['otp']) && isset($_SESSION['mobileNo'])) {
    $otp = $_POST['otp'];              
    $phone = $_SESSION['mobileNo'];    
   
   $otp_sql = "SELECT * FROM password_reset WHERE otp = '$otp' ORDER BY created_at DESC LIMIT 1;";

        $otp_result = mysqli_query($conn, $otp_sql);

        if (mysqli_num_rows($otp_result) > 0) {

           header('location:../forgetPass/resetPass.html');
           die();
        } else {
            echo "<p style='color:red;'> Invalid or expired OTP.</p>";
        
    } 
    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../Login/login.css">
    <link rel="stylesheet" href="../common.css">
</head>

<body class="d-flex justify-content-center align-items-center ">
    <div class="containerPass">
        <form id="myForm" action="" method="post">
            <h1>Validate OTP</h1>

            <label for="password">Enter OTP</label>
            <input type="text" name="otp" id="otp" required>
            <button name="submit">Verify OTP</button>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>