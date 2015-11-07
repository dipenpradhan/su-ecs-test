<?php
	$link = mysql_connect("localhost:33306" , "ecs", "1234");
	
	if ($link == false) {
		echo "Error: can't connect to database server";
		exit;
	}
	
	if (mysql_select_db("ecs", $link) == false) {
		echo "Error: can't connect to database";
		exit;
	}
?>