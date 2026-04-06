<?php
include('.//dbconnect.php');

// Read invoice_id from URL
if (isset($_GET['invoice_no'])) {
    $invoice_no = $_GET['invoice_no'];
     $invoice_id=$_GET['invoice_id'];
    // Query 1: Get customer + invoice info
    $query = "SELECT invoice_no,invoice_date,grand_total, payment_term,customer_name, mobileNo,address
              FROM sales_invoices
              JOIN customers ON sales_invoices.customer_id = customers.customer_id
              WHERE invoice_no = '$invoice_no'";

    $result = mysqli_query($conn, $query);
    $invoice = mysqli_fetch_assoc($result);
    //  var_dump($invoice);
    //  exit();
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Sales Invoice View</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">

  <h5 class="mb-2">Invoice No.<?= $invoice['invoice_no'] ?></h5>

  <!-- Customer Info -->
  <div class="mb-3">
    <strong>Date:</strong> <?=$invoice['invoice_date']?><br>
    <strong>Customer Name:</strong> <?= $invoice['customer_name'] ?><br>
    <strong>Phone No:</strong> <?= $invoice['mobileNo'] ?><br>
    <strong>Address:</strong> <?= $invoice['address'] ?><br>
    <strong>Payment Type:</strong> <?= $invoice['payment_term'] ?><br>
  </div>
 
<?php

$query_items = "SELECT 
    products.product_name,
    sales.unit_price AS sales_unit_price,
    sales.quantity,
    sales.total,
    sales.discount,
    sales.tax,
    sales.created_at
FROM 
    sales
JOIN 
    products ON products.product_id = sales.product_id
    WHERE invoice_id='$invoice_id'";

$result_items = mysqli_query($conn, $query_items);

?>

  <!-- Product Details -->
  <table class="table table-bordered table-striped">
    <thead class="text-white" style="background-color:#5c39d1;">
      <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Rate</th>
        <th>Discount</th>
        <th>Tax</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php $datet=[];
       while($item = mysqli_fetch_assoc($result_items)): ?>
    <?php 
     
      //  $date = date('Y-m-d H:i:s', strtotime($item['created_at'])); 
      //  $datet[]=$date;
        ?>
      <tr>
        <td><?= $item['product_name'] ?></td>
        <td><?= $item['quantity'] ?></td>
        <td><?=$item['sales_unit_price']?></td>
        <td><?=$item['discount']?>%</td>
        <td><?=$item['tax']?>%</td>
       
        <td>₹<?= number_format($item['total']) ?></td>
      </tr>
      
      <?php endwhile; ?>
    </tbody>
  </table>

  <!-- Invoice totals -->
  <div class="text-end">
   
    <h5><strong>Grand Total:</strong> ₹<?= number_format($invoice['grand_total']) ?></h5>
  </div>

</div>
</body>
</html>