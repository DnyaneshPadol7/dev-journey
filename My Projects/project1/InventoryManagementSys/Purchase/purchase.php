<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include('../dbconnect.php');

// ✅ Browser ko batao ki hum JSON bhej rahe hain
header("Content-Type: application/json");
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1️⃣ Get the data sent from JavaScript (JSON payload)
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Invalid JSON data"]);
    exit;
}

// 2️⃣ Extract fields
$seller_id    = $data['seller_id'];
$invoice_no   = $data['invoice_no'];
$payment_term = $data['payment_term'];

// datetime fix: agar sirf date aa rahi hai to uske sath time add kar do
$invoice_date = $data['invoice_date'];
if (strlen($invoice_date) == 10) { 
    $invoice_date .= " 00:00:00"; 
}

$grand_total = (float)$data['grand_total'];

// 3️⃣ Insert invoice
$sql_invoice = "INSERT INTO purchase_invoices 
                (seller_id, invoice_no, payment_term, invoice_date, grand_total) 
                VALUES ('$seller_id', '$invoice_no', '$payment_term', '$invoice_date', '$grand_total')";

if ($conn->query($sql_invoice) === TRUE) {

    // ✅ get new invoice_id
    $invoice_id = $conn->insert_id;

    // 4️⃣ Insert products & update stock
    foreach ($data['products'] as $p) {
        $product_id = $p['product_id'];
        $quantity   = $p['quantity'];
        $unit_price = $p['unit_price'];
        $discount   = $p['discount'];
        $tax        = $p['tax'];
        $total      = $p['total'];

        $sql_purchase = "INSERT INTO purchases 
                         (invoice_id, product_id, quantity, unit_price, discount, tax, total) 
                         VALUES ('$invoice_id', '$product_id', '$quantity', '$unit_price', '$discount', '$tax', '$total')";

        if (!$conn->query($sql_purchase)) {
            echo json_encode(["success" => false, "message" => "Error saving product: " . $conn->error]);
            exit;
        }

        // ✅ Update stock
        $updateStock = "UPDATE products 
                        SET stock_left = stock_left + $quantity 
                        WHERE product_id = '$product_id'";

        if (!$conn->query($updateStock)) {
            echo json_encode(["success" => false, "message" => "Error updating stock: " . $conn->error]);
            exit;
        }
    }

    // ✅ clean success JSON (for redirect)
    echo json_encode([
        "success" => true,
        "message" => "Purchase saved & stock updated successfully",
        "invoice_no" => $invoice_no,
        "invoice_id" => $invoice_id
    ]);
} 
else {
    echo json_encode(["success" => false, "message" => "Error saving invoice: " . $conn->error]);
}

// 5️⃣ Close connection
$conn->close();
?>
