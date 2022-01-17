<?php
session_start();

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


$filename = 'online.txt';  //数据文件
$cookiename = 'VGOTCN_OnLineCount';  //cookie名称
$onlinetime = 120;  //在线有效时间，单位：秒 
 
@$online = file($filename); //数据文件，如果没有新建
$nowtime = time(); //返回秒数
$nowonline = array();
 
/*
@ 得到仍然有效的数据
*/
	if(!empty($online)){
		foreach($online as $line) {
		$row = explode('|',$line);
		//$row[0]为uid， row[1]为初始时间
		$sesstime = trim($row[1]);
			//如果仍在有效时间内，则数据继续保存，否则被放弃不再统计
			if(($nowtime - $sesstime) <= $onlinetime) {  
			$nowonline[$row[0]] = $sesstime;  //获取在线列表到数组，会话ID为键名，最后通信时间为键值
			}
		}
	}
/*
@ 创建访问者通信状态
使用cookie通信
COOKIE 将在关闭浏览器时失效，但如果不关闭浏览器，此 COOKIE 将一直有效，直到程序设置的在线时间超时
*/
if(isset($_COOKIE[$cookiename])) {  //如果有COOKIE即并非初次访问则不添加人数并更新通信时间
	$uid = $_COOKIE[$cookiename];
	} 
	else {  //如果没有COOKIE即是初次访问
	$vid = 0;  //初始化访问者ID
		do {  //给用户一个新ID
		$vid++;
		$uid = 'U'.$vid;
		} 
		//检查$uid是否存在数组中
		while (array_key_exists($uid,$nowonline)); 
		//设置cookie value
		setcookie($cookiename,$uid);
	}
	
$nowonline[$uid] = $nowtime;  //更新现在的时间状态
 
/*
@ 统计现在在线人数
*/
$total_online = count($nowonline);
 
/*
@ 写入数据
*/
if($fp = @fopen($filename,'w')) {
	if(flock($fp,LOCK_EX)) {
		rewind($fp);
		foreach($nowonline as $fuid => $ftime) {
		$fline = $fuid.'|'.$ftime."\n";
		@fputs($fp,$fline); 
		}
	flock($fp,LOCK_UN);
	fclose($fp);
	}
}
//echo 'document.write("'.$_COOKIE[$cookiename].'");';
?>
<?php
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
	
	<div class = "table table-striped table table-hover" style="float: left;">
	    <?php
	    header('Content-type: admin_product/css');
	    header("Content-Type: text/html; charset=utf-8");
	
	    echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"admin_product.css\" />";
		echo "<table class=\"\" style=\"border-color: grey\">
		<tr>
		<th>在线人数</th>
		</tr>";
	
		echo "<tr>";
		echo "<td>" . $total_online;
		echo "</tr>";
		echo "</table>";
	
		mysqli_close($conn);
		?>
	</div>
	
	
	
</body>
</html>