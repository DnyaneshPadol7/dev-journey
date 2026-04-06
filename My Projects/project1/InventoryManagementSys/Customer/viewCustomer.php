<?php
include('../auth.php');  
include('../dbconnect.php'); 

// Get filters from GET (fixed variable names)
$idFilter = $_GET['id'] ?? '';
$nameFilter = $_GET['customer'] ?? '';
$addressFilter = $_GET['address'] ?? '';
$dateFrom = $_GET['from_date'] ?? '';
$dateTo = $_GET['to_date'] ?? '';
$outstandingFilter = $_GET['outstanding'] ?? '';

// Build WHERE
$where = [];
if (!empty($idFilter)) {
    $where[] = "c.customer_id LIKE '%" . mysqli_real_escape_string($conn, $idFilter) . "%'";
}
if (!empty($nameFilter)) {
    $where[] = "c.customer_name LIKE '%" . mysqli_real_escape_string($conn, $nameFilter) . "%'";
}
if (!empty($addressFilter)) {
    $where[] = "c.address LIKE '%" . mysqli_real_escape_string($conn, $addressFilter) . "%'";
}
if (!empty($dateFrom) && !empty($dateTo)) {
    $where[] = "i.invoice_date BETWEEN '" . mysqli_real_escape_string($conn, $dateFrom) . "' AND '" . mysqli_real_escape_string($conn, $dateTo) . "'";
}
$whereSql = "";
if (count($where) > 0) {
    $whereSql = "WHERE " . implode(" AND ", $where);
}

// HAVING for outstanding
$having = "";
if ($outstandingFilter == "positive") {
    $having = "HAVING outstanding > 0";
} elseif ($outstandingFilter == "negative") {
    $having = "HAVING outstanding < 0";
} elseif ($outstandingFilter == "zero") {
    $having = "HAVING outstanding = 0";
} elseif ($outstandingFilter == "pending") {
    $having = "HAVING outstanding > 0";
}

// Query
$query = "
    SELECT c.customer_id, c.customer_name, c.mobileNo, c.email, c.address,
           (COALESCE(SUM(i.grand_total),0) - COALESCE(SUM(p.amount_paid),0)) AS outstanding
    FROM customers c
    LEFT JOIN sales_invoices i ON c.customer_id = i.customer_id
    LEFT JOIN sale_payment p ON i.invoice_id = p.invoice_id
    $whereSql
    GROUP BY c.customer_id, c.customer_name, c.mobileNo, c.email, c.address
    $having
";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Customers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="viewCustomer.css">
</head>
<body class="product">

    <label class="form-label">Customers List</label>

    <!-- Filters -->
    <form method="GET" class="mb-3">
        <div class="row">
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

    <!-- Table -->
    <table class="table table-bordered table-striped text-center align-middle">
        <thead>
            <tr>
                <th>Customer ID</th>
                <th>Customer Name</th>
                <th>Mobile No.</th>
                <th>Email</th>
                <th>Address</th>
                <th>Outstanding</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['customer_id']); ?></td>
                    <td><?= htmlspecialchars($row['customer_name']); ?></td>
                    <td><?= htmlspecialchars($row['mobileNo']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['address']); ?></td>
                    <td>₹<?= number_format($row['outstanding'], 2); ?></td>
                    <td>
                        <a href="updateCustomer.php?customer_id=<?= $row['customer_id']; ?>" class="btn btn-success btn-sm">Update</a>
                        <a href="deleteCustomer.php?customer_id=<?= $row['customer_id']; ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this customer?');">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Flash Messages -->
    <?php if (isset($_GET['update_msg'])): ?>
        <div id="flashUpdate" class="modal fade show" style="display:block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center text-success fw-bold">
                        <?= htmlspecialchars($_GET['update_msg']); ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
            setTimeout(() => { document.getElementById('flashUpdate').style.display = 'none'; }, 3000);
        </script>
    <?php endif; ?>

    <?php if (isset($_GET['delete_msg'])): ?>
        <div id="flashDelete" class="modal fade show" style="display:block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center text-danger fw-bold">
                        <?= htmlspecialchars($_GET['delete_msg']); ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
            setTimeout(() => { document.getElementById('flashDelete').style.display = 'none'; }, 3000);
        </script>
    <?php endif; ?>

    <!-- Add Customer Button -->
    <div class="justify-content-center saveb">
        <a href="addCustomer.html">
            <button type="button" class="position-fixed bottom-0 end-0 mb-2 save-btn">Add Customer</button>
        </a>
    </div>
</body>
</html>
