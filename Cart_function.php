<?php
// Start session
if(!session_id()){
    session_start();
}

class CartFunction {
    protected $cart_contents = array();
	/**
	 * contents 结构
	 * cart_contents[item标记字段][具体元素])
	 */
    public function __construct(){
        // 从session获得购物车数组
        $this->cart_contents = !empty($_SESSION['cart_contents'])?$_SESSION['cart_contents']:NULL;
        if ($this->cart_contents === NULL){
            //如果检测到登录账号，从数据库读取该账号的购物车信息
			if(!empty($_SESSION['user_id'])){
				$uiddd = $_SESSION['user_id'];
				$conne = mysqli_connect('localhost','root','','store');
				$cart_uid = "SELECT cart.product_id,product_name, cart.price, quantity 
							FROM cart 
							LEFT JOIN products
							ON cart.product_id = products.product_id
							WHERE user_id = $uiddd";
				$cartu=mysqli_query($conne,$cart_uid);
				if($conne){
					while($row = $cartu->fetch_array()){
						$itemData = array(
							'id' => $row['product_id'],
							'name' => $row['product_name'],
							'price' => $row['price'],
							'qty' => $row['quantity']
						);
						// 插入购物车页面的session
						$this->insert($itemData);
					}
				}
			}
			
			
			// 设置默认值0，防止空字符串报错
            $this->cart_contents = array('cart_total' => 0, 'total_items' => 0);
        }
    }

    /**
     * CartFunction Contents: 返回购物车数组
     */
    public function contents(){
					
		$cart =$this->cart_contents;

		// 用于表格，删掉不需要的项
		unset($cart['total_items']);
		unset($cart['cart_total']);
		return $cart;
	}
	

    /**
     * 获取购物车具体项
     */
    public function get_item($row_id){
        return (in_array($row_id, array('total_items', 'cart_total'), TRUE) OR ! isset($this->cart_contents[$row_id]))
            ? FALSE
            : $this->cart_contents[$row_id];
    }

    /**
     * Total Items: 返回购物车内物品数量
     */
    public function total_items(){
        return $this->cart_contents['total_items'];
    }

    /**
     * CartFunction Total: 购物车内商品总额
     */
    public function total(){
        return $this->cart_contents['cart_total'];
    }

    /**
     * 更新数组，存session，并调用save()存入购物车
     */
    public function insert($item = array()){
        if(!is_array($item) OR count($item) === 0){
            return FALSE;
        }else{
            if(!isset($item['id'], $item['name'], $item['price'], $item['qty'])){
                return FALSE;
            }else{
                /*
                 * 插入
                 */
                // prep the quantity
                $item['qty'] =  $item['qty'];
                if($item['qty'] == 0){
                    return FALSE;
                }
                // prep the price
                $item['price'] =  $item['price'];
                // 为购物车的每个item创造标记
                $rowid = md5($item['id']);
                // 如果商品已存在，获取其现有数量
                $old_qty = isset($this->cart_contents[$rowid]['qty']) ? (int) $this->cart_contents[$rowid]['qty'] : 0;
                // 插入session
                $item['rowid'] = $rowid;
                $item['qty'] += $old_qty;
                $this->cart_contents[$rowid] = $item;

                // save CartFunction Item
                if($this->save_cart()){
                    return isset($rowid) ? $rowid : TRUE;
                }else{
                    return FALSE;
                }
            }
        }
    }

    /**
     * 更新购物车(商品数量)
     */
    public function update($item = array()){
        if (!is_array($item) OR count($item) === 0){
            return FALSE;
        }else{
            if (!isset($item['rowid'], $this->cart_contents[$item['rowid']])){
                return FALSE;
            }else{
                // prep the quantity
                if(isset($item['qty'])){
                    $item['qty'] = $item['qty'];
                    //删除数量为0的项
                    if ($item['qty'] == 0){
                        unset($this->cart_contents[$item['rowid']]);
                        return TRUE;
                    }
                }

                // 找到需要更新的值
                $keys = array_intersect(array_keys($this->cart_contents[$item['rowid']]), array_keys($item));
                // prep the price
                if(isset($item['price'])){
                    $item['price'] =  $item['price'];
                }
                //保留商品名和id
                foreach(array_diff($keys, array('id', 'name')) as $key){
                    $this->cart_contents[$item['rowid']][$key] = $item[$key];
                }
                // save cart data
                $this->save_cart();
                return TRUE;
            }
        }
    }

    /**
     * 将购物车数组存入session
     */
    protected function save_cart(){
        $this->cart_contents['total_items'] = $this->cart_contents['cart_total'] = 0;
        foreach ($this->cart_contents as $key => $val){
            if(!is_array($val) OR !isset($val['price'], $val['qty'])){
                continue;
            }

            $this->cart_contents['cart_total'] += ($val['price'] * $val['qty']);
            $this->cart_contents['total_items'] += $val['qty'];
            $this->cart_contents[$key]['subtotal'] = ($this->cart_contents[$key]['price'] * $this->cart_contents[$key]['qty']);
        }

        //删除空购物车
        if(count($this->cart_contents) <= 2){
            unset($_SESSION['cart_contents']);
            return FALSE;
        }else{
            $_SESSION['cart_contents'] = $this->cart_contents;
            return TRUE;
        }
    }

    /**
     * 移除购物车内的项目
     */
    public function remove($row_id,$pid){
        // 更新数据库cart表
		$conn = mysqli_connect('localhost','root','','store');
		if($conn){
			$uid=$_SESSION['user_id'];			
			mysqli_query($conn,"DELETE FROM cart WHERE product_id='$pid' AND user_id='$uid'");			
			}
		// 销毁变量，并保存新表
        unset($this->cart_contents[$row_id]);
        $this->save_cart();
        return TRUE;
    }

    /**
     * 关闭空会话和空购物车
     */
    public function destroy(){
        $this->cart_contents = array('cart_total' => 0, 'total_items' => 0);
        unset($_SESSION['cart_contents']);
    }
}