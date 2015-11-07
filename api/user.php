<?php
	session_start();
		
	include 'db.php';
	header('Content-type: application/json');
	$post_json = json_decode($HTTP_RAW_POST_DATA);
	$username = $post_json->username;
	$password = $post_json->password;
	$sql = "select email,name from user where email = '$username'";//" and password = '$password'";//md5('$password')";
	$result = mysql_query($sql, $link);

	
	if ($result == false) {	
		http_response_code(500);
		exit;
	}
	
	$num_rows = mysql_num_rows($result);


	if ($num_rows >= 1) {
		$rows = array();
while($r = mysql_fetch_assoc($result)) {
    $rows[] = $r;
}

echo json_encode($rows[0]);

		http_response_code(200);
		mysql_close($link);
		die();
	}else{
		http_response_code(401);
		die();
	}
	
	mysql_close($link);
	
?>