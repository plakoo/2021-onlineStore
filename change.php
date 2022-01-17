<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
$conn = mysqli_connect('localhost','root','','store');

$pid=$_GET['input1'];
$nam=$_GET['input2'];
$pri=$_GET['input3'];
$inv=$_GET['input4'];

$sql_pid="select product_id from products where product_id='$pid'";
$result=mysqli_query($conn,$sql_pid);
$num=mysqli_num_rows($result);
if($conn){
if($num){
    //购物车存在该商品
   
   mysqli_query($conn,"UPDATE products SET price=$pri, inventory= $inv WHERE product_id=$pid");
    echo "<script> alert('修改成功');window.history.go(-1);</script>";
}

}
else{
    die('could not connect: '.mysql_error());
}

?> 