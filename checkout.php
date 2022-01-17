<?php
// Include the database config file
require_once 'conn.php';

// 初始化
include_once 'Cart_function.php';
$cart = new CartFunction;
if(empty($_SESSION['user_id'])){
    echo "<script>alert('请先登录')</script>";
	echo "<script>window.location.href='index.php';</script>";
}
else{
$user_id = $_SESSION['user_id'];
ob_start();
// 购物车为空，则重定位
if ($cart->total_items() <= 0) {
    header("Location: index.php");
}

// 获取session数据
$postData = !empty($_SESSION['postData']) ? $_SESSION['postData'] : array();
unset($_SESSION['postData']);

//检查错误信息，并提示
$sessData = !empty($_SESSION['sessData']) ? $_SESSION['sessData'] : '';
if (!empty($sessData['status']['msg'])) {
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout</title>
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
    <h1>订单</h1>
    <div class="col-12">
        <div class="checkout">
            <div class="row">
                <?php if (!empty($statusMsg) && ($statusMsgType == 'success')) { ?>
                    <div class="col-md-12">
                        <div class="alert alert-success"><?php echo $statusMsg; ?></div>
                    </div>
                <?php } elseif (!empty($statusMsg) && ($statusMsgType == 'error')) { ?>
                    <div class="col-md-12">
                        <div class="alert alert-danger"><?php echo $statusMsg; ?></div>
                    </div>
                <?php } ?>

                <div class="col-md-4 order-md-2 mb-4">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">购物车</span>
                        <span class="badge badge-secondary badge-pill"><?php echo $cart->total_items(); ?></span>
                    </h4>
                    <ul class="list-group mb-3">
                        <?php
                        if ($cart->total_items() > 0) {
                            //get cart items from session
                            $cartItems = $cart->contents();
                            foreach ($cartItems as $item) {
                                ?>
                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                    <div>
                                        <h6 class="my-0"><?php echo $item["name"]; ?></h6>
                                        <small class="text-muted"><?php echo '￥' . $item["price"]; ?>
                                            (<?php echo $item["qty"]; ?>)</small>
                                    </div>
                                    <span class="text-muted"><?php echo '￥' . $item["subtotal"]; ?></span>
                                </li>
                            <?php }
                        } ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>总价 (元)</span>
                            <strong><?php echo '￥' . $cart->total(); ?></strong>
                        </li>
                    </ul>
                    <a href="index.php" class="btn btn-block btn-info">继续购物</a>
                </div>
                <div class="col-md-8 order-md-1">
                    <h4 class="mb-3">订单</h4>
                    <form method="post" action="cartAction.php">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="user_name">用户名</label>
                                <input type="text" class="form-control" name="user_name"
                                       value="<?php echo !empty($postData['user_name']) ? $postData['user_name'] : ''; ?>"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="credit_card">信用卡</label>
                                <input type="text" class="form-control" name="credit_card"
                                       value="<?php echo !empty($postData['credit_card']) ? $postData['credit_card'] : ''; ?>"
                                       required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone">电话</label>
                            <input type="phone_number" class="form-control" name="phone_number"
                                   value="<?php echo !empty($postData['phone_number']) ? $postData['phone_number'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="city">城市</label>
                            <input type="text" class="form-control" name="city"
                                   value="<?php echo !empty($postData['city']) ? $postData['city'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="address">地址</label>
                            <input type="text" class="form-control" name="address"
                                   value="<?php echo !empty($postData['address']) ? $postData['address'] : ''; ?>"
                                   required>
                        </div>
                        <input type="hidden" name="action" value="placeOrder"/>
                        <input class="btn btn-success btn-lg btn-block" type="submit" name="checkoutSubmit"
                               value="支付">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>