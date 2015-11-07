<?php
	session_start();
		
	include 'db.php';
	header('Content-type: application/json');
	$post_json = json_decode($HTTP_RAW_POST_DATA);
	$username = $post_json->username;
	$password = $post_json->password;
	$sql = "select id from user where email = '$username'";//" and password = '$password'";//md5('$password')";
	$result = mysql_query($sql, $link);

	
	if ($result == false) {	
		http_response_code(500);
		exit;
	}
	
	$num_rows = mysql_num_rows($result);


	if ($num_rows >= 1) {

		$sql_products = "select * from product";//" and password = '$password'";//md5('$password')";
	$result_products = mysql_query($sql_products, $link);
		$rows = array();
while($r = mysql_fetch_assoc($result_products)) {
    $rows[] = $r;
}
$data = $arrayName = array('products' => $rows );
echo json_encode($data);

		http_response_code(200);
		mysql_close($link);
		die();
	}else{
		http_response_code(401);
		die();
	}
	
	mysql_close($link);
	
?>