<?php
require '../db_connect.php';

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
        header('Location: clientReviews.php');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "ssssssssssss", $bname, $bizID, $rating1, $rating2, $rating3, $rating4, $rating5, $cost, $review, $uidClient, $emailclient, $leadID);
        if (mysqli_stmt_execute($stmt)); {
            $_SESSION['status_msg'] = "Your review was submitted successfully!!! ";

            header('Location: clientReviews.php');
            exit();
        }
    }
} else {
    $_SESSION['status_msg'] = "Provide the ratings for all the features.";
    header('Location: clientReviews.php');
    exit();
}
