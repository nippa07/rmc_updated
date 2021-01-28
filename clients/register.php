<?php
session_start();

require "../db_connect.php";

    $uid = $_POST['uidClient'];
    $emailclient = $_POST['emailclient'];
	$pwd = $_POST['pwdClient'];
	$pwdRepeat = $_POST['pwdrepeat'];
	$token= random_bytes(32);

// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['uidClient'], $_POST['pwdClient'], $_POST['emailclient'], $_POST['pwdrepeat'])) {
	// Could not get the data that should have been sent.
	die ('Please complete the registration form!');
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['uidClient']) || empty($_POST['pwdClient']) || empty($_POST['emailclient']) || empty($_POST['pwdrepeat'])) {
	// One or more values are empty.
	die ('Please complete the registration form');
}
// We check if the repeated pwd is NOT the same.
if ($pwd !== $pwdRepeat) {
	header("Location: ../hosignup.php?error=pwdCheckFailed");
	exit(); 
  } else {
      // We need to check if the account with that username exists.
      $sql= "SELECT uidClient FROM leads WHERE uidClient = ?";
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
              echo '<h1>Username exists, please choose another!</h1>';
              header("Refresh: 2; url= ../hosignup.php?error=UsernameTaken");
              exit();
          } else {
              // Username doesnt exists, insert new account
              $sql= "SELECT emailclient FROM leads WHERE emailclient = ?";
              $stmt= mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt, $sql)) {
                  echo "There was an error";
                  exit();
              } else {
                  mysqli_stmt_bind_param($stmt, "s", $emailclient);
                  mysqli_stmt_execute($stmt);
                  $result= mysqli_stmt_get_result($stmt);
                  $rows= mysqli_fetch_assoc($result);
                  // Store the result so we can check if the account exists in the database.
                  if ($rows > 0) {
                      // Username already exists
                      echo '<h1>Email exists, please choose another!</h1>';
                      header("Refresh: 2; url= ../hosignup.php?error=EmailTaken");
                      exit();
                  } else {
                      $sql= "INSERT INTO leads (emailclient, uidClient, pwdClient, token) VALUES (?, ?, ?, ?)";
                      $stmt= mysqli_stmt_init($conn);
                      if (!mysqli_stmt_prepare($stmt, $sql)) {
                          echo "There was an error";
                          exit();
                      } else {
                          $password = password_hash($_POST['pwdClient'], PASSWORD_DEFAULT);
                          $hashtoken = password_hash($token, PASSWORD_DEFAULT);
                          mysqli_stmt_bind_param($stmt, 'ssss', $_POST['emailclient'], $_POST['uidClient'], $password, $hashtoken);
						  mysqli_stmt_execute($stmt);
						 
                      }
                  }
              }
          }
      }
  }
  header('Refresh:0; url= ../log-in.php');