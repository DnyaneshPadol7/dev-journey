<?php 
include('headerSeller.php');
include('../dbconnect.php');
include('../auth.php');

// Role check
if ($_SESSION['role'] !== 'SuperAdmin' && $_SESSION['role'] !== 'Admin') {
    die("Access Denied. You are not allowed to update Sellers. ");
}

// Fetch Seller data by id
if (isset($_GET['seller_id'])) {
    $id = $_GET['seller_id'];

    $query = "SELECT * FROM `sellers` WHERE `seller_id` = '$id'";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    } else {
        $row = mysqli_fetch_assoc($result);
    }
}
?>

<?php
// Update Seller details
if (isset($_POST['update_seller'])) {

    if (isset($_GET['id_new'])) {
        $idnew = $_GET['id_new'];
    } else {
        echo "<p style='color:red;'>❌ id_new GET me nahi aaya</p>";
    }

    $seller_Name = $_POST['seller_Name'];
    $mobile        = $_POST['mobileNo'];
    $email         = $_POST['email'];
    $address       = $_POST['address'];
    // $outstanding_balance = $_POST['outstanding_balance'];

    $query = "UPDATE `sellers` SET 
                `seller_Name` = '$seller_Name',
                `mobileNo`      = '$mobile',
                `email`         = '$email', 
                `address`       = '$address'
                -- ,`outstanding_balance` = '$outstanding_balance'
              WHERE `seller_id` = '$idnew'";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("UPDATE failed: " . mysqli_error($conn));
    } else {
        header('location: viewSeller.php?update_msg=Seller Updated Successfully');
        exit();
    }
}
?>

<div class="container mt-4" style="max-width:50vw;">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <!-- Update Form -->
            <form action="updateSeller.php?id_new=<?php echo $id; ?>" method="post">

                <div class="form-group mb-3">
                    <label for="seller_Name">Seller Name</label>
                    <input type="text" 
                           name="seller_Name" 
                           class="form-control" 
                           value="<?php echo $row['seller_Name']; ?>">
                </div>

                <div class="form-group mb-3">
                    <label for="mobileNo">Mobile No.</label>
                    <input type="text" 
                           name="mobileNo" 
                           class="form-control" 
                           value="<?php echo $row['mobileNo']; ?>">
                </div>

                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="text" 
                           name="email" 
                           class="form-control" 
                           value="<?php echo $row['email']; ?>">
                </div>

                <div class="form-group mb-3">
                    <label for="address">Address</label>
                    <input type="text" 
                           name="address" 
                           class="form-control" 
                           value="<?php echo $row['address']; ?>">
                </div>

                <!-- Agar outstanding balance use karna hai to uncomment karo
                <div class="form-group mb-3">
                    <label for="outstanding_balance">Outstanding Balance</label>
                    <input type="text" 
                           name="outstanding_balance" 
                           class="form-control" 
                           value="<?php echo $row['outstanding_balance']; ?>">
                </div>
                -->

                <div class="d-flex justify-content-between">
                    <input type="submit" 
                           class="btn btn-success" 
                           name="update_seller" 
                           value="Update">

                    <input type="button" 
                           class="btn btn-danger" 
                           value="Cancel" 
                           onclick="window.location.href='viewSeller.php';">
                </div>

            </form>

        </div>
    </div>
</div>
