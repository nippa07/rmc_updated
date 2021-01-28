<?php

if (isset($_POST["pwdresetsubmit"])) {
    $selector= $_POST["selector"];
    $validator= $_POST["validator"];
    $pwd = $_POST["pwd"];
    $pwdrepeat= $_POST["pwdRepeat"];
    

if (empty($pwd) || empty($pwdrepeat)) {
    header("Location: bizsignup.com?error&tryagain");
    exit();
} else if ($pwd != $pwdrepeat) {
    header("Location: bizsignin.php?error&pwddonotmatch");
    exit();
}

$currentDate = date("U");

require "../db_connect.php";


$sql = "SELECT * FROM pwdreset WHERE pwdResetSelector= ? AND pwdResetExpires >= ?";
$stmt= mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "There was an error";
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    if (!$row= mysqli_fetch_assoc($result)) {
        echo "You need to resubmit your 1 reset request.";
        exit();
    } else {
    
    $tokenBin = hex2bin($validator);
    $tokenCheck = password_verify($tokenBin, $row["pwdResetToken"]);
  if ($tokenCheck === false) {
      echo "you need to resubmit your reset 2 request.";
      exit();
  } elseif ($tokenCheck === true) {

    $tokenEmail = $row['pwdResetEmail'];

    $sql = "SELECT * FROM users WHERE emailUsers= ?";
    $stmt= mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was an 1 error";
        exit();
    } else {

    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$row= mysqli_fetch_assoc($result)) {
        echo "there was an 2 error!.";
        exit();
    } else {

    $sql = "UPDATE users SET pwdUsers = ? WHERE emailUsers= ?";
    $stmt= mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was an 3 error";
        exit();
    } else {
       $newPwdHash = password_hash($pwd, PASSWORD_DEFAULT);
       mysqli_stmt_bind_param($stmt, "ss", $newPwdHash, $tokenEmail);
       mysqli_stmt_execute($stmt);
  
       $sql= "DELETE FROM pwdreset WHERE pwdResetEmail= ?";
       $stmt= mysqli_stmt_init($conn);
       if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was a fucking error";
        exit();
    } else {
    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
    mysqli_stmt_execute($stmt);
    header("Location: bizsignin.php");
}  
    } 
    }
}
  }
}
}

} else {
    header("Location: ../index.php");

}