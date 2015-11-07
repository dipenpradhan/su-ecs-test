<?php
	session_start();
		
	include 'db.php';
	header('Content-type: application/json');
	// $post_json = json_decode($HTTP_RAW_POST_DATA);
	// $username = $post_json->username;
	// $password = $post_json->password;
	// $sql = "select id from user where email = '$username'";//" and password = '$password'";//md5('$password')";
	// $result = mysql_query($sql, $link);

	
	// if ($result == false) {	
	// 	http_response_code(500);
	// 	exit;
	// }
	
	// $num_rows = mysql_num_rows($result);


	$oid=$_GET['id'];
	$type=$_GET['type'];
	// if ($num_rows >= 1) {
	$sql_orders="select o.id,o.status,u.id as user_id,u.name,GROUP_CONCAT(p.name SEPARATOR ', ') AS items from orders o 
		inner join user u on o.user_id=u.id 
		inner join order_product op on op.order_id=o.id
		inner join product p on op.product_id = p.id ";
		
	if($type==null || $type=='all'){
		if($oid!=null){
			$sql_orders.=" where o.id = '$oid'";
		}

	}else{
		$sql_orders .= " where status='$type' ";
		if($oid!=null){
			$sql_orders.=" and o.id = '$oid'";
		}
	}
	$sql_orders.=" group by o.id";

	$result_orders = mysql_query($sql_orders, $link);
		$rows = array();
while($r = mysql_fetch_assoc($result_orders)) {
    $rows[] = $r;
}


$data = $arrayName = array('orders' => $rows );
echo json_encode($data);

		http_response_code(200);
		mysql_close($link);
		die();
	// }else{
		// http_response_code(401);
		// die();
	// }
	
	mysql_close($link);
	
?>