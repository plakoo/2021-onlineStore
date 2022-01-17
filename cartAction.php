<?php
// 初始化购物车
require_once 'Cart_function.php';
$cart = new CartFunction;
$user_id = $_SESSION['user_id'];
//连接数据库
require_once 'conn.php';

// 重定位到主页
$redirectLoc = 'index.php';

// 加购物车
if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])){
    if($_REQUEST['action'] == 'addToCart' && !empty($_REQUEST['id'])){
        $productID = $_REQUEST['id'];

        //获取商品信息
        $query = $db->query("SELECT * FROM products WHERE product_id = $productID");
        $row = $query->fetch_assoc();
        $itemData = array(
            'id' => $row['product_id'],
            'name' => $row['product_name'],
            'price' => $row['price'],
            'qty' => 1
        );
        // 插入购物车页面的session
        $insertItem = $cart->insert($itemData);
        //插入成功，转到购物车页面
        $redirectLoc = $insertItem?'viewCart.php':'index.php';
 
		$conne = mysqli_connect('localhost','root','','store');
		//插入到数据库（若id已登录）
		if($conne){
				$uid=$_SESSION['user_id'];
				$pid=$itemData['id'];
				$price=$itemData['price'];
				
				$sql_pid="select product_id from cart where product_id='$pid' AND user_id='$uid'";
				$result=mysqli_query($conne,$sql_pid);
				//检查返回了多少行
				$num=mysqli_num_rows($result);
				//检查是否登录
				if($uid !=0){
					if($num){
					//购物车存在该商品
					$sql_quantity="select quantity from cart where product_id='$pid' AND user_id= '$uid'";
					$qu_result=mysqli_query($conne,$sql_quantity);
					$quantity= mysqli_fetch_array($qu_result);
					$value=$quantity[0];
					$value+=1;
					
					mysqli_query($conne,"UPDATE cart SET quantity=$value WHERE product_id=$pid AND user_id='$uid' ");
					echo "<script> alert('Added to the cart');window.history.go(-1);</script>";
					}
					else{
						// 购物车不存在该商品
						 $sql_insert="insert into cart(user_id,product_id,price,quantity) values ('$uid', '$pid', '$price', 1)";
						mysqli_query($conne,$sql_insert);
						echo "<script> alert('Added to the cart');window.history.go(-1);</script>";
					}
				}
				
			}
		else{
		    die('could not connect: '.mysql_error());
		}
		
	
	
	}elseif($_REQUEST['action'] == 'updateCartItem' && !empty($_REQUEST['id'])){
        // Update item data in cart
        $itemData = array(
            'rowid' => $_REQUEST['id'],
            'qty' => $_REQUEST['qty']
        );
		$uuid = $_SESSION['user_id'];
		$ppid = $_REQUEST['pid'];
		$qqty = $_REQUEST['qty'];
        $updateItem = $cart->update($itemData);
		// 如果更改成功,更新数据库
		if($updateItem){
			$conn = mysqli_connect('localhost','root','','store');
				if($conn){
					mysqli_query($conn,"UPDATE cart SET quantity=$qqty WHERE product_id='$ppid' AND user_id='$uuid'" );
					}
		}

        // Return status
        echo $updateItem?'ok':'err';die;
    }elseif($_REQUEST['action'] == 'removeCartItem' && !empty($_REQUEST['id'])){
        // 删除购物条目
        $deleteItem = $cart->remove($_REQUEST['id'],$_REQUEST['pid']);

        //回到购物车页面
        $redirectLoc = 'viewCart.php';
    }elseif($_REQUEST['action'] == 'placeOrder' && $cart->total_items() > 0){
        $redirectLoc = 'checkout.php';

        // Store post data
        $_SESSION['postData'] = $_POST;

        $user_name = strip_tags($_POST['user_name']);
        $credit_card = strip_tags($_POST['credit_card']);
        $phone_number = strip_tags($_POST['phone_number']);
        $city = strip_tags($_POST['city']);
        $address = strip_tags($_POST['address']);

        $errorMsg = '';
        if(empty($user_name)){
            $errorMsg .= '请输入用户名<br/>';
        }
        if(empty($credit_card)){
            $errorMsg .= '请输入信用卡<br/>';
        }
        if(empty($phone_number)){
            $errorMsg .= '请输入电话<br/>';
        }
        if(empty($city)){
            $errorMsg .= '请输入城市.<br/>';
        }
        if(empty($address)){
            $errorMsg .= '请输入地址<br/>';
        }
        $balance = "SELECT balance FROM users where id=$user_id";
		
        if(empty($errorMsg)){
				
				$conna = mysqli_connect('localhost','root','','store');
                $balance = "SELECT balance FROM users where id=$user_id";
				$res=mysqli_query($conna,$balance);
				$rowb = $res->fetch_assoc();
				
                $total = $cart->total();
                $new_balance = $rowb["balance"]-$total;
				
                if($new_balance>=0){                       
						//插入数据库
						$insertOrder ="INSERT INTO orders (user_id,user_name,credit_card,price,phone_number,city,detail_address,day)
													VALUES ($user_id, '".$user_name."', $credit_card, '".$cart->total()."', '".$phone_number."', '".$city."', '".$address."', CURDATE() )";
						$connee = mysqli_connect('localhost','root','','store');
							if($connee){
								mysqli_query($connee,$insertOrder);
							}
						//获取插入order时产生的order_id
						$sql_orderID = "select max(order_id) AS id from orders";
						$resOID = mysqli_query($connee,$sql_orderID);
						$orID= $resOID ->fetch_assoc();
						$orderID=$orID["id"];
						$cartItems = $cart->contents();
                        $sql = '';
                        $sql2 ='';
                        $sql1 ='';
                        foreach($cartItems as $item){ 
                            $sql= "INSERT INTO product_orders (order_id, product_id, product_name, quantity,price,total_amount) 
										VALUES ('".$orderID."', '".$item['id']."', '".$item['name']."', '".$item['qty']."', '".$item['price']."', '".$item['qty']*$item['price']."');";
                            mysqli_query($connee,$sql);
							$sql1= "UPDATE users set balance=$new_balance where id=$user_id;";
                            mysqli_query($connee,$sql1);
							$sql2= "UPDATE products set inventory=inventory-'".$item['qty']."' where product_id='".$item['id']."' ;";
							mysqli_query($connee,$sql2);
						}
							//删除数据库中的购物车项
							$cartItems = $cart->contents();
							$cart->remove($item["rowid"],$item["id"]);
							
							
							// 删除数据库和session中的购物车项
							$uuiid=$_SESSION['user_id'];
							mysqli_query($connee,"DELETE FROM cart WHERE user_id='$uuiid'");
							
							$cart->destroy();
							
                            // 重定位
                            $redirectLoc = 'orderSuccess.php?id='.$orderID;
							$connee ->close();
                }else{
                    $sessData['status']['type'] = 'error';
                    $sessData['status']['msg'] = '您的余额不足';
                }

            }else{
                $sessData['status']['type'] = 'error';
                $sessData['status']['msg'] = 'errorMsg括号问题：viewcart传入参数可能出现错误';
            }
		$_SESSION['sessData'] = $sessData;
    }
}

//跳转页面
header("Location: $redirectLoc");
exit();
?>