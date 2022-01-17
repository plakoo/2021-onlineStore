<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
$conn = mysqli_connect('localhost','root','','store');

$pid=$_GET['product_id'];

$sql_pid="select product_id from products where product_id='$pid'";
$result=mysqli_query($conn,$sql_pid);
$num=mysqli_num_rows($result);
if($conn){
if($num){
    //购物车存在该商品
   
    mysqli_query($conn,"DELETE FROM products WHERE product_id=$pid");
	mysqli_query($conn,"DELETE FROM cart WHERE product_id=$pid");
    echo "<script> alert('删除成功');window.history.go(-1);</script>";
}
else{
	echo " $pid";
}
}
else{
    die('could not connect: '.mysql_error());
}

?> 