<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
$servername = "localhost";
$username = "root";
$password = "";
$dbName = "store" ;
// 建立连接
$conn = mysqli_connect($servername, $username, $password, $dbName);
// 检查连接
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
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
	<link href="admin_product.css" rel="stylesheet" media="all">
    <link href="css/bootstrap.min.css" rel="stylesheet" media="all">
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
	
	<div class = "table table-striped table-hover" style="float: left;" margin-top: -10px;>
	    <?php
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"admin_product.css\" />";

	    
	    $sql="select *from orders";
	    $res=mysqli_query($conn,$sql);
		echo "<table>
		<tr>
		<th>order_id</th>
		<th>user_id</th>
		<th>用户</th>
		<th>信用卡</th>
		<th>价格</th>
		<th>电话</th>
		<th>城市</th>
		<th>具体地址</th>
		<th>日</th>
		</tr>";

		while($row = $res->fetch_array())//转成数组，且返回第一条数据,当不是一个对象时候退出
		{
		echo "<tr>";
		echo "<td>" . $row['order_id'] . "</td>";
		echo "<td>" . $row['user_id'] . "</td>";
		echo "<td>" . $row['user_name'] . "</td>";
		echo "<td>" . $row['credit_card'] . "</td>";
		echo "<td>" . $row['price'] . "</td>";
		echo "<td>" . $row['phone_number'] . "</td>";
		echo "<td>" . $row['city'] . "</td>";
		echo "<td>" . $row['detail_address'] . "</td>";
		echo "<td>" . $row['day'] . "</td>";
		echo "</tr>";
		}
		echo "</table>";

		mysqli_close($conn);
		?>
	</div>
</body>
</html>
	