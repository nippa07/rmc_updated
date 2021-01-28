<?php
require 'db_connect.php';

session_start();

if (
    isset($_POST['bname']) &&
    isset($_POST['bizID']) &&
    isset($_POST['1rating']) &&
    isset($_POST['2rating']) &&
    isset($_POST['3rating']) &&
    isset($_POST['4rating']) &&
    isset($_POST['5rating']) &&
    isset($_POST['review'])
) {

    if (
        isset($_SESSION['uidClient']) &&
        isset($_SESSION['emailclient']) &&
        isset($_SESSION['id'])
    ) {
        # code...
        $bname = $_POST['bname'];
        $bizID = $_POST['bizID'];
        $rating1 = $_POST['1rating'];
        $rating2 = $_POST['2rating'];
        $rating3 = $_POST['3rating'];
        $rating4 = $_POST['4rating'];
        $rating5 = $_POST['5rating'];
        $cost = $_POST['cost'];
        $review = $_POST['review'];

        $uidClient = $_SESSION['uidClient'];
        $emailclient = $_SESSION['emailclient'];
        $leadID = $_SESSION['id'];

        $sql = "INSERT INTO `review`(`bname`,`bizID`,`professionalism`, `quality`, `communication`, `cleanliness`, `price`, `cost`, `review`, `uidClient`, `emailclient`, `leadID`)
               VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $_SESSION['status_msg'] = "Oops!! Something Went Wrong Try Later!!!";
            header('Location: reviews.php');
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ssssssssssss", $bname, $bizID, $rating1, $rating2, $rating3, $rating4, $rating5, $cost, $review, $uidClient, $emailclient, $leadID);
            if (mysqli_stmt_execute($stmt)); {
                $_SESSION['status_msg'] = "Your review was submitted successfully!!! ";

                header('Location: reviews.php');
                exit();
            }
        }
    } else {
        setcookie("bname", $_POST['bname'], time() + (86400 * 30), "/");
        setcookie("bizID", $_POST['bizID'], time() + (86400 * 30), "/");
        setcookie("1rating", $_POST['1rating'], time() + (86400 * 30), "/");
        setcookie("2rating", $_POST['2rating'], time() + (86400 * 30), "/");
        setcookie("3rating", $_POST['3rating'], time() + (86400 * 30), "/");
        setcookie("4rating", $_POST['4rating'], time() + (86400 * 30), "/");
        setcookie("5rating", $_POST['5rating'], time() + (86400 * 30), "/");
        setcookie("cost", $_POST['cost'], time() + (86400 * 30), "/");
        setcookie("review", $_POST['review'], time() + (86400 * 30), "/");

        $_SESSION['status_msg'] = "Login or sign up to complete review";
        header('Location: sign-up.php');
        exit();
    }
} else {
    $_SESSION['status_msg'] = "Provide the ratings for all the features.";
    header('Location: reviews.php');
    exit();
}
