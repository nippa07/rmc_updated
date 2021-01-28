<?php

$dbname = "bizclients";	
$host = "localhost";
$user = "roots";
$password = "impossible13";
			
		try{$db = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8mb4', ''.$user.'', ''.$password) or die(print_r($db->errorInfo())); }
		catch (Exception $e){die('Erreur : ' . $e->getMessage());}

?>