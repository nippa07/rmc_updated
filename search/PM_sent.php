<?php
require '../db_connect.php';

session_start();

if (
    isset($_POST['subject']) &&
    isset($_POST['message']) 
) {

    if (
        isset($_SESSION['uidClient']) &&
        isset($_SESSION['id'])
    ) {
        # code...
        $bizID = $_POST['bizID'];
        $bname = $_POST['bname'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];
        

        $uidClient = $_SESSION['uidClient'];
        $leadID = $_SESSION['id'];

        $sql = "INSERT INTO `private_messages`(`bizID`,`bname`, `subject`, `message`, `leadID`, `uidClient`)
               VALUES (?,?,?,?,?,?)";

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $_SESSION['status_msg'] = "Oops!! Something Went Wrong Try Later!!!";
            header('Location: profileviewer.php');
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ssssss", $bizID, $bname, $subject, $message, $leadID, $uidClient);
            if (mysqli_stmt_execute($stmt)); {
                $_SESSION['status_msg'] = "Your review was submitted successfully!!! ";

                header('Location: profileviewer.php');
                exit();
            }
        }
    } else {
        setcookie("bizID", $_POST['bizID'], time() + (86400 * 30), "/");
        setcookie("bname", $_POST['bname'], time() + (86400 * 30), "/");
        setcookie("subject", $_POST['subject'], time() + (86400 * 30), "/");
        setcookie("message", $_POST['message'], time() + (86400 * 30), "/");

        $_SESSION['status_msg'] = "Login or sign up to complete review";
        header('Location: ../sign-up.php');
        exit();
    }
} else {
    $_SESSION['status_msg'] = "Provide all the info.";
    header('Location: profileviewer.php');
    exit();
}