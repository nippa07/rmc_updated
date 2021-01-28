<?php

if (isset($_POST['submit'])) {
    $selector= bin2hex(random_bytes(8));
    $token= random_bytes(12);

    // $url= "https://ratemycontractor.com/cnpwd.php?selector=".$selector."&validator=".bin2hex($token);
    $url= "http://localhost/rmcv4/cnpwd.php?selector=".$selector."&validator=".bin2hex($token);

    $expires= date("U") + 900;

    require "db_connect.php";

    $userEmail= $_POST["email"];

    $sql= "INSERT INTO pwdreset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?,?,?,?)";
    $stmt= mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was an error";
        exit();
    } else {
    $hashedToken= password_hash($token, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashedToken, $expires);
    mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    $to = $userEmail;

    $subject = 'Reset your password on Rate My Contractor';

    $message = '<p>We recieved a password reset request. The link to reset your password is below. If you did not make this request, you can ignore this email.';
    $message .= 'Here is your password reset link: </br>';
    $message .= '<a href="' .$url . '">' .$url . '</a></p>';

    $headers = "From: RateMyContractor <noreply@ratemycontractor.com>\r\n";
    $headers .= "Content-type: text/html\r\n";

    if(mail($to, $subject, $message, $headers))
        echo "Email Success";
    else
        echo "Email sending failed";

    header("Location: resetpwd.php?reset=success");

}else {
    header("Location: index.php");
}