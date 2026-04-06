<?php
header('Content-Type: application/json');
include('../dbconnect.php');


$query = "SELECT seller_id, seller_Name FROM sellers";
$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
  $data[] = $row;
}

echo json_encode($data);
?>
