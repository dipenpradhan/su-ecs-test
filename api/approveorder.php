
<?php
session_start();
include 'db.php';

$oid=$_GET['order_id'];

	$sql_order = "update orders set status='processed' WHERE id='$oid'";
	$result_order = mysql_query($sql_order, $link);

$result_useremail = mysql_query("select u.email as emails from orders o inner join user u on o.user_id=u.id where o.id='$oid'", $link);
$useremail=array();
while ($a = mysql_fetch_assoc($result_useremail))
		{
		$useremail[] = $a;
		}
$shell_command='./send_mail.sh "';
$shell_command.=$useremail[0]['emails'];
$shell_command.='" ';
$shell_command.='"';
$shell_command.=' Your order no.';
$shell_command.=$oid;
$shell_command.=' has been approved."';
$output = shell_exec($shell_command);



	$data = array(
		'order_id' => $order_id
	);
	echo json_encode($data);
	http_response_code(200);
	mysql_close($link);
	die();

mysql_close($link);
?>
