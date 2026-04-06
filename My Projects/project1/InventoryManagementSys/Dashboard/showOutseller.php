<?php
include('../dbconnect.php');

// Base query
$sql = "SELECT i.invoice_id, i.invoice_date, i.grand_total, i.payment_term, 
               s.seller_Name, s.mobileNo,
               COALESCE(SUM(p.amount_paid), 0) AS total_paid
        FROM purchase_invoices i
        JOIN sellers s ON i.seller_id = s.seller_id
        LEFT JOIN purchase_payment p ON i.invoice_id = p.invoice_id
        WHERE i.is_deleted = 0";

// Filters
if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $from = $_GET['from_date'];
    $to = $_GET['to_date'];
    $sql .= " AND i.invoice_date BETWEEN '$from' AND '$to'";
}

if (!empty($_GET['seller'])) {
    $seller = $_GET['seller'];
    $sql .= " AND s.seller_Name LIKE '%$seller%'";
}

if (!empty($_GET['payment_type'])) {
    $ptype = $_GET['payment_type'];
    $sql .= " AND i.payment_term = '$ptype'";
}

$sql .= " GROUP BY i.invoice_id";

// Handle status
$status = $_GET['status'] ?? '';
if ($status === 'unpaid') {
    $sql .= " HAVING i.grand_total = COALESCE(SUM(p.amount_paid), 0)";
} elseif ($status === 'partial') {
    $sql .= " HAVING COALESCE(SUM(p.amount_paid), 0) > 0 
                    AND i.grand_total > COALESCE(SUM(p.amount_paid), 0)";
} else {
    $sql .= " HAVING i.grand_total > COALESCE(SUM(p.amount_paid), 0)";
}

$result = $conn->query($sql);
if (!$result) {
    die("SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Outstanding Invoices</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../Customer/viewCustomer.css">
</head>
<body class="p-4">

  <label class="form-label fs-5 fw-bold mb-3">Seller Outstanding Invoices</label>

  <!-- Filter Form -->
  <form method="GET" class="mb-4">
    <div class="row g-3">
      <div class="col-md-2">
        <label>From Date</label>
        <input type="date" name="from_date" class="form-control" value="<?= $_GET['from_date'] ?? '' ?>">
      </div>
      <div class="col-md-2">
        <label>To Date</label>
        <input type="date" name="to_date" class="form-control" value="<?= $_GET['to_date'] ?? '' ?>">
      </div>
      <div class="col-md-2">
        <label>Customer Name</label>
        <input type="text" name="customer" class="form-control" placeholder="Enter Name" value="<?= $_GET['customer'] ?? '' ?>">
      </div>
      <div class="col-md-2">
        <label>Payment Type</label>
        <select name="payment_type" class="form-control">
          <option value="">All</option>
          <option value="cash" <?= (($_GET['payment_type'] ?? '')=='cash')?'selected':'' ?>>Cash</option>
          <option value="credit" <?= (($_GET['payment_type'] ?? '')=='credit')?'selected':'' ?>>Credit</option>
        </select>
      </div>
      <div class="col-md-2">
        <label>Customer Status</label>
        <select name="status" class="form-control">
          <option value="">All</option>
          <option value="unpaid" <?= (($_GET['status'] ?? '')=='unpaid')?'selected':'' ?>>Unpaid</option>
          <option value="partial" <?= (($_GET['status'] ?? '')=='partial')?'selected':'' ?>>Partial</option>
        </select>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100 me-2">Filter</button>
        <a href="<?= basename($_SERVER['PHP_SELF']) ?>" class="btn btn-secondary w-100">Reset</a>
      </div>
    </div>
  </form>

  <!-- Table -->
  <table class="table table-bordered table-striped text-center align-middle">
    <thead class="table-light">
      <tr>
        <th>Invoice ID</th>
        <th>Date</th>
        <th>Seller</th>
        <th>Phone</th>
        <th>Total</th>
        <th>Status</th>
        <th>Remaining</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): 
        $remaining = $row['grand_total'] - $row['total_paid'];
      ?>
      <tr>
        <td><?= $row['invoice_id'] ?></td>
        <td><?= $row['invoice_date'] ?></td>
        <td><?= $row['seller_Name'] ?></td>
        <td><?= $row['mobileNo'] ?></td>
        <td>₹<?= number_format($row['grand_total'], 2) ?></td>
        <td>
          <?php if($remaining == $row['grand_total']): ?>
            <span class="badge bg-danger">Unpaid</span>
          <?php else: ?>
            <span class="badge bg-warning text-dark">Partial</span>
          <?php endif; ?>
        </td>
        <td>₹<?= number_format($remaining, 2) ?></td>
        <td>
          <a href="sellerPayment1.php?invoice_id=<?= $row['invoice_id'] ?>" class="btn btn-sm btn-primary">Make Payment</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

</body>
</html>
