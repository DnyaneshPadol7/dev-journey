<?php
include('../dbconnect.php');
if (isset($_POST['submit'])) {
    $productName=$_POST['product_name'];
    $category=$_POST['category'];
    $stock_left=$_POST['stock_left'];
    $MOU=$_POST['measure_unit'];
    $price=$_POST['unit_price'];
    $desc=$_POST['description'];
$query="INSERT INTO products(product_name,category,stock_left,measure_unit,unit_price,description)VALUES('$productName','$category','$stock_left','$MOU','$price','$desc')";
$result=mysqli_query($conn,$query);
if($result){
echo "data inserted succesfully";

}else{
    die('data not inserted'.mysqli_error($conn));
}
}
?>