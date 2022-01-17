<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
$conn = mysqli_connect('localhost','root','','store');

$product_name=$_GET['in1'];
$price=$_GET['in2'];
$pic=$_GET['in3'];
$inventory=$_GET['in4'];


if($conn){

	$sql_insert="insert into products(product_name,price,inventory,pic) values ('$product_name',$price, $inventory, '$pic')";
    mysqli_query($conn,$sql_insert);
    echo "<script> alert('Added to the database');window.history.go(-1);</script>";
}
else{
    die('could not connect: '.mysql_error());
}

?>

