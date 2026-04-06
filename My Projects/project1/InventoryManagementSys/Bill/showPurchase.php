<?php include('../dbconnect.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase List</title>
     <link rel="stylesheet" href="bill.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body class="p-4">

    <!-- Heading -->
      <label class="form-label fs-4 fw-bold mb-3">Customer Outstanding Invoices</label>


    <!-- Filters Section -->
    <?php
    // Default WHERE condition
    $where = "WHERE purchase_invoices.is_deleted = 0";

    // Date Filter
    if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
        $from = $_GET['from_date'];
        $to = $_GET['to_date'];
        $where .= " AND invoice_date BETWEEN '$from' AND '$to'";
    }

    // Seller Name Filter
    if (!empty($_GET['customer'])) {
        $customer = $_GET['customer'];
        $where .= " AND sellers.seller_Name LIKE '%$customer%'";
    }

    // Seller Address Filter
    if (!empty($_GET['address'])) {
        $address = $_GET['address'];
        $where .= " AND sellers.address LIKE '%$address%'";
    }

    // Payment Type Filter
    if (!empty($_GET['payment_type'])) {
        $ptype = $_GET['payment_type'];
        $where .= " AND purchase_invoices.payment_term = '$ptype'";
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
                <label>Seller Name</label>
                <input type="text" name="customer" class="form-control" placeholder="Enter Name" value="<?= $_GET['customer'] ?? '' ?>">
            </div>
            <div class="col-md-2">
                <label>Seller Address</label>
                <input type="text" name="address" class="form-control" placeholder="Enter Village" value="<?= $_GET['address'] ?? '' ?>">
            </div>
            <div class="col-md-2">
                <label>Payment Type</label>
                <select name="payment_type" class="form-control">
                    <option value="">All</option>
                    <option value="cash" <?= (($_GET['payment_type'] ?? '') == 'cash') ? 'selected' : '' ?>>Cash</option>
                    <option value="credit" <?= (($_GET['payment_type'] ?? '') == 'credit') ? 'selected' : '' ?>>Credit</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100 me-2">Filter</button>
        <a href="<?= basename($_SERVER['PHP_SELF']) ?>" class="btn btn-secondary w-100">Reset</a>
      </div>
        </div>
    </form>

    <!-- Fetch Filtered Data -->
    <?php
    $query = "SELECT invoice_id, invoice_no, invoice_date, grand_total, seller_Name, mobileNo, payment_term, address
              FROM purchase_invoices 
              JOIN sellers ON purchase_invoices.seller_id = sellers.seller_id
              $where
              ORDER BY invoice_date DESC";

    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "<div class='alert alert-danger'>Query Failed: " . mysqli_error($conn) . "</div>";
        exit();
    }
    ?>

    
    <!-- ✅ Flash Messages (Modal style same as viewSeller.php) -->
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
            setTimeout(() => { document.getElementById('flashUpdate').style.display = 'none'; }, 2500);
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
            setTimeout(() => { document.getElementById('flashDelete').style.display = 'none'; }, 2500);
        </script>
    <?php endif; ?>


    <!-- Table -->
    <table class="table table-rounded table-bordered table-striped">
        <thead class="text-white" style="background-color:#5c39d1;">
            <tr>
                <th>Invoice No</th>
                <th>Seller Name</th>
                <th>Phone No</th>
                <th>Village</th>
                <th>Date</th>
                <th>Payment Type</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['invoice_no'] ?></td>
                    <td><?= $row['seller_Name'] ?></td>
                    <td><?= $row['mobileNo'] ?></td>
                    <td>
                        <?php 
                            $parts = explode("-", $row['address']);
                            $city = $parts[0];
                            echo $city;
                        ?>
                    </td>
                    <td><?= $row['invoice_date'] ?></td>
                    <td><?= $row['payment_term'] ?></td>
                    <td>₹<?= number_format($row['grand_total']) ?></td>
                    <td>
                        <a href="purchaseDetail.php?invoice_no=<?= $row['invoice_no'] ?>&invoice_id=<?= $row['invoice_id'] ?>" 
                           class="btn btn-sm btn-success">View</a>
                        <a href="editPurchase.php?invoice_no=<?= $row['invoice_no'] ?>&invoice_id=<?= $row['invoice_id'] ?>" 
                           class="btn btn-sm btn-primary">Edit</a>
                        <a href="purchaseDelete.php?invoice_id=<?= $row['invoice_id'] ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>
