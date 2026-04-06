<?php
session_start();
include('../dbconnect.php');

if (isset($_POST['submit'])) {
    $name = $_POST['userName'];
    $password = $_POST['password'];

    // 🔍 Check user credentials
    $query = "SELECT * FROM userinfo WHERE userName='$name' AND password='$password'";
    $result = mysqli_query($conn, $query);
    $rows = mysqli_num_rows($result);

    // ❌ Invalid username or password
    if ($rows != 1) {
        // Redirect back to login page with error message in URL
        header('Location: ../Login/login.html?error=invalid');
        exit();
    } 
    // ✅ Valid user found
    else {
        $row = mysqli_fetch_assoc($result);

        // Session data set kar rahe hain
        $_SESSION['userName'] = $name;
        $_SESSION['role'] = $row['role'];
        $_SESSION['user_id'] = $row['id'];

        // User login log entry
        $log_query = "INSERT INTO users_logs (user_id) VALUES ({$row['id']})";
        mysqli_query($conn, $log_query);
        $_SESSION['log_id'] = mysqli_insert_id($conn);

        // 🔀 Redirect according to role
        switch ($_SESSION['role']) {
            case 'Anonymous':
                header('Location: ../Employee/Anonymous.php');
                break;
            case 'Employee':
                header('Location: ../Employee/Employee.html');
                break;
            case 'Admin':
                header('Location: ../Employee/Admin.html');
                break;
            case 'SuperAdmin':
                header('Location: ../Admin/SuperAdmin.php');
                break;
            default:
                header('Location: ../Login/login.html');
        }

        exit();
    }
}
?>
