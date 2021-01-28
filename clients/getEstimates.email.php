<?php
session_start();

	require_once ('../db_connect.php');
   
    $fname = $_SESSION['fname'];
    $email = $_SESSION['emailclient'];
    $phone = $_SESSION['phone'];
    $zip = $_SESSION['zip'];
    $time = $_SESSION['time'];
    $money = $_SESSION['money'];
    $type = $_SESSION['type'];
    $leadinfo = $_SESSION['leadinfo'];
    $array = array($fname, $phone, $zip, $time, $money, $type, $leadinfo);

    
    $sql = "SELECT * FROM users WHERE bname LIKE '%$type%' AND zip = '$zip' ORDER BY RAND() LIMIT 7";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result)) {
   
    $to = $row['emailUsers'];

    $subject = 'You Have A New Lead';

    $message = "You recieved a lead in your work area. Please, call or email then within 24 hours.\n\n"."Here are the details:\n\nName: $fname\n\nEmail: $email\n\nPhone: $phone\n\nZip: $zip\n\nTime: $time\n\nBudget: $money\n\nType: $type\n\nLeadInfo:\n$leadinfo";
    

    $headers = "From: RateMyContractor <noreply@ratemycontractor.com>\r\n";
    

    mail($to,$subject,$message,$headers);

    header('Refresh: 0; url= home.php');

}
?>