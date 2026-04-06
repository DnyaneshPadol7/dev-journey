<?php
include('../dbconnect.php');

header("Content-Type: application/json");

// Get payment data from frontend
$data = json_decode(file_get_contents("php://input"), true);

$invoice_id = $data['invoice_id'];
$payment_date =$data["date"];
$amount_paid = $data['paid_amount'];
$pay_method = $data['payment_method'];
$reference = $data['reference_no'];
$note = $data['note'];

// Insert payment
$sql = "INSERT INTO sale_payment (invoice_id,date, amount_paid, pay_method, reference,note)
        VALUES ('$invoice_id', '$payment_date', '$amount_paid', '$pay_method', '$reference','$note')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success", "message" => "Payment saved"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}

$conn->close();
?>
 