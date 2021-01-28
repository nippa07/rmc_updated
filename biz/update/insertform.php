<?php
session_start();

require 'db_connect.php';

$uid = $_SESSION['uidUsers'];

    $bname = $_POST['bname'];
	$phone = $_POST['phone'];
	$email = $_POST['emailUsers'];
	$web = $_POST['web'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];
	$type = $_POST['type'];







$sql = "UPDATE users SET bname='$bname', phone='$phone', emailUsers='$email', web='$web', city='$city', state='$state', zip='$zip', type='$type' WHERE uidUsers= '$uid'";
if (mysqli_query($conn, $sql)) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}
header('Refresh: 0; url= update.php');

mysqli_close($conn);
?>