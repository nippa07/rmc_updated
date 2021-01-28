<?php
require '../db_connect.php';

session_start();

if (
    isset($_POST['bname']) &&
    isset($_POST['type']) &&
    isset($_POST['phone']) &&
    isset($_POST['zip']) &&
    isset($_POST['userAdd'])
) {

    $bname = $_POST['bname'];
    $type = $_POST['type'];
    $phone = $_POST['phone'];
    $zip = $_POST['zip'];
    $userAdd = $_POST['userAdd'];

    $sql = "INSERT INTO `users`(`bname`,`type`,`phone`, `zip`, `userAdd`)
           VALUES (?,?,?,?,?)";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        $_SESSION['status_msg'] = "Oops!! Something Went Wrong Try Later!!!";
        header('Location: clientReviews.php');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "sssss", $bname, $type, $phone, $zip, $userAdd);
        if (mysqli_stmt_execute($stmt)); {
            $_SESSION['status_msg'] = "Business Added successfully!!! ";

            header('Location: clientReviews.php');
            exit();
        }
    }
} else {
    $_SESSION['status_msg'] = "Fill all the details.";
    header('Location: clientReviews.php');
    exit();
}
