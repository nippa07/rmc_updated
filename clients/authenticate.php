<?php
// CHEYENNE'S MODIFICATION START
// Reset all session variables to prevent user to be connected as lead and contractor
session_start();
session_unset();
session_destroy();
// CHEYENNE'S MODIFICATION END

session_start();
// Change this to your connection info.
require '../db_connect.php';

$uidEmail = $_POST['uidEmail'];
$pwdClient = $_POST['pwdClient'];


// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if (!isset($_POST['uidEmail'], $_POST['pwdClient'])) {
    // Could not get the data that should have been sent.
    die('Please fill both the username and password field!');
}
// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $conn->prepare('SELECT uidClient, pwdClient, emailclient, zip, id FROM leads WHERE uidClient = ? OR emailclient = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param('ss', $uidEmail, $uidEmail);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();
}
if ($stmt->num_rows > 0) {
    $stmt->bind_result($uidClient, $password, $emailclient, $zip, $id);
    $stmt->fetch();
    // Account exists, now we verify the password.
    // Note: remember to use password_hash in your registration file to store the hashed passwords.
    if (password_verify($pwdClient, $password)) {
        // Verification success! User has loggedin!
        // Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
        session_regenerate_id();
        $_SESSION['emailclient'] = $emailclient;
        $_SESSION['uidClient'] = $uidClient;
        $_SESSION['zip'] = $zip;
        $_SESSION['id'] = $id;
        // CHEYENNE'S MODIFICATION START
        $_SESSION['Clientstatus'] = "lead";

        if (
            isset($_COOKIE['bname']) &&
            isset($_COOKIE['bizID']) &&
            isset($_COOKIE['1rating']) &&
            isset($_COOKIE['2rating']) &&
            isset($_COOKIE['3rating']) &&
            isset($_COOKIE['4rating']) &&
            isset($_COOKIE['5rating']) &&
            isset($_COOKIE['review'])
        ) {
            $bname = $_COOKIE['bname'];
            $bizID = $_COOKIE['bizID'];
            $rating1 = $_COOKIE['1rating'];
            $rating2 = $_COOKIE['2rating'];
            $rating3 = $_COOKIE['3rating'];
            $rating4 = $_COOKIE['4rating'];
            $rating5 = $_COOKIE['5rating'];
            $cost = $_COOKIE['cost'];
            $review = $_COOKIE['review'];

            $uidClient = $_SESSION['uidClient'];
            $emailclient = $_SESSION['emailclient'];
            $leadID = $_SESSION['id'];

            $sql = "INSERT INTO `review`(`bname`,`bizID`,`professionalism`, `quality`, `communication`, `cleanliness`, `price`, `cost`, `review`, `uidClient`, `emailclient`, `leadID`)
               VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";

            unset($_COOKIE['bname']);
            setcookie("bname", NULL, time() + (86400 * 30), "/");
            unset($_COOKIE['bizID']);
            setcookie("bizID", NULL, time() + (86400 * 30), "/");
            unset($_COOKIE['1rating']);
            setcookie("1rating", NULL, time() + (86400 * 30), "/");
            unset($_COOKIE['2rating']);
            setcookie("2rating", NULL, time() + (86400 * 30), "/");
            unset($_COOKIE['3rating']);
            setcookie("3rating", NULL, time() + (86400 * 30), "/");
            unset($_COOKIE['4rating']);
            setcookie("4rating", NULL, time() + (86400 * 30), "/");
            unset($_COOKIE['5rating']);
            setcookie("5rating", NULL, time() + (86400 * 30), "/");
            unset($_COOKIE['cost']);
            setcookie("cost", NULL, time() + (86400 * 30), "/");
            unset($_COOKIE['review']);
            setcookie("review", NULL, time() + (86400 * 30), "/");

            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header('Location: home.php');
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ssssssssssss", $bname, $bizID, $rating1, $rating2, $rating3, $rating4, $rating5, $cost, $review, $uidClient, $emailclient, $leadID);
                if (mysqli_stmt_execute($stmt)); {
                    header('Location: home.php');
                    exit();
                }
            }
        }

        // CHEYENNE'S MODIFICATION END
        header('Location: home.php');
    } else {
        echo 'Incorrect password!';
    }
} else {
    echo 'Incorrect username!';
}
$stmt->close();
