<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include('../dbconnect.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Get the data sent from JavaScript (JSON payload)
$data = json_decode(file_get_contents("php://input"), true);

$customer_id  = $data['customer_id'];
$invoice_no   = $data['invoice_no'];
$payment_term = $data['payment_term'];

// datetime fix
$invoice_date = $data['invoice_date'];
if (strlen($invoice_date) == 10) {
    $invoice_date .= " 00:00:00";
}
$invoice_date = date('Y-m-d H:i:s', strtotime($invoice_date));
$grand_total = (float)$data['grand_total'];

// 2. Insert into sales_invoices
$sql_invoice = "INSERT INTO sales_invoices (customer_id, invoice_no, payment_term, invoice_date, grand_total) 
                VALUES ('$customer_id', '$invoice_no', '$payment_term', '$invoice_date', $grand_total)";

if ($conn->query($sql_invoice) === TRUE) {
    $invoice_id = $conn->insert_id;

    // 3. Cash payment entry
    if ($payment_term === 'cash') {
        $date = date('Y-m-d');
        $sqlPayment = "INSERT INTO sale_payment (invoice_id, date, amount_paid, pay_method, reference, note)
                       VALUES ('$invoice_id', '$date', '$grand_total','cash','','cash sale')";
        $conn->query($sqlPayment);
    }

    // 4. Loop through products
    foreach ($data['products'] as $p) {
        $product_id = $p['product_id'];
        $quantity   = $p['quantity'];
        $unit_price = $p['unit_price'];
        $discount   = $p['discount'];
        $tax        = $p['tax'];
        $total      = $p['total'];

        // Check stock
        $stockCheck = $conn->query("SELECT stock_left, product_name FROM products WHERE product_id = $product_id");
        $stockRow   = $stockCheck->fetch_assoc();
        $availableStock = $stockRow['stock_left'];
        $productName    = $stockRow['product_name'];

        if ($quantity > $availableStock) {
            echo json_encode([
                "success" => false,
                "message" => "Error: Requested quantity ($quantity) for '$productName' exceeds available stock ($availableStock)."
            ]);
            exit;
        }

        // Insert into sales
        $sql_purchase = "INSERT INTO sales (invoice_id, product_id, quantity, unit_price, discount, tax, total) 
                         VALUES ('$invoice_id', '$product_id', '$quantity', '$unit_price', '$discount', '$tax', '$total')";
        if (!$conn->query($sql_purchase)) {
            echo json_encode(["success" => false, "message" => "Error saving product: " . $conn->error]);
            exit;
        }

        // Update stock
        $updateStock = "UPDATE products SET stock_left = stock_left - $quantity WHERE product_id = $product_id";
        $conn->query($updateStock);
    }

    // Success response with invoice details
    echo json_encode([
        "success" => true,
        "invoice_no" => $invoice_no,
        "invoice_id" => $invoice_id
    ]);
    exit;

} else {
    echo json_encode(["success" => false, "message" => "Error saving invoice: " . $conn->error]);
    exit;
}

$conn->close();
?>
