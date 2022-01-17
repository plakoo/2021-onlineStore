<?php
// Initialize shopping cart class
include_once 'Cart_function.php';
$cart = new CartFunction;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Cart</title>
    <meta charset="utf-8">

    <!-- CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/indexStyle.css" rel="stylesheet" media="all">

    <!-- jQuery library -->
    <script src="js/jquery.min.js"></script>

    <script>
        function updateCartItem(obj, id, pid) {
            $.get("cartAction.php", {action: "updateCartItem", id: id, pid: pid, qty: obj.value}, function (data) {
                if (data == 'ok') {
                    location.reload();
                } 
				else {
    //              alert('请先登录,再更新数据库内容');
					window.history.go(0) 
				}
            });
        }
    </script>
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
    <h1>购物车</h1>
    <div class="row">
        <div class="cart">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th width="40%">商品</th>
                            <th width="23%">价格</th>
                            <th width="23%">数量</th>
                            <th class="text-right" width="20%">总价</th>
                            <th width="10%"></th>
                        </tr>
                        </thead>
                        <tbody>
                        
						<?php
                        if($cart->total_items() > 0){
							//cart_fuction
                            $cartItems = $cart->contents();
                            foreach($cartItems as $item){
                        ?>
                                <tr>
                                    <td><?php echo $item["name"]; ?></td>
                                    <td><?php echo '￥'.$item["price"].''; ?></td>
                                    <td><input class="form-control" type="number" value="<?php echo $item["qty"]; ?>" onchange="updateCartItem(this, '<?php echo $item["rowid"]; ?>','<?php echo $item["id"]; ?>')"/></td>
                                    <td class="text-right"><?php echo '￥'.$item["subtotal"].''; ?></td>
                                    <!-- 删除键(关键字触发cartaction再触发cart_fuction) -->
									<td class="text-right"><button 	class="btn btn-sm btn-danger" 
																	onclick="return confirm('你确定吗?')?window.location.href='cartAction.php?action=removeCartItem&id=<?php echo $item["rowid"]?>&pid=<?php echo $item["id"] ?>;':false;">
																	<i class="itrash"></i> </button></td>
                                </tr>
                            <?php } }else{ ?>
								<tr><td colspan="5"><p>购物车为空</p></td>
                            <?php } ?>
                            <?php if($cart->total_items() > 0){ ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td><strong>总计</strong></td>
                                <td class="text-right"><strong><?php echo '￥'.$cart->total().''; ?></strong></td>
                                <td></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col mb-2">
                <div class="row">
                    <div class="col-sm-12  col-md-6">
                        <a href="index.php" class="btn btn-lg btn-block btn-primary">继续购物</a>
                    </div>
                    <div class="col-sm-12 col-md-6 text-right">
                        <?php if ($cart->total_items() > 0 ) { ?>
                            <a href="checkout.php" class="btn btn-lg btn-block btn-primary">结账</a>
                        <?php } ?>
						
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>