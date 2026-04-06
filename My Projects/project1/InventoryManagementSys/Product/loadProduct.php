<?php
header('Content-Type: application/json');
include('../dbconnect.php');

$query = "SELECT product_name, category, measure_unit, unit_price FROM products";
$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
?>
