<?php
require '../db_connect.php';

session_start();

if (
    isset($_POST['sent_to']) &&
    isset($_POST['received_name']) &&
    isset($_POST['subject']) &&
    isset($_POST['message'])
) {

    if (
        isset($_SESSION['uidClient']) &&
        isset($_SESSION['emailclient']) &&
        isset($_SESSION['id'])
    ) {
        # code...
        $sent_to = $_POST['sent_to'];
        $received_name = $_POST['received_name'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        $sent_by = $_SESSION['id'];
        $sent_name = $_SESSION['uidClient'];

        $sql = "INSERT INTO `messages`(`sent_to`,`received_name`,`subject`, `message`, `sent_by`, `sent_name`)
               VALUES (?,?,?,?,?,?)";

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $_SESSION['status_msg'] = "Oops!! Something Went Wrong Try Later!!!";
            header('Location: home.php');
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ssssss", $sent_to, $received_name, $subject, $message, $sent_by, $sent_name);
            if (mysqli_stmt_execute($stmt)); {
                $_SESSION['status_msg'] = "Your message was sent successfully!!! ";

                header('Location: home.php');
                exit();
            }
        }
    } else {
        $_SESSION['status_msg'] = "Login or sign up to send message";
        header('Location: home.php');
        exit();
    }
} else {
    $_SESSION['status_msg'] = "Please fill the details.";
    header('Location: home.php');
    exit();
}
