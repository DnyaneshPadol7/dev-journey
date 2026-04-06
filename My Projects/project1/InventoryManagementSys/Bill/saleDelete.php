<?php
include('../dbconnect.php');

if (isset($_GET['invoice_id'])) {
    $id = $_GET['invoice_id'];

    // Soft delete (set flag)
    $query = "UPDATE sales_invoices SET is_deleted = 1 WHERE invoice_id = $id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("DELETE failed: " . mysqli_error($conn));
    } else {
        header('location:showSales.php?delete_msg=Sales invoice deleted successfully');
        exit();
    }
}
?>
