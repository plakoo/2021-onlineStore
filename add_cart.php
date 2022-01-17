<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
$conn = mysqli_connect('localhost','root','','store');
	if($conn){
		$uid=$_SESSION['user_id'];
		$pid=$_GET['product_id'];
		// $pname=$_GET['product_name'];
		$price=$_GET['price'];
		//$cart_id;

		$sql_pid="select product_id from cart where product_id='$pid' AND user_id='$uid'";
		$result=mysqli_query($conn,$sql_pid);
		$num=mysqli_num_rows($result);


		if($num){
		//购物车存在该商品
		$sql_quantity="select quantity from cart where product_id='$pid' AND user_id='$uid'";
		$qu_result=mysqli_query( $conn,$sql_quantity);
		$quantity= mysqli_fetch_array($qu_result);
		$value=$quantity[0];
		$value+=1;
		
		mysqli_query($conn,"UPDATE cart SET quantity=$value WHERE product_id='$pid' AND user_id='$uid'" );
		echo "<script> alert('Added to the cart');window.history.go(-1);</script>";
		}
		else{
			// 购物车不存在该商品
			 $sql_insert="insert into cart(user_id,product_id,price,quantity) values ('$uid', '$pid', '$price', 1)";
			mysqli_query($conn,$sql_insert);
			echo "<script> alert('Added to the cart');window.history.go(-1);</script>";
		}
	}
else{
    die('could not connect: '.mysql_error());
}

?>