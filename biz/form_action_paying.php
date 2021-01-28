<?php

	require_once ('../db_connect.php');

	$uid = $_POST['uidUsers'];
	$pwd = $_POST['pwdUsers'];
	$pwdRepeat = $_POST['pwd-repeat'];
    $bname = $_POST['bname'];
    $fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$phone = $_POST['phone'];
	$email = $_POST['emailUsers'];
	$web = $_POST['web'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];
	$claimed = '1';
	$paying = '1';

	if (empty($uid) || empty($email) || empty($pwd) || empty($pwdRepeat)) {
		header("Location: bizsignup.php?error=emptyfields&uid=".$uid."&mail=".$email);
		exit();
	  }
	  // We check for an invalid uid AND invalid e-mail.
	  else if (!preg_match("/^[a-zA-Z0-9]*$/", $uid) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header("Location: bizsignup.php?error=invaliduidmail");
		exit();
	  }
	  // We check for an invalid uid. In this case ONLY letters and numbers.
	  else if (!preg_match("/^[a-zA-Z0-9]*$/", $uid)) {
		header("Location: bizsignup.php?error=invaliduid&mail=".$email);
		exit();
	  }
	  // We check for an invalid e-mail.
	  else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header("Location: bizsignup.php?error=invalidmail&uid=".$uid);
		exit();
	  }
	  // We check if the repeated pwd is NOT the same.
	  else if ($pwd !== $pwdRepeat) {
		header("Location: bizsignup.php?error=pwdcheck&uid=".$uid."&mail=".$email);
		exit(); 
	  }

	else {

		// We also need to include another error handler here that checks whether or the username is already taken. We HAVE to do this using prepared statements because it is safer!
	
		// First we create the statement that searches our database table to check for any identical usernames.
		$sql = "SELECT uidUsers FROM users WHERE uidUsers=?;";
		// We create a prepared statement.
		$stmt = mysqli_stmt_init($conn);
		// Then we prepare our SQL statement AND check if there are any errors with it.
		if (!mysqli_stmt_prepare($stmt, $sql)) {
		  // If there is an error we send the user back to the signup page.
		  header("Location: bizsignup.php?error=sqlerror");
		  exit();
		}

	else {
			// Next we need to bind the type of parameters we expect to pass into the statement, and bind the data from the user.
			// In case you need to know, "s" means "string", "i" means "integer", "b" means "blob", "d" means "double".
			mysqli_stmt_bind_param($stmt, "s", $uid);
			// Then we execute the prepared statement and send it to the database!
			mysqli_stmt_execute($stmt);
			// Then we store the result from the statement.
			mysqli_stmt_store_result($stmt);
			// Then we get the number of result we received from our statement. This tells us whether the username already exists or not!
			$resultCount = mysqli_stmt_num_rows($stmt);
			// Then we close the prepared statement!
			mysqli_stmt_close($stmt);
			// Here we check if the username exists.
			if ($resultCount > 0) {
			  echo 'username taken';
			  exit();
			}

	else {
		$sql= 'SELECT emailUsers FROM users WHERE emailUsers = ?';
	$stmt= mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
    	echo "There was an error";
    	exit();
	} else {
    mysqli_stmt_bind_param($stmt, "s", $email);
	mysqli_stmt_execute($stmt);
	$result= mysqli_stmt_get_result($stmt);
	$rows= mysqli_fetch_assoc($result);
	// Store the result so we can check if the account exists in the database.
	if ($rows > 0) {
		// Username already exists
		echo 'Email exists, please choose another!';
		exit();
	} else {
		$sql= "SELECT id FROM users WHERE id = ?";
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "There was an error";
                            exit();
                        } else {
                            mysqli_stmt_bind_param($stmt, "s", $id);
                            mysqli_stmt_execute($stmt);
                            $result= mysqli_stmt_get_result($stmt);
                            $rows= mysqli_fetch_assoc($result);
                            if ($rows > 0) {
                                $sql= "UPDATE users SET uidUsers = ?, pwdUsers = ?, bname = ?, fname = ?, lname = ?, phone = ?, emailUsers = ?, web = ?, city = ?, state = ?, zip = ?, paying = ?, claimed = ? WHERE id = ?";
                                $stmt = mysqli_stmt_init($conn);
                                mysqli_stmt_prepare($stmt, $sql);
                                $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
                                mysqli_stmt_bind_param($stmt, "ssssssssssssss", $uid, $hashedPwd, $bname, $fname, $lname, $phone, $email, $web, $city, $state, $zip, $paying, $claimed, $id);
								mysqli_stmt_execute($stmt);
								header('Refresh: 0; url= bizsignin.php');
                                    exit();
                            } else {
                                $sql = "INSERT INTO users (`uidUsers`,`pwdUsers`,`bname`,`fname`,`lname`,`phone`,`emailUsers`,`web`,`city`,`state`,`zip`,`paying`,`claimed`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
                                // Here we initialize a new statement using the connection from the dbh.inc.php file.
                                $stmt = mysqli_stmt_init($conn);
                                // Then we prepare our SQL statement AND check if there are any errors with it.
                                if (!mysqli_stmt_prepare($stmt, $sql)) {
                                    // If there is an error we send the user back to the signup page.
                                    header("Location: bizsignup.php?error=sqlerror");
                                    exit();
                                } else {

                    // If there is no error then we continue the script!
          
                                    // Before we send ANYTHING to the database we HAVE to hash the users password to make it un-readable in case anyone gets access to our database without permission!
                                    // The hashing method I am going to show here, is the LATEST version and will always will be since it updates automatically. DON'T use md5 or sha256 to hash, these are old and outdated!
                                    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
          
                                    // Next we need to bind the type of parameters we expect to pass into the statement, and bind the data from the user.
                                    mysqli_stmt_bind_param($stmt, "sssssssssssss", $uid, $hashedPwd, $bname, $fname, $lname, $phone, $email, $web, $city, $state, $zip, $paying, $claimed);
                                    // Then we execute the prepared statement and send it to the database!
                                    // This means the user is now registered! :)
                                    mysqli_stmt_execute($stmt);
                                    header('Refresh: 0; url= bizsignin.php');
                                    exit();
                                }
                            }
                        }
                    }
                }
            }
        }
	}

			// Then we close the prepared statement and the database connection!
			mysqli_stmt_close($stmt);
			mysqli_close($conn);

	

	
?>
