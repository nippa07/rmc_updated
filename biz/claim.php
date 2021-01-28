<?php
session_start();

require "../db_connect.php";
    $id = $_POST['id'];
    $uid = $_POST['uidUsers'];
    $emailUsers = $_POST['emailUsers'];
	$pwd = $_POST['pwdUsers'];
    $pwdRepeat = $_POST['pwdrepeat'];
    $claimed = '1';


// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['uidUsers'], $_POST['pwdUsers'], $_POST['emailUsers'], $_POST['pwdrepeat'])) {
	// Could not get the data that should have been sent.
	die ('Please complete the registration form!');
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['uidUsers']) || empty($_POST['pwdUsers']) || empty($_POST['emailUsers']) || empty($_POST['pwdrepeat'])) {
	// One or more values are empty.
	die ('Please complete the registration form');
}
// We check if the repeated pwd is NOT the same.
if ($pwd !== $pwdRepeat) {
	header("Location: hosignup.php?error=pwdcheck&uid=".$uid."&mail=".$email);
	exit(); 
  } else {
// We need to check if the account with that username exists.
$sql= 'SELECT uidUsers FROM users WHERE uidUsers = ?';
	$stmt= mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was an error";
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "s", $uid);
	mysqli_stmt_execute($stmt);
	$result= mysqli_stmt_get_result($stmt);
	$rows= mysqli_fetch_assoc($result);
	// Store the result so we can check if the account exists in the database.
	if ($rows > 0) {
		// Username already exists
		echo 'Username exists, please choose another!';
		exit();
	} else {
		// Username doesnt exists, insert new account
	$sql= 'SELECT emailUsers FROM users WHERE emailUsers = ?';
	$stmt= mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
    	echo "There was an error";
    	exit();
	} else {
    mysqli_stmt_bind_param($stmt, "s", $emailUsers);
	mysqli_stmt_execute($stmt);
	$result= mysqli_stmt_get_result($stmt);
	$rows= mysqli_fetch_assoc($result);
	// Store the result so we can check if the account exists in the database.
	if ($rows > 0) {
		// Username already exists
		echo 'Email exists, please choose another!';
		exit();
	} else {
    $sql= "UPDATE users SET emailUsers=?, uidUsers=?, pwdUsers=?, claimed=? WHERE id=?"; 
	$stmt= mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was an error";
        exit();
    } else {
	$password = password_hash($_POST['pwdUsers'], PASSWORD_DEFAULT);
	$stmt->bind_param('sssss', $_POST['emailUsers'], $_POST['uidUsers'], $password, $claimed, $id);
    $stmt->execute();
    header('Refresh:0; url= ./bizsignin.php');
	}
}
}
  }
}
  }
 ?>