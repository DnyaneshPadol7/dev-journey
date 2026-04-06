<?php include('../dbconnect.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="bill.css">
</head>
<body class="p-4">

    <!-- Heading -->
    <label class="form-label">Sales List</label>

    <!-- Filters -->
    <?php 
    include('../filters.php'); 
    $query = "SELECT invoice_id, invoice_no, invoice_date, grand_total, customer_name, mobileNo, payment_term, address
              FROM sales_invoices 
              JOIN customers ON sales_invoices.customer_id = customers.customer_id
              $where
              AND sales_invoices.is_deleted = 0
              ORDER BY invoice_date DESC";

    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "query failed" . mysqli_error($conn);
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

    <!-- Table -->
    <table class="table table-rounded table-bordered table-striped">
        <thead class="text-white" style="background-color:#5c39d1;">
            <tr>
                <th>Invoice No</th>
                <th>Customer Name</th>
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
                    <td><?= $row['customer_name'] ?></td>
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
                        <a href="saleDetail.php?invoice_no=<?= $row['invoice_no'] ?>&invoice_id=<?= $row['invoice_id'] ?>" 
                           class="btn btn-sm btn-success">View</a>
                        <a href="editSale.php?invoice_no=<?= $row['invoice_no'] ?>&invoice_id=<?= $row['invoice_id'] ?>" 
                           class="btn btn-sm btn-primary">Edit</a>
                        <a href="saleDelete.php?invoice_no=<?= $row['invoice_no'] ?>&invoice_id=<?= $row['invoice_id'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Are you sure you want to delete this invoice?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>
