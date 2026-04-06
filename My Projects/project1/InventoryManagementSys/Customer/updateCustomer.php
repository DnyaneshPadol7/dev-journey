<?php 
include('headerCustomer.php');
include('../dbconnect.php');
include('../auth.php');

// Role check
if ($_SESSION['role'] !== 'SuperAdmin' && $_SESSION['role'] !== 'Admin') {
    die("Access Denied. You are not allowed to update customers. ");
}

// Fetch customer data by id
if (isset($_GET['customer_id'])) {
    $id = $_GET['customer_id'];

    $query = "SELECT * FROM `customers` WHERE `customer_id` = '$id'";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    } else {
        $row = mysqli_fetch_assoc($result);
    }
}
?>

<?php
// Update customer details
if (isset($_POST['update_customer'])) {

    if (isset($_GET['id_new'])) {
        $idnew = $_GET['id_new'];
    } else {
        echo "<p style='color:red;'>❌ id_new GET me nahi aaya</p>";
    }

    $customer_name = $_POST['customer_name'];
    $mobile        = $_POST['mobileNo'];
    $email         = $_POST['email'];
    $address       = $_POST['address'];
    // $outstanding_balance = $_POST['outstanding_balance'];

    $query = "UPDATE `customers` SET 
                `customer_name` = '$customer_name',
                `mobileNo`      = '$mobile',
                `email`         = '$email', 
                `address`       = '$address'
                -- ,`outstanding_balance` = '$outstanding_balance'
              WHERE `customer_id` = '$idnew'";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("UPDATE failed: " . mysqli_error($conn));
    } else {
        header('location: viewCustomer.php?update_msg=Customer Updated Successfully');
        exit();
    }
}
?>

<div class="container mt-4" style="max-width:70vw;">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <!-- Update Form -->
            <form action="updateCustomer.php?id_new=<?php echo $id; ?>" method="post">

                <div class="form-group mb-3">
                    <label for="customer_name">Customer Name</label>
                    <input type="text" 
                           name="customer_name" 
                           class="form-control" 
                           value="<?php echo $row['customer_name']; ?>">
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
                           name="update_customer" 
                           value="UPDATE">

                    <input type="button" 
                           class="btn btn-danger" 
                           value="Cancel" 
                           onclick="window.location.href='viewCustomer.php';">
                </div>

            </form>

        </div>
    </div>
</div>
