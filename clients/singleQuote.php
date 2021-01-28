<?php
session_start();

require ('../db_connect.php');

$uID = $_SESSION["uID"];

if ($sql = "SELECT emailUsers FROM users WHERE ($uID IS $uID )");{
    $result = $conn->query($sql);

    

    while ($row = $result->fetch_assoc()) {
        $to = $row['emailUsers'];

        $subject = 'You Have A New Lead';

        $message = "You recieved a lead in your work area. Please, call or email then within 24 hours.\n\n"."Here are the details:\n\nName: $fname\n\nEmail: $email\n\nPhone: $phone\n\nZip: $zip\n\nTime: $time\n\nBudget: $money\n\nType: $type\n\nLeadInfo:\n$leadinfo";
    

        $headers = "From: RateMyContractor <noreply@ratemycontractor.com>\r\n";
    

        mail($to, $subject, $message, $headers);

        header('Refresh: 0; url= home.php');
    }
}


?>