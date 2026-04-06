<?php
// Default where condition
$where = "WHERE sales_invoices.is_deleted = 0";

// Date filter
if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $from = $_GET['from_date'];
    $to = $_GET['to_date'];
    $where .= " AND invoice_date BETWEEN '$from' AND '$to'";
}

// Customer filter
if (!empty($_GET['customer'])) {
    $customer = $_GET['customer'];
    $where .= " AND customers.customer_name LIKE '%$customer%'";
}

// Address filter
if (!empty($_GET['address'])) {
    $address = $_GET['address'];
    $where .= " AND customers.address LIKE '%$address%'";
}

// Payment type filter
if (!empty($_GET['payment_type'])) {
    $ptype = $_GET['payment_type'];
    $where .= " AND sales_invoices.payment_term = '$ptype'";
}
?>

<!-- Filter Form -->
<form method="GET" class="mb-3">
  <div class="row g-3 align-items-end">
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
      <label>Customer Village</label>
      <input type="text" name="address" class="form-control" placeholder="Enter Village" value="<?= $_GET['address'] ?? '' ?>">
    </div>
    <div class="col-md-2">
      <label>Payment Type</label>
      <select name="payment_type" class="form-control">
        <option value="">All</option>
        <option value="cash" <?= (($_GET['payment_type'] ?? '')=='cash')?'selected':'' ?>>Cash</option>
        <option value="credit" <?= (($_GET['payment_type'] ?? '')=='credit')?'selected':'' ?>>Credit</option>
      </select>
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100 me-2">Filter</button>
        <a href="<?= basename($_SERVER['PHP_SELF']) ?>" class="btn btn-secondary w-100">Reset</a>
      </div>
  </div>
</form>
