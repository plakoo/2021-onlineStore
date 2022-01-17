<?php
	session_start();
	if (!isset($_REQUEST['id'])) {
		header("Location: index.php");
	}

	require_once 'conn.php';
	include_once 'Cart_function.php';
	$user_id = $_SESSION['user_id'];
	$cart = new CartFunction;

	// 获取订单和商品订单的联合表格
	$result = $db->query("SELECT user_id,orders.user_name,price,phone_number,detail_address,balance,day
							FROM orders
							LEFT JOIN users
							ON users.id=user_id
							WHERE  orders.order_id= " . $_REQUEST['id']);
	$conn = mysqli_connect('localhost','root','','store');
	if ($result->num_rows > 0) {
		$orderInfo = $result->fetch_assoc();
	} 
	else {
		header("Location: index.php");
	}
	
	$sql_balance = "SELECT balance FROM users where id=$user_id";
	$result_ba=mysqli_query($conn,$sql_balance);
	$bb=mysqli_fetch_array($result_ba);
	$balance = $bb[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Status</title>
    <meta charset="utf-8">

    <!-- CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/indexStyle.css" rel="stylesheet" media="all">
</head>
<body>

	<div class ="topbar">
		<div class="wrapper">
			<a href="index.php" class="logo"></a>
			<div class="nav">

				<form action="search.php" method="post" style="margin-top:25px">
					<div style="float:left">
					<input type="text" name="searchbar"  placeholder="搜索">
					</div>
					<div style="float:left">
					<input type="image" src="img/search.png" >
					</div>
				</form>

				<ul class="parent">
					<li class="current">
					<a href="index.php" >主页</a>
					<span class="lines"></span></li>

					<li class="current" style="float: left">
                        <a href="viewCart.php" title="View Cart" ><img src="img/cart.jpg" width="30px">
						<?php echo ($cart->total_items() > 0) ? '('.$cart->total_items() .')'. ' 购物车' : '购物车'; ?></a>
					<span class="lines"></span></li>
				</ul>

                <div class="userul" style="float: right;padding-top: 20px;">
                    <ul style="width: auto;text-align: right">
                        <?php if(!empty($_SESSION['user'])){?>
                            <li class="userInfo">欢迎, <?php echo $_SESSION['user'];?></li>
                            <li	class="userInfo"><a href="logout.php">Login Out</a></li>

                        <?php }else{?>
                            <li class="userInfo"><a href="login.php">Login</a></li>
                        <?php }?>
                    </ul>
                </div>
			</div>
		</div>
	</div>

<div class="container">
    <h1>订单状态</h1>
    <div class="col-12">
        <?php if (!empty($orderInfo)) { ?>
            <div class="col-md-12">
                <div class="alert alert-success">购买成功</div>

            <!-- Order status & shipping info -->
            <div class="s">
                <div class="hdr"><h1>订单信息</h1></div>
                <p><b>用户ID:</b> <?php echo $user_id; ?></p>
				<p><b>收件人:</b> <?php echo $orderInfo['user_name']; ?></p>
                <p><b>总价:</b> <?php echo '￥' . $orderInfo['price']; ?></p>
				<p><b>收件地址:</b> <?php echo $orderInfo['detail_address']; ?></p>
                <p><b>电话:</b> <?php echo $orderInfo['phone_number']; ?></p>
				<p><b>余额:</b> <?php echo $orderInfo['balance']; ?></p>
				<p><b>时间:</b> <?php echo $orderInfo['day']; ?></p>
            </div>

            <!-- Order items -->
            <div class="row col-lg-12">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>商品</th>
                        <th>价格</th>
                        <th>数量</th>
                        <th>金额</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    // 获取订单条目
                    $result = $db->query("SELECT product_name, price, quantity 
											FROM product_orders 
											WHERE order_id = " . $_REQUEST['id']);
                    if ($result->num_rows > 0) {
                        while ($item = $result->fetch_assoc()) {
                            $price = $item["price"];
                            $quantity = $item["quantity"];
                            $sub_total = ($price * $quantity);
                            ?>
                            <tr>
                                <td><?php echo $item["product_name"]; ?></td>
                                <td><?php echo '￥' . $price . ''; ?></td>
                                <td><?php echo $quantity; ?></td>
                                <td><?php echo '￥' . $sub_total . ''; ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <div class="col-md-12">
                <div class="alert alert-danger">购买失败</div>
            </div>
        <?php } ?>
    </div>
</div>
</body>
</html>