<?php
include('../dbconnect.php');

// Read invoice_no & invoice_id from URL
$invoice_no = isset($_GET['invoice_no']) ? $_GET['invoice_no'] : '';
$invoice_id = isset($_GET['invoice_id']) ? $_GET['invoice_id'] : '';

$invoice = [];
if ($invoice_no) {
    $query = "SELECT invoice_no, invoice_date, grand_total, payment_term, customer_name, mobileNo, address
              FROM sales_invoices
              JOIN customers ON sales_invoices.customer_id = customers.customer_id
              WHERE invoice_no = '$invoice_no' LIMIT 1";

    $result = mysqli_query($conn, $query);
    if ($result) {
        $invoice = mysqli_fetch_assoc($result);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Sales Invoice View</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="bill.css">
  </style>
</head>
<body class="p-4">
<div class="container">
  <div id="invoice">
    <h5 class="mb-2">Invoice No. <?= htmlspecialchars($invoice['invoice_no'] ?? '') ?></h5>

    <!-- Customer Info -->
    <div class="mb-3">
      <strong>Date:</strong> <?= htmlspecialchars($invoice['invoice_date'] ?? '') ?><br>
      <strong>Customer Name:</strong> <?= htmlspecialchars($invoice['customer_name'] ?? '') ?><br>
      <strong>Phone No:</strong> <?= htmlspecialchars($invoice['mobileNo'] ?? '') ?><br>
      <strong>Address:</strong> <?= htmlspecialchars($invoice['address'] ?? '') ?><br>
      <strong>Payment Type:</strong> <?= htmlspecialchars($invoice['payment_term'] ?? '') ?><br>
    </div>
    
    <!-- Buttons (outside table) -->
    <div class="d-flex justify-content-end mb-3 no-print">
      <button onclick="window.print()" class="btn btn-primary me-2">Print Bill</button>
      <button id="exportBtn" class="btn btn-success">Export to Excel</button>
    </div>




    
    <?php
    // Fetch items for the invoice (use $invoice_id passed via URL)
    $result_items = false;
    if ($invoice_id) {
        $query_items = "SELECT 
            products.product_name,
            sales.unit_price AS sales_unit_price,
            sales.quantity,
            sales.total,
            sales.discount,
            sales.tax,
            sales.created_at
        FROM sales
        JOIN products ON products.product_id = sales.product_id
        WHERE sales.invoice_id = '$invoice_id'";
        $result_items = mysqli_query($conn, $query_items);
    }
    ?>

    <!-- Product Details Table -->
    <table class="table table-bordered table-striped" id="salesTable" border="1">
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
        <?php
        if ($result_items) {
            while ($item = mysqli_fetch_assoc($result_items)) {
                // show rupee with HTML entity to avoid encoding issues
                $total_formatted = number_format((float)$item['total'], 2);
                $rate_formatted = number_format((float)$item['sales_unit_price'], 2);
                $discount = is_numeric($item['discount']) ? $item['discount'] : 0;
                $tax = is_numeric($item['tax']) ? $item['tax'] : 0;
                ?>
                <tr>
                  <td><?= htmlspecialchars($item['product_name']) ?></td>
                  <td><?= htmlspecialchars($item['quantity']) ?></td>
                  <td><?= $rate_formatted ?></td>
                  <td><?= $discount ?>%</td>
                  <td><?= $tax ?>%</td>
                  <td>&#8377;<?= $total_formatted ?></td>
                </tr>
            <?php
            }
        } else {
            echo '<tr><td colspan="6" class="text-center">No items found for this invoice.</td></tr>';
        }
        ?>
      </tbody>
    </table>

    <!-- Invoice totals -->
    <div class="text-end mt-3">
      <h5><strong>Grand Total:</strong> &#8377;<?= number_format((float)($invoice['grand_total'] ?? 0), 2) ?></h5>
    </div>
  </div>
</div>

<!-- Export to Excel script (uses BOM + UTF-8 to keep ₹ correct) -->
<script>
(function() {
  var exportBtn = document.getElementById("exportBtn");
  if (!exportBtn) return;

  exportBtn.addEventListener("click", function () {
    var table = document.getElementById("salesTable");
    if (!table) {
      alert("Table not found!");
      return;
    }

    // wrap table in a minimal html so Excel likes it
    var preHtml = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel"><head><meta charset="utf-8"></head><body>';
    var postHtml = '</body></html>';
    var html = preHtml + table.outerHTML + postHtml;

    // create a blob with BOM for Excel to detect utf-8 correctly
    var blob = new Blob(["\uFEFF", html], { type: 'application/vnd.ms-excel;charset=utf-8' });
    var url = URL.createObjectURL(blob);

    var a = document.createElement("a");
    a.href = url;
    a.download = "Sales_Report.xls";
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
  });
})();
</script>
</body>
</html>
