<?php
include('../dbconnect.php'); // your db connection

// ---------------- FILTERS START ----------------
$where = "WHERE i.payment_term = 'credit' AND i.is_deleted = 0"; // base condition

if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $from = $_GET['from_date'];
    $to = $_GET['to_date'];
    $where .= " AND i.invoice_date BETWEEN '$from' AND '$to'";
}

if (!empty($_GET['customer'])) {
    $customer = $_GET['customer'];
    $where .= " AND c.customer_name LIKE '%$customer%'";
}

if (!empty($_GET['payment_type'])) {
    $ptype = $_GET['payment_type'];
    $where .= " AND i.payment_term = '$ptype'";
}

// ---------------- FILTERS END ----------------

// Main query with HAVING condition (for status filter)
$sql = "SELECT i.invoice_id, i.invoice_date, i.grand_total, i.payment_term, 
       c.customer_name, c.mobileNo,
       COALESCE(SUM(p.amount_paid), 0) AS total_paid
FROM sales_invoices i
JOIN customers c ON i.customer_id = c.customer_id
LEFT JOIN sale_payment p ON i.invoice_id = p.invoice_id
$where
GROUP BY i.invoice_id
HAVING (i.grand_total - COALESCE(SUM(p.amount_paid), 0)) > 0";

// Apply status filter after HAVING
if (!empty($_GET['status'])) {
    $status = $_GET['status'];

    if ($status == 'unpaid') {
        $sql .= " AND (i.grand_total - COALESCE(SUM(p.amount_paid), 0)) = i.grand_total";
    } elseif ($status == 'partial') {
        $sql .= " AND (i.grand_total - COALESCE(SUM(p.amount_paid), 0)) < i.grand_total";
    }
}

$result = $conn->query($sql);
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

  <label class="form-label fs-4 fw-bold mb-3">Customer Outstanding Invoices</label>

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

  <table class="table table-bordered table-striped text-center align-middle">
    <thead class="table-primary">
      <tr>
        <th>Invoice ID</th>
        <th>Date</th>
        <th>Customer</th>
        <th>Phone</th>
        <th>Total</th>
        <th>Status</th>
        <th>Remaining</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): 
          $remaining = $row['grand_total'] - $row['total_paid'];
        ?>
        <tr>
          <td><?= $row['invoice_id'] ?></td>
          <td><?= $row['invoice_date'] ?></td>
          <td><?= htmlspecialchars($row['customer_name']) ?></td>
          <td><?= htmlspecialchars($row['mobileNo']) ?></td>
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
            <a href="payment1.php?invoice_id=<?= $row['invoice_id'] ?>" class="btn btn-sm btn-primary">
              Make Payment
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="8" class="text-center text-muted">No records found</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

</body>
</html>
