<?php
	include'registry.php';
	include 'mysqldb.class.php';

	$reg =new registry();
	$db_connection = new mysqldb($reg);
	$db_connection->newConnection('localhost','root','','itfellow');
	
?>
