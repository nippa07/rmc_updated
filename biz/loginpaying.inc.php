<?php
// CHEYENNE'S MODIFICATION START
// Reset all session variables to prevent user to be connected as lead and contractor
session_start();
session_unset();
session_destroy();
// CHEYENNE'S MODIFICATION END

session_start();

$uidEmail= $_POST['uidEmail'];

require '../db_connect.php';

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['uidEmail'], $_POST['pwdUsers']) ) {
	// Could not get the data that should have been sent.
	die ('Please fill both the username and password field!');
}
// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $conn->prepare('SELECT id, bname, uidUsers, pwdUsers, zip, paying FROM users WHERE uidUsers = ? OR emailUsers = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('ss', $_POST['uidEmail'], $_POST['uidEmail']);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();
if ($stmt->num_rows > 0) {
	$stmt->bind_result($id, $bname, $uidUsers, $password, $zip, $paying);
	$stmt->fetch();
	// Account exists, now we verify the password.
	// Note: remember to use password_hash in your registration file to store the hashed passwords.
	if (password_verify($_POST['pwdUsers'], $password)) {
		// Verification success! User has loggedin!
		// Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
		if ($paying == 0) {
        $sql= "UPDATE users SET Paying= '1' WHERE uidUsers = '$uidEmail' OR emailUsers = '$uidEmail'"; 
	    $stmt= mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was an error";
        exit();
        } else {
        $stmt->execute();}

        session_regenerate_id();
		$_SESSION['zip'] = $zip;
		$_SESSION['uidUsers'] = $uidUsers;
		$_SESSION['id'] = $id;
		$_SESSION['bname'] = $bname;
		// CHEYENNE'S MODIFICATION START
		$_SESSION['userStatus'] = "contractor";
        // CHEYENNE'S MODIFICATION END
        
        
		
		header('Location: home.php');
	} else {
		echo 'Incorrect password!';
	}
} else {
	echo 'Incorrect username!';
}
	$stmt->close();
}
}
?>