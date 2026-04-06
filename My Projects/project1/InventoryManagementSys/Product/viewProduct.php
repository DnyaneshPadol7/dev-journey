<?php
include('../dbconnect.php'); // DB connection

// ------------------------
// Filters Logic Start
// ------------------------

// Default WHERE condition
$where = "WHERE 1"; // show all rows initially

// Filter: Product Name
if (!empty($_GET['product_name'])) {
    $product_name = $_GET['product_name'];
    $where .= " AND product_name LIKE '%$product_name%'";
}

// Filter: Category
if (!empty($_GET['category'])) {
    $category = $_GET['category'];
    $where .= " AND category LIKE '%$category%'";
}

// Filter: Stock Availability
if (!empty($_GET['stock'])) {
    if ($_GET['stock'] == 'in') {
        $where .= " AND stock_left > 0";
    } elseif ($_GET['stock'] == 'out') {
        $where .= " AND stock_left = 0";
    }
}

// ------------------------
// Fetch Products
// ------------------------
$sql = "SELECT * FROM products $where ORDER BY product_name ASC";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Query failed: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="viewproduct.css">
</head>
<body class="p-4">

    <!-- Heading -->
   <label class="form-label">Products List</label>
    <!-- ------------------------ -->
    <!-- Filters Form -->
    <!-- ------------------------ -->
    <form method="GET" class="mb-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label>Product Name</label>
                <input type="text" name="product_name" class="form-control" placeholder="Enter Name" value="<?= $_GET['product_name'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label>Category</label>
                <input type="text" name="category" class="form-control" placeholder="Enter Category" value="<?= $_GET['category'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label>Stock Status</label>
                <select name="stock" class="form-control">
                    <option value="">All</option>
                    <option value="in" <?= (($_GET['stock'] ?? '') == 'in') ? 'selected' : '' ?>>In Stock</option>
                    <option value="out" <?= (($_GET['stock'] ?? '') == 'out') ? 'selected' : '' ?>>Out of Stock</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
    <a href="<?= basename($_SERVER['PHP_SELF']) ?>" class="btn btn-secondary flex-fill">Reset</a>
</div>

        </div>
    </form>

    <!-- ------------------------ -->
    <!-- Products Table -->
    <!-- ------------------------ -->
    <table class="table table-rounded table-bordered table-striped text-center align-middle">
        <thead class="text-white" style="background-color:#5c39d1;">
            <tr>
                <th>Product Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Stock Left</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['stock_left']) ?></td>
                    <td>₹<?= number_format($row['unit_price']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Add Product Button -->
    <div class="d-flex justify-content-end mt-3">
        <a href="addProduct.html" class="btn btn-success">Add Product</a>
    </div>

</body>
</html>
