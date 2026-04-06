<?php
include('../dbconnect.php');

header("Content-Type: application/json");
// Receive JSON data from frontend
$data = json_decode(file_get_contents("php://input"), true);

$invoice_id   = $data['invoice_id'];
$invoice_date = $data['invoice_date'];
$payment_term = $data['payment_term'];
$grand_total  = $data['grand_total'];
$products     = $data['products'];

// 1. Update purchase_invoices table
$sql_update = "UPDATE purchase_invoices 
               SET invoice_date='$invoice_date', payment_term='$payment_term', grand_total='$grand_total'
               WHERE invoice_id=$invoice_id";
mysqli_query($conn, $sql_update);
// 2. Sync payments table
$check_payment = "SELECT COUNT(*) as cnt FROM purchase_payment WHERE invoice_id=$invoice_id";
$res_pay = mysqli_query($conn, $check_payment);
$row_pay = mysqli_fetch_assoc($res_pay);
if ($row_pay['cnt'] > 0) {
    // Update existing payment
    $sql_payment = "UPDATE purchase_payment 
                    SET date='$invoice_date', amount_paid='$grand_total', pay_method='$payment_term'
                    WHERE invoice_id=$invoice_id";
} else {
    // Insert new payment
    $sql_payment = "INSERT INTO purchase_payment (invoice_id , date ,  customer_id, amount_paid, payment_term) 
                     VALUES ('$invoice_id' , '$invoice_date' ,'$customer_id', '$grand_total', '$payment_term')";
}

if (!$conn->query($sql_payment)) {
    echo json_encode(["message" => "Error updating payment: " . $conn->error]);
    exit;
}
// 3. Get existing products for this invoice
$existing_products = [];
$res = mysqli_query($conn, "SELECT product_id FROM purchases WHERE invoice_id=$invoice_id");
while ($row = mysqli_fetch_assoc($res)) {
    $existing_products[] = $row['product_id'];
}

// 4. Prepare array of submitted product_ids from the UI
$new_products = array_map('intval', array_column($products, 'product_id'));

// 5. Handle removed products (those that are in DB but not in the UI)
foreach ($existing_products as $pid) {
    if (!in_array($pid, $new_products)) {
        // Delete the product that has been removed from the UI
        mysqli_query($conn, "DELETE FROM purchases WHERE invoice_id=$invoice_id AND product_id=$pid");
    }
}
// 6. Insert or update products
foreach ($data['products'] as $p) {
    $pid  = $p['product_id'];
    $qty  = $p['quantity'];
    $rate = $p['unit_price'];
    $disc = $p['discount'];
    $tax  = $p['tax'];
    $total = $p['total'];
    
    // 1. Check if product already exists
    $check = "SELECT COUNT(*) as cnt FROM purchases WHERE invoice_id=$invoice_id AND product_id=$pid";
    $res   = mysqli_query($conn, $check);
    $row   = mysqli_fetch_assoc($res);

    if ($row['cnt'] > 0) {
        // Update existing product
        $sql_item = "UPDATE purchases 
                     SET quantity='$qty', unit_price='$rate', discount='$disc', tax='$tax', total='$total'
                     WHERE invoice_id=$invoice_id AND product_id=$pid";
    } else {
        // Insert new product
        $sql_item = "INSERT INTO purchases (invoice_id, product_id, quantity, unit_price, discount, tax, total)
                     VALUES ('$invoice_id', '$pid', '$qty', '$rate', '$disc', '$tax', '$total')";
    }

    if (!$conn->query( $sql_item)) {
            echo json_encode(["message" => "Error saving product: " . $conn->error]);
            exit;
        }
}

echo json_encode(["status" => "success", "message" => "Purchase updated successfully!"]);
?>
