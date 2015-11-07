
<?php
session_start();
include 'db.php';

header('Content-type: application/json');
$post_json = json_decode($HTTP_RAW_POST_DATA);
$username = $post_json->username;
$password = $post_json->password;
$sql = "select id from user where email = '$username'"; //" and password = '$password'";//md5('$password')";
$result = mysql_query($sql, $link);

if ($result == false)
	{
	http_response_code(500);
	exit;
	}

$num_rows = mysql_num_rows($result);
$order_id;
$user;
if ($num_rows >= 1)
	{
	while ($r = mysql_fetch_assoc($result))
		{
		$rows[] = $r;
		}
$user_id=$rows[0]['id'];
	$order = $post_json->order;
	$sql_order = "insert into orders (user_id,status) VALUES('$user_id','PENDING')";
	$result_order = mysql_query($sql_order, $link);
	$order_id = mysql_insert_id($link);
	foreach($order->cart as $p)
		{
		$sql_ord_prod = "insert into order_product (order_id,product_id) VALUES('$order_id','$p->id')"; //" and password = '$password'";//md5('$password')";
		$result_ord_prod = mysql_query($sql_ord_prod, $link);
		if($result_ord_prod==true){
			$sql_inventory = "update product set inventory=inventory-1 where id='$p->id'"; //" and password = '$password'";//md5('$password')";
			$result_inventory = mysql_query($sql_inventory, $link);
		}
		}
$result_admins = mysql_query("select group_concat(email) as emails from admin", $link);
$admins=array();
while ($a = mysql_fetch_assoc($result_admins))
		{
		$admins[] = $a;
		}
$shell_command='./send_mail.sh "';
$shell_command.=$admins[0]['emails'];
$shell_command.='" ';
$shell_command.='"';
$shell_command.=' New Order received. Click http://localhost/admin/#/order/';
$shell_command.=$order_id;
$shell_command.=' to view and approve."';
$output = shell_exec($shell_command);



	$data = array(
		'user'=>$shell_command,
		'order_id' => $order_id
	);
	echo json_encode($data);
	http_response_code(200);
	mysql_close($link);
	die();
	}
  else
	{
	http_response_code(401);
	die();
	}

mysql_close($link);
?>
