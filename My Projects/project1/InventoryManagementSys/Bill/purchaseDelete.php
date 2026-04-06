<?php
include('../dbconnect.php');

if (isset($_GET['invoice_id'])) {
   $_id = $_GET['invoice_id'];

   // soft deleted (set flag)
   $query = "UPDATE purchase_invoices SET is_deleted = 1 WHERE invoice_id = $_id";
   $result = mysqli_query($conn, $query);

   if (!$result) {
      die("DELETE fialed: " .mysqli_error($conn));
   } else {
      header('location:showPurchase.php?delete_msg=Sales invoice deleted successfully');
      exit();
   }
}