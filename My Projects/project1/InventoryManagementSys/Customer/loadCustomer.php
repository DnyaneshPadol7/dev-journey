<?php
header('Content-Type: application/json');
include('../dbconnect.php');

$query = "SELECT customer_name, mobileNo, address, outstanding_balance FROM customers";
$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
?>
