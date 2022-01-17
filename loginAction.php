<?php
	session_start();
	
	include_once  'Cart_function.php';
	$cart = new CartFunction;
	
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbName = "store" ;
	
	$conn = mysqli_connect($servername, $username, $password, $dbName);
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());}
	
	mysqli_select_db($conn,'store');
	mysqli_set_charset($conn,'utf8');
	
	$username=$_POST['username'];
	$password=$_POST['password'];
	 
	$sql="select * from users where user_name='$username' AND password='$password'";
	
	$result=mysqli_query($conn,$sql);


	if($result->num_rows!=0){

	$sql_user="select id,authority from users where user_name='$username'";
	$id_result=mysqli_query( $conn,$sql_user);
    $userInfo= mysqli_fetch_array($id_result);
	$user_id=$userInfo[0];
	$user_authority=$userInfo[1];
	$_SESSION['user']=$username;
	$_SESSION['user_id']=$user_id;
	
	if($cart->total_items() > 0){
		$cartItems = $cart->contents();
		foreach ($cartItems as $item) {
			$p_id=$item['id'];
			$p_name=$item['name'];
			$price=$item['price'];
			$quantity=$item['qty'];
			
			$sql_pid="select product_id from cart where product_id='$p_id' AND user_id='$user_id'";
			$result=mysqli_query($conn,$sql_pid);
			//检查返回了多少行
			$num=mysqli_num_rows($result);

			if($num){
				//购物车存在该商品					
				mysqli_query($conn,"UPDATE cart SET quantity=$quantity WHERE product_id=$p_id AND user_id='$user_id' ");
			}
			else{
				// 购物车不存在该商品
				$sql_insert="insert into cart(user_id,product_id,price,quantity) values ('$user_id', '$p_id', '$price', '$quantity')";
				mysqli_query($conn,$sql_insert);
			}			
			
		}	
	}
	$cart->destroy();
	
	
	mysqli_close($conn);	
		if($user_authority==1){
			header("location:admin_product.php");			
		}
		else 
			header("Location: index.php");//登录跳转
			
	}else{
		echo "<script> alert('账号或密码不正确');window.history.go(-1);</script>";
	}
	
	
	
?>









