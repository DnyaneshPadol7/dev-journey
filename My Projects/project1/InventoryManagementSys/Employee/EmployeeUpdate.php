<?php 
include_once('EmployeeHeader.php'); 
include('../dbconnect.php');
include('../auth.php');

 


if ($_SESSION['role'] !== 'SuperAdmin' && $_SESSION['role'] !== 'Admin') {
    die("Access Denied. You are not allowed to update users. ");
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    
            $query ="SELECT * from `userinfo` where `id` = '$id'";
            $result = mysqli_query($conn,$query);
            
            if (!$result) {
                die("query failed" .mysqli_error($conn));
            }else{
                 $row = mysqli_fetch_assoc($result);
            }
               
       
}

?>

    <?php
    
    if (isset($_POST['update_userinfo'])) {
  
        if (isset($_GET['id_new'])) {
            $idnew = $_GET['id_new'];
        } else {
        echo "<p style='color:red;'>❌ id_new GET me nahi aaya</p>";
    }

        $name = $_POST['userName'];
        $email = $_POST['email'];
        $mobile = $_POST['mobileNo'];
        $pass = $_POST['password'];
        $role = $_POST['role'];

        $query = "UPDATE `userinfo` SET 
        `userName` = '$name',
        `email` = '$email', 
        `mobileNo` = '$mobile',
        `password` = '$pass',
        `role` = '$role'
        where `id` = '$idnew'";

        $result = mysqli_query($conn,$query);
        
            
            if (!$result) {
                die("UPDATE failed:"  .mysqli_error($conn));
            } else {
                header('location: Employee.php?update_msg=Updated Successfully');
                exit();
            }

    }
    
    ?>


<div class="container mt-4" style="max-width:50vw;">
    <div class="row justify-content-center">
        <div class="col-md-10"> <!-- form ki width control -->
            <form action="EmployeeUpdate.php?id_new=<?php echo $id; ?>" method="post">
                
                <div class="form-group mb-3">
                    <label for="username" class="form-label">User Name</label>
                    <input type="text" name="userName" class="form-control" 
                           value="<?php echo $row['userName'] ?>">
                </div>

                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" name="email" class="form-control" 
                           value="<?php echo $row['email'] ?>">
                </div>

                <div class="form-group mb-3">
                    <label for="mobileNo" class="form-label">Mobile NO.</label>
                    <input type="text" name="mobileNo" class="form-control" 
                           value="<?php echo $row['mobileNo'] ?>">
                </div>

                <div class="form-group mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="text" name="password" class="form-control" 
                           value="<?php echo $row['password'] ?>">
                </div>

                <div class="form-group mb-3">
                    <label for="role" class="form-label">Assign Role</label>
                <select name="role" class="form-control">
                    <option value="Anonymous" <?= ($row['role']=="Anonymous")?"selected":"" ?>>Anonymous</option>
                    <option value="Employee" <?= ($row['role']=="Employee")?"selected":"" ?>>Employee</option>
                    <?php if ($_SESSION['role'] === 'SuperAdmin'){ ?>
                    <option value="Admin" <?= ($row['role']=="Admin")?"selected":"" ?>>Admin</option>
                    <?php } ?>
                </select>
            </div>


                <div class="d-flex justify-content-between">
                    <input type="submit" class="btn btn-success" name="update_userinfo" value="Update">
                    <input type="button" class="btn btn-danger" value="Cancel" onclick="window.location.href='Employee.php';">
                </div>
            </form>
        </div>
    </div>
</div>
