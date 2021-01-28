<?php
session_start();
if (!isset($_SESSION['uidClient'])) {
	header('Location: ../log-in.php');
	exit();
}

    require ('../db_connect.php');
    
    $uid = $_SESSION['uidClient'];
   
    $fname = $_POST['fname'];
    $emailclient = $_POST['emailclient'];
    $phone = $_POST['phone'];
    $zip = $_POST['zip'];
    $time = $_POST['time'];
    $money = $_POST['money'];
    $type = $_POST['type'];
	$leadinfo = $_POST['leadinfo'];
	

    $_SESSION['fname']=$_POST['fname'];
    $_SESSION['emailclient']=$_POST['emailclient'];
    $_SESSION['phone']=$_POST['phone'];
    $_SESSION['zip']=$_POST['zip'];
    $_SESSION['time']=$_POST['time'];
    $_SESSION['money']=$_POST['money'];
    $_SESSION['type']=$_POST['type'];
    $_SESSION['leadinfo']=$_POST['leadinfo'];



    $sql = "UPDATE leads SET fname='$fname', emailclient='$emailclient', phone='$phone', zip='$zip', time='$time', money='$money', type='$type', leadinfo='$leadinfo' WHERE uidClient= '$uid'";
if (mysqli_query($conn, $sql)) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}   

    
header('Refresh: 0; url= getEstimates.email.php');

mysqli_close($conn);
?>