
<?php
session_start();
	header("Content-Type: text/html;charset=utf-8");
	//建立连接
	$conn = mysqli_connect('localhost','root','','store');
	if ($conn) {
		//echo "Connected successfully";
			
			$user = $_POST["username"];
			$pass = $_POST["password"];
			$re_pass = $_POST["repassword"];
			$email = $_POST["email"];
			$authority = 0;
			if($user == ""||$pass == ""||$re_pass==""){
				echo"<script> alert('用户名和密码不能为空');window.history.go(-1);</script>";
				exit;
			}
			if($email == ""){
				echo"<script> alert('邮箱不能为空');window.history.go(-1);</script>";
				exit;
			}
			if($pass == $re_pass){
				mysqli_set_charset($conn,'utf8');	
				
				//sql语句
				$sql = "select user_name from users where user_name = '$user'";
				//sql语句执行
				$result = mysqli_query($conn,$sql);
				//判断用户名是否已存在，读行数，行数>0，true，已存在
				$num = mysqli_num_rows($result); 
				
				if($num){
					//用户名已存在
					echo"<script> alert('用户名已存在');window.history.go(-1);</script>";
					exit;
				}else{
					//用户名不存在
					$sql_insert = "insert into users(user_name,password,email,authority,balance) values('$user','$pass','$email','$authority',90000)";
					//插入数据
					$ret = mysqli_query($conn,$sql_insert);
					//$row = mysqli_fetch_array($ret); 
					//跳转注册成功页面
					header("Location: login.php");
				}
			}else{
				//两次密码输入不一致
				echo"<script> alert('两次密码输入不同');window.history.go(-1);</script>";
				exit;
			}
		
		//关闭数据库
		mysqli_close($conn);
	}else{
		//连接错误处理
		die('Could not connect:'.mysql_error());
	}
?>