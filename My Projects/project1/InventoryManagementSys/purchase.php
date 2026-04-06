<?php
include('dbconnect.php');
// Tell browser we are sending JSON back
header("Content-Type: application/json");
ini_set('display_errors', 1);
error_reporting(E_ALL);
// 1. Get the data sent from JavaScript (JSON payload)
$data = json_decode(file_get_contents("php://input"), true);

// 3. Save the invoice into purchase_invoices table
$seller_id = $data['seller_id'];
$invoice_no = $data['invoice_no'];
$payment_term = $data['payment_term'];
$invoice_date = $data['invoice_date'];
$grand_total = (float)$data['grand_total'];

$sql_invoice = "INSERT INTO purchase_invoices (seller_id, invoice_no, payment_term,invoice_date, grand_total) 
                VALUES ('$seller_id', '$invoice_no', '$payment_term', '$invoice_date', '$grand_total')";

if ($conn->query($sql_invoice) === TRUE) {
    // Get the id of the invoice we just created
    $invoice_id = $conn->insert_id;
      if ($payment_term === 'cash') {
    $date = date('Y-m-d');
    $sqlPayment = "INSERT INTO purchase_payment (invoice_id, date, amount_paid, pay_method, reference, note)
                   VALUES ('$invoice_id', '$date', '$grand_total','cash','','cash purchase')";
    $conn->query($sqlPayment);
}
    // 4. Loop through each product and save it into purchases table
//     foreach ($data['products'] as $p) {
//         $product_id = $p['product_id'];
//         $quantity = $p['quantity'];
//         $unit_price = $p['unit_price'];
//         $discount = $p['discount'];
//         $tax = $p['tax'];
//         $total = $p['total'];

//         $sql_purchase = "INSERT INTO purchases (invoice_id, product_id, quantity,unit_price, discount, tax, total) 
//                          VALUES ('$invoice_id', '$product_id', '$quantity', '$unit_price', '$discount', '$tax', '$total')";

//         $conn->query($sql_purchase);
//     }

//     echo json_encode(["message" => "Purchase saved successfully"]);
// } else {
//     echo json_encode(["message" => "Error saving invoice: " . $conn->error]);
// }
 
 foreach ($data['products'] as $p) {
        $product_id = $p['product_id'];
        $quantity   = $p['quantity'];
        $unit_price = $p['unit_price'];
        $discount   = $p['discount'];
        $tax        = $p['tax'];
        $total      = $p['total'];

        // Insert purchase entry
        $sql_purchase = "INSERT INTO purchases (invoice_id, product_id, quantity, unit_price, discount, tax, total) 
                         VALUES ('$invoice_id', '$product_id', '$quantity', '$unit_price', '$discount', '$tax', '$total')";

        if (!$conn->query($sql_purchase)) {
            echo json_encode(["message" => "Error saving product: " . $conn->error]);
            exit;
        }

        //  Update stock_left in products table
        $updateStock = "UPDATE products 
                        SET stock_left = stock_left + $quantity 
                        WHERE product_id = '$product_id'";

        if (!$conn->query($updateStock)) {
            echo json_encode(["message" => "Error updating stock: " . $conn->error]);
            exit;
        }
    }
}
// 5. Close the database connection
$conn->close();
?>
