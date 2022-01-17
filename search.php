<?php
session_start();

header("Content-Type: text/html; charset=utf-8");
$servername = "localhost";
$username = "root";
$password = "";
$dbName = "store" ;
include_once  'Cart_function.php';
$cart = new CartFunction;
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

  $key=$_POST["searchbar"];
  $sql="select * from products where product_name like '%$key%'";
  $res=mysqli_query($conn,$sql);

  $p_id=array();
  $p_name=array();
  $price=array();
  $pic=array();
  $arr=array();

//查询结果不为0  
if($res){
    while($row=$res->fetch_array()){
        $p_id[]   =$row["product_id"];
        $p_name[] =$row["product_name"];
        $price[]  =$row["price"];
        $pic[]    =$row["pic"];
        $arr[]    =$row;
        }
    }
if(!empty($_SESSION['user'])){
    $user=$_SESSION['user'];
    $user_id=$_SESSION['user_id'];
    }
    ?>
	
<html>
    <head>
        <TITLE>main page</TITLE>
        <link href="css/indexStyle.css" rel="stylesheet" media="all">
        <link href="css/bootstrap.min.css" rel="stylesheet">
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
        
        <div class="home">
            <div class="main">
                <ul>

	
		<div class="row col-lg-12">
		    <?php
			
		    if (!empty($arr)) {
		        foreach ($arr as $key => $value) {
		            ?>
		            <div class="card col-lg-4">
		                <div class="card-body">
		                    <img src="<?php echo $pic[$key]?>" width="240" height="240">
		                    <h5 class="card-title"><?php echo $p_name[$key]; ?></h5>
		                    <h6 class="card-subtitle mb-2 text-muted">
		                        价格: <?php echo '￥'; echo $price[$key]; ?></h6>
		
		                    <a href="cartAction.php?action=addToCart&id=<?php echo $p_id[$key]; ?>"
		                       class="btn btn-primary">加入购物车</a>
		                </div>
		            </div>
		        <?php }
		    } else { ?>
		        <p>未找到商品</p>
		    <?php } ?>
		</div>





		</ul>
		</div>
	</div>

	</body>
</html>