<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
$servername = "localhost";
$username = "root";
$password = "";
$dbName = "store" ;

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());}

$sql="select *from products";
$res=mysqli_query($conn,$sql);
$arr=array();

while($row=mysqli_fetch_array($res)){
$p_id[]=$row["product_id"];
$p_name[]=$row["product_name"];
$price[]=$row["price"];
$pic[]    =$row["pic"];
$inven[]=$row["inventory"];
$arr[]=$row;
}

if(empty($_SESSION['user'])){
	$user = 'x';
}
else{
	$user=$_SESSION['user'];
}

?>

<?php
if(empty($user) || $user != 'admin'){
	echo "<script> alert('你没有权限登录该网页');</script>"; 
	echo "<script>window.location.href='index.php';</script>";
}
?>

<html>
<head>
	<TITLE>main page</TITLE>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="admin_product.css" rel="stylesheet" media="all">
</head>
<body>
	<div class ="topbar">
		<div class="wrapper">
			<a href="index.php" class="logo"></a>
			<div class="userul">
				<ul >
				<li class="current">
					<a href="index.php" >主页</a>
					<span class="lines"></span></li>
    				<li class="userInfo">欢迎, <?php echo $_SESSION['user'];?></li>
    				<li	class="userInfo"><a href="logout.php">Login Out</a></li>
				</ul>
			</div>			
		</div>
	</div>
	
	<aside class="lt_aside_nav content mCustomScrollbar">
	 <ul>
	  <li>
	   <dl>
	    <dt>菜单</dt>
	    <dd><a href="admin_product.php">产品列表</a></dd>
	    <dd><a href="admin_user.php">用户列表</a></dd>
	    <dd><a href="admin_order.php">订单列表</a></dd>
		<dd><a href="admin_online.php">在线人数</a></dd>
	   </dl>
	  </li>
	 </ul>
	</aside>
	
	<div class="home">
		<div id="main">
			<ul>
			<?php
				foreach($arr  as $key => $value){ ?>
				
				<li>
					<div class="item">
					<div class="item-pic">
						<a>
						<img alt="item 1" src="<?php echo $pic[$key]?>" title="商品1" width="240" height="240">
						</a>
					</div>
						
					<div>
						<form action = "change.php" method = "get">																
								<div>
									<label><strong>商品id: </strong></label> 
									<input id="i1" type="text" name="input1" value="<?php echo $p_id[$key];?>" readonly ><span id="p111"></span>							
								</div>
								<div>
									<label><strong>商品名: </strong></label>
									<input id="i1" type="text" name="input2" value="<?php echo $p_name[$key];?>" readonly ><span id="p111"></span>
								</div>
								<div>
									<label><strong>价格: </strong></label>
									<input id="i2" type="text" name="input3" value="<?php echo $price[$key];?>" class="form-control" ><span id="p111"></span>
								</div>
								<div>
									<label><strong>库存: </strong></label>
									<input id="i2" type="text" name="input4" value="<?php echo $inven[$key];?>" class="form-control"><span id="p111"></span>
								</div>
								<div id="i2">
									<button class="btn btn-block btn-info">修改</button><span id="p111"></span>
								</div>
						</form>				
					</div>
					
					<div id="i2">
						<form method="post" href="">
						<a href="delete.php?product_id=<?php echo $p_id[$key]?>" class="btn btn-block btn-info">删除</a>
						</form>							
					</div>	
					
				<?php }?>
			</ul>
			
				</div>
		</div>
	<hr id="new1">
	<div id="upload">
		
	
		<form action = "addproduct.php" method = "get">																
			<div>
				<label><strong>商品名: </strong></label>
				<input id="i2" type="text" name="in1" placeholder="product name" class="form-control"><span id="p111"></span>
			</div>
			<div>
				<label><strong>价格: </strong></label>
				<input id="i2" type="text" name="in2" placeholder="price" class="form-control" ><span id="p111"></span>
			</div>
			<div>
				<label><strong>图片地址: </strong></label> 
				<input id="i2" type="text" name="in3" placeholder="img address" class="form-control" ><span id="p111"></span>							
			</div>
		    <div>
				<label><strong>库存: </strong></label>
				<input id="i2" type="text" name="in4" placeholder="inventory" class="form-control"><span id="p111"></span>
			</div>
			<div id="i2">
				<button class="btn btn-block btn-info">添加商品</button><span id="p111"></span>
			</div>
		</form>	
	</div>			
</body> 
</html>


	

