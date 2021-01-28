<?php

header('Access-Control-Allow-Origin: *');

require("db_config.php");
global $db;

$err = false;

// API FUNCTIONS
/*if(isset($_GET["todo"]) and $_GET["todo"] != "retrieveAccount"){
	APIKeyCheck($db, $_GET["apiKey"]);
}*/

if(!isset($_GET["todo"])){
	$err = array('err' => '404;parameter_missing:[todo]');
	$output = json_encode($err);
	echo $output;
}
else if($_GET["todo"] == "test"){

}
/*else if($_GET["todo"] == "retrieveAccount"){
	if(isset($_GET["gsmExt"]) and isset($_GET["gsm"]) and $_GET["gsmExt"] != "" and $_GET["gsm"] != ""){
		// Check if number already in DB
		$statement = $db->prepare('SELECT USR_KEY FROM accounts WHERE GSM_EXT = :gsmExt and GSM = :gsm ');
		$statement->bindParam(':gsmExt', $_GET["gsmExt"], PDO::PARAM_STR);
		$statement->bindParam(':gsm', $_GET["gsm"], PDO::PARAM_INT);
		$statement->execute();
		$usrKey = $statement->fetchColumn();

		//User already exists
		if(isset($usrKey) and $usrKey != null){
			$statement = $db->prepare('SELECT ID, STATUS, CONNECTED FROM accounts WHERE USR_KEY = :usrKey ');
			$statement->bindParam(':usrKey', $usrKey, PDO::PARAM_STR);
			$statement->execute();
			$result = $statement->fetchAll();
			// 0 : Brand new. GSM not verified
			// 1 : GSM verified. Licence not uploaded
			// 2 : GSM verified. Licence uploaded. Licence not validated
			// 3 : GSM verified. Licence uploaded. Licence Rejected
			// 4 : Account activated
			// 5 : Account suspended
			// 6 : Account Blocked
			// 7 : Account Deleted		
			
			if(isset($result[0][STATUS]) and $result[0][STATUS] == 0){
				//Account still not validated
				//Send back SMS (conditions my apply)
				$validationCode = genValidationCode(6);
				$statement = $db->prepare('UPDATE accounts SET VALIDATION_CODE = :validationCode WHERE USR_KEY = :usrKey ');
				$statement->bindParam(':usrKey', $usrKey, PDO::PARAM_STR);
				$statement->bindParam(':validationCode', $validationCode, PDO::PARAM_INT);

				$statement->execute();

				$results = array('err' => 'none', 'usrKey' => $usrKey, 'accStatus' => $result[0][STATUS], 'connected' => $result[0][CONNECTED], 'instruction' => 'validationCode');
				$output = json_encode($results);
				echo $output;
			}
			else if(isset($result[0][STATUS]) and $result[0][STATUS] == 1){
				$results = array('err' => 'none', 'usrKey' => $usrKey, 'accStatus' => $result[0][STATUS], 'connected' => $result[0][CONNECTED], 'instruction' => 'companyIDValidation');
				$output = json_encode($results);
				echo $output;
			}
			else if(isset($result[0][STATUS]) and $result[0][STATUS] == 2){
				$results = array('err' => 'none', 'usrKey' => $usrKey, 'accStatus' => $result[0][STATUS], 'connected' => $result[0][CONNECTED], 'instruction' => 'licenceValidationPending');
				$output = json_encode($results);
				echo $output;
			}
			else if(isset($result[0][STATUS]) and $result[0][STATUS] == 3){
				$results = array('err' => 'none', 'usrKey' => $usrKey, 'accStatus' => $result[0][STATUS], 'connected' => $result[0][CONNECTED], 'instruction' => 'companyIDValidationR');
				$output = json_encode($results);
				echo $output;
			}
			else if(isset($result[0][STATUS]) and $result[0][STATUS] == 4){
				$statement = $db->prepare('SELECT ACC_ID, API_KEY FROM api_access WHERE ACC_ID = :accID');
				$statement->bindParam(':accID', $result[0][ID], PDO::PARAM_INT);
				$statement->execute();
				$result2 = $statement->fetchAll();

				if(isset($result2[0][API_KEY]) and $result2[0][API_KEY] != ""){
					if($result2[0][API_KEY] == $_GET["apiKey"]){	// Compte ouvert la dernière fois avec cet appareil
						if(isset($result[0][CONNECTED]) and $result[0][CONNECTED] == 1){	// Compte connecté
							$results = array('instruction' => 'login', 'usrKey' => $usrKey);
							$output = json_encode($results);
							echo $output;
						}
						else{
							// Se connecter par SMS display div : "connectionValidationCode"
							$results = array('instruction' => 'connectionValidationCode', 'usrKey' => $usrKey, 'connected' => $result[0][CONNECTED]);
							$output = json_encode($results);
							echo $output;
						}
					}
					else{	// Compte ouvert la dernière fois sur un autre appareil
						if(isset($result[0][CONNECTED]) and $result[0][CONNECTED] == 1){	// Compte connecté sur un autre appareil
							// Demander à le déconnecter
							$results = array('instruction' => 'disconnectionRequest', 'usrKey' => $usrKey, 'connected' => $result[0][CONNECTED]);
							$output = json_encode($results);
							echo $output;
						}
						else{
							// Compte déconnecté mais différent appareil
							$results = array('instruction' => 'changeDevice', 'usrKey' => $usrKey, 'connected' => $result[0][CONNECTED]);
							$output = json_encode($results);
							echo $output;
						}
					}
				}
				else{
					if($result2[0][API_KEY] == $_GET["apiKey"]){	// Compte ouvert la dernière fois avec cet appareil
						if(isset($result[0][CONNECTED]) and $result[0][CONNECTED] == 1){	// Compte connecté
							// Retour sur la page HOME.html
						}
						else{
							// Se connecter par SMS display div : "connectionValidationCode"
						}
					}
					else{
					
					}
				}
			}
			else if($result[0][STATUS] == 5){
				$results = array('err' => 'none', 'usrKey' => $usrKey, 'accStatus' => $result[0][STATUS], 'instruction' => 'accountIncidents');
				$output = json_encode($results);
				echo $output;
			}
			else if($result[0][STATUS] == 6 or $result[0][STATUS] == 7){
				$statement = $db->prepare('SELECT STATUS_NOTIF FROM accounts WHERE ID = :accID');
				$statement->bindParam(':accID', $result[0][ID], PDO::PARAM_INT);
				$statement->execute();
				$statusReason = $statement->fetchColumn();

				$results = array('err' => 'none', 'usrKey' => $usrKey, 'accStatus' => $result[0][STATUS], 'statusReason' => $statusReason, 'instruction' => 'accountIncidents');
				$output = json_encode($results);
				echo $output;
			}
			else if($result[0][STATUS] == 8){
				$results = array('err' => 'none', 'usrKey' => $usrKey, 'accStatus' => $result[0][STATUS], 'instruction' => 'codeValidation');
				$output = json_encode($results);
				echo $output;
			}
		}
		//User does not exist
		else{
			createAccount();
		}		
	}
	else{
		$err = array('err' => 'parameter_missing_or_empty:[gsm]');
		$output = json_encode($err);
		echo $output;
	}
}*/
else if($_GET["todo"] == "numValidateCode"){
	if(isset($_GET["vCode"]) and isset($_GET["usrKey"]) and $_GET["vCode"] != "" and $_GET["usrKey"] != ""){
		// Check if number already in DB
		$statement = $db->prepare('SELECT VALIDATION_CODE FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$validationCode = $statement->fetchColumn();

		if(isset($validationCode) and $validationCode != null){
			if($validationCode == $_GET["vCode"]){			
				$statement = $db->prepare('UPDATE accounts SET VALIDATION_CODE=null, STATUS=1 WHERE USR_KEY = :usrKey');
				$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
				$statement->execute();
				$results = array('notif' => 'emailValidated', 'err' => 'none', 'accStatus' => '1', 'instruction' => 'companyIDValidation');
				$output = json_encode($results);
				echo $output;
			}
			else{
				$err = array('notif' => 'none', 'err' => 'argumentNotMatch:[vCode]');
				$output = json_encode($err);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'accountNotFound:[usrKey]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey;vCode]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "licenceUploaded"){
	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Check if user exists in DB
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$accID = $statement->fetchColumn();

		if(isset($accID) and $accID != null){	
			$statement = $db->prepare('SELECT FILE_REF FROM company_id_validation WHERE ACC_ID = :accID');
			$statement->bindParam(':accID', $accID, PDO::PARAM_STR);
			$statement->execute();
			$fileRef = $statement->fetchColumn();
			$filePath = "usrUploads/" . $fileRef . ".jpg";

			if(file_exists($filePath)){
				$statement = $db->prepare('UPDATE accounts SET STATUS=2 WHERE USR_KEY = :usrKey'); // 2 : GSM verified. Licence uploaded. Licence not validated
				$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
				$statement->execute();
				$results = array('notif' => 'licenceUploaded', 'err' => 'none', 'accStatus' => '2', 'instruction' => 'licenceValidationPending');
				$output = json_encode($results);
				echo $output;
			}
			else{
				$results = array('notif' => 'none', 'err' => 'none', 'licenceFileNotUploaded' => '1');
				$output = json_encode($results);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'accountNotFound:[usrKey]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "checkAccStatus"){
	if (isset($_GET["gsmExt"]) and isset($_GET["gsm"]) and isset($_GET["email"]) and $_GET["gsmExt"] != "" and $_GET["gsm"] != "" and $_GET["email"] != "" and $_GET["gsmExt"] != "undefined" and $_GET["gsm"] != "undefined" and $_GET["email"] != "undefined"){
		// Check Account Existance
		$statement = $db->prepare('SELECT USR_KEY FROM accounts WHERE GSM_EXT = :gsmExt and GSM = :gsm and EMAIL = :email'); // <-- CREADE DUPLICATES IF ALL INFO NOT THE SAME
		//$statement = $db->prepare('SELECT USR_KEY FROM accounts WHERE EMAIL = :email'); // <-- Retrieve account just with email and avoid duplicates
		$statement->bindParam(':gsmExt', $_GET["gsmExt"], PDO::PARAM_STR);
		$statement->bindParam(':gsm', $_GET["gsm"], PDO::PARAM_INT);
		$statement->bindParam(':email', $_GET["email"], PDO::PARAM_STR);
		$statement->execute();
		$usrKey = $statement->fetchColumn();

		if(isset($usrKey) and $usrKey != ""){
			checkAccStatus($db, $usrKey);
		}
		else{
			createAccount();
		}
	}
	else if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		checkAccStatus($db, $_GET["usrKey"]);
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameters_missing_or_empty:[usrKey,gsmExt,gsm,email]', 'instruction' => 'splashScreenOff');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "connectionValidation"){
	if(isset($_GET["vCode"]) and isset($_GET["usrKey"]) and $_GET["vCode"] != "" and $_GET["usrKey"] != ""){
		// Check if number already in DB
		$statement = $db->prepare('SELECT VALIDATION_CODE FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$validationCode = $statement->fetchColumn();

		if(isset($validationCode) and $validationCode != null){
			if($validationCode == $_GET["vCode"]){			
				$statement = $db->prepare('UPDATE accounts SET VALIDATION_CODE=null, CONNECTED=1 WHERE USR_KEY = :usrKey');
				$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
				$statement->execute();
				$results = array('notif' => 'connectionValidated', 'err' => 'none', 'connected' => '1', 'instruction' => 'login');
				$output = json_encode($results);
				echo $output;
			}
			else{
				$err = array('notif' => 'none', 'err' => 'argumentNotMatch:[vCode]');
				$output = json_encode($err);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'accountNotFound:[usrKey]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey;vCode]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "disconnet"){
	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Check if user exists in DB
		$statement = $db->prepare('SELECT ID, STATUS FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$results = $statement->fetchAll();

		if(isset($results) and $results != null){	

			if($results[0][STATUS] == 4){
				$statement = $db->prepare('UPDATE accounts SET CONNECTED = 0 WHERE ID = :accID');
				$statement->bindParam(':accID', $results[0][ID], PDO::PARAM_INT);
				$statement->execute();

				$results = array('notif' => 'accountDisconnected', 'err' => 'none', 'accStatus' => $results[0][STATUS], 'instruction' => 'logout');
				$output = json_encode($results);
				echo $output;
			}
			else{
				$results = array('notif' => 'none', 'err' => 'noPermission:[accStatus]', 'accStatus' => $results[0][STATUS], 'instruction' => 'logout');
				$output = json_encode($results);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'accountNotFound:[usrKey]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "connectNewDevice"){
	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){		
		$statement = $db->prepare('SELECT ID, EMAIL FROM accounts WHERE USR_KEY = :usrKey ');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$result = $statement->fetchAll();

		if(isset($result) and $result != null){					
			$validationCode = genValidationCode(6);
			$statement = $db->prepare('UPDATE accounts SET VALIDATION_CODE = :validationCode WHERE ID = :usrID ');
			$statement->bindParam(':usrID', $result[0][ID], PDO::PARAM_INT);
			$statement->bindParam(':validationCode', $validationCode, PDO::PARAM_INT);

			$statement->execute();

			sendEmail(2, $result[0][EMAIL], $validationCode);

			$results = array('notif' => 'codeSent', 'err' => 'none', 'email' => $result[0][EMAIL], 'usrKey' => $_GET["usrKey"], 'instruction' => 'newDeviceValidation');
			$output = json_encode($results);
			echo $output;
		}
		else{
			$err = array('notif' => 'none', 'err' => 'accountNotFound:[usrKey]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "newDeviceValidation"){
	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){			
		$statement = $db->prepare('SELECT ID, EMAIL FROM accounts WHERE USR_KEY = :usrKey ');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$result = $statement->fetchAll();

		if(isset($result) and $result != null){
			if(isset($_GET["apiKey"]) and $_GET["apiKey"] != ""){		
				$statement = $db->prepare("UPDATE api_access SET API_KEY = :apiKey WHERE ACC_ID = :accID");
				$statement->bindParam(':accID', $result[0][ID], PDO::PARAM_INT);
				$statement->bindParam(':apiKey', $_GET["apiKey"], PDO::PARAM_STR);
				$statement->execute();

				$statement = $db->prepare('UPDATE accounts SET VALIDATION_CODE=null, CONNECTED=1 WHERE ID = :usrID');
				$statement->bindParam(':usrID', $result[0][ID], PDO::PARAM_INT);
				$statement->execute();

				sendEmail(3, $result[0][EMAIL], false);

				$results = array('notif' => 'newDeviceValidated', 'err' => 'none', 'usrKey' => $_GET["usrKey"], 'instruction' => 'login');
				$output = json_encode($results);
				echo $output;
			}
			else{
				$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[apiKey]');
				$output = json_encode($err);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'accountNotFound:[usrKey]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "sendEmail"){
	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){	
		$statement = $db->prepare('SELECT EMAIL, VALIDATION_CODE FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$result = $statement->fetchAll();

		if(isset($_GET["eType"]) and $_GET["eType"] != ""){

			sendEmail($_GET["eType"], $result[0][EMAIL], $result[0][VALIDATION_CODE]);

			$err = array('notif' => 'emailSent', 'err' => 'none');
			$output = json_encode($err);
			echo $output;
		}
		else{
			$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[eType]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{		
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "friendInvite"){ // NEED FRIEND ACC ID
	if(isset($_GET["id"]) and isset($_GET["usrKey"]) and $_GET["id"] != "" and $_GET["usrKey"] != ""){
		// Check if number already in DB
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){			
			$prereq = $db->prepare('SELECT ID FROM accounts_friendslist WHERE ACC1_ID = :usrID AND ACC2_ID = :accID OR ACC1_ID = :accID AND ACC2_ID = :usrID');
			$prereq->bindParam(':usrID', $usrID, PDO::PARAM_INT);
			$prereq->bindParam(':accID', $_GET["id"], PDO::PARAM_INT);
			$prereq->execute();
			$friendship = $prereq->fetchColumn();

			if(!$friendship){
				$statement = $db->prepare("INSERT INTO accounts_friendslist (ID, ACC1_ID, ACC2_ID) VALUES (null, :usrID, :friendID)");
				$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
				$statement->bindParam(':friendID', $_GET["id"], PDO::PARAM_INT);
				$statement->execute();

				$results = array('notif' => 'friendInvitationSent', 'err' => 'none', 'instruction' => 'resetFriendSearch');
				$output = json_encode($results);
				echo $output;
			}
			else{
				$results = array('notif' => 'none', 'err' => 'friendshipAlreadyExists', 'instruction' => 'resetFriendSearch');
				$output = json_encode($results);
				echo $output;
			}
			
		}
		else{
			$err = array('notif' => 'none', 'err' => 'accountNotFound:[usrKey]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey;id]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "friendAccept"){ // NEED REQUEST ID
	if(isset($_GET["id"]) and isset($_GET["usrKey"]) and $_GET["id"] != "" and $_GET["usrKey"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		// Check if request is for this user
		$statement = $db->prepare('SELECT ACC1_ID FROM accounts_friendslist WHERE ID = :reqID AND ACC2_ID = :usrID');
		$statement->bindParam(':reqID', $_GET["id"], PDO::PARAM_INT);
		$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
		$statement->execute();
		$friendRequestSenderID = $statement->fetchColumn();

		if(isset($friendRequestSenderID) and $friendRequestSenderID != null){			
			$statement = $db->prepare("UPDATE accounts_friendslist SET STATUS = 1, EVENT_DATE = CURRENT_TIMESTAMP WHERE ID = :reqID ");
			$statement->bindParam(':reqID', $_GET["id"], PDO::PARAM_INT);
			$statement->execute();

			$results = array('notif' => 'friendInvitationAccepted', 'err' => 'none', 'instruction' => 'refreshFriendsList');
			$output = json_encode($results);
			echo $output;
		}
		else{
			$err = array('notif' => 'none', 'err' => 'requestNotFound:[usrID,friendID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey;id]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "friendRefuse"){ // NEED REQUEST ID
	if(isset($_GET["id"]) and isset($_GET["usrKey"]) and $_GET["id"] != "" and $_GET["usrKey"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		// Check if request is for this user
		$statement = $db->prepare('SELECT ACC1_ID FROM accounts_friendslist WHERE ID = :reqID AND ACC2_ID = :usrID');
		$statement->bindParam(':reqID', $_GET["id"], PDO::PARAM_INT);
		$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
		$statement->execute();
		$friendRequestSenderID = $statement->fetchColumn();

		if(isset($friendRequestSenderID) and $friendRequestSenderID != null){			
			$statement = $db->prepare("DELETE FROM accounts_friendslist WHERE ID = :reqID ");
			$statement->bindParam(':reqID', $_GET["id"], PDO::PARAM_INT);
			$statement->execute();

			$results = array('notif' => 'friendInvitationRefused', 'err' => 'none', 'instruction' => 'refreshFriendsInvitationsList');
			$output = json_encode($results);
			echo $output;
		}
		else{
			$err = array('notif' => 'none', 'err' => 'requestNotFound:[usrID,friendID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey;id]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "friendRemove"){ // NEED FRIEND ACC ID
	if(isset($_GET["id"]) and isset($_GET["usrKey"]) and $_GET["id"] != "" and $_GET["usrKey"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		// Check if request is for this user
		$statement = $db->prepare('SELECT ID FROM accounts_friendslist WHERE ACC1_ID = :friendID AND ACC2_ID = :usrID OR  ACC1_ID = :usrID AND ACC2_ID = :friendID');
		$statement->bindParam(':friendID', $_GET["id"], PDO::PARAM_INT);
		$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
		$statement->execute();
		$friendshipID = $statement->fetchColumn();

		if(isset($friendshipID) and $friendshipID != null){			
			$statement = $db->prepare("DELETE FROM accounts_friendslist WHERE ID = :friendshiID ");
			$statement->bindParam(':friendshiID', $friendshipID, PDO::PARAM_INT);
			$statement->execute();

			$results = array('notif' => 'friendRemoved', 'err' => 'none', 'instruction' => 'refreshFriendsList');
			$output = json_encode($results);
			echo $output;
		}
		else{
			$err = array('notif' => 'none', 'err' => 'requestNotFound:[usrID,friendID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey;id]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "friendsList"){
	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){			
			$statement = $db->prepare("SELECT BLOCKED_ID FROM accounts_blacklist WHERE ACC_ID = :usrID ");
			$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
			$statement->execute();
			$blacklist = $statement->fetchColumn();
			$blacklistArray = explode(";", $blacklist);

			$toPrint = "";

			$prereq = $db->prepare('SELECT ACC1_ID, ACC2_ID FROM accounts_friendslist WHERE ACC1_ID = :usrID AND STATUS = 1 OR ACC2_ID = :usrID AND STATUS = 1');
			$prereq->bindParam(':usrID', $usrID, PDO::PARAM_INT);
			$prereq->execute();

			while ($predata = $prereq->fetch())
			{
				$acc1ID = $predata["ACC1_ID"];
				$acc2ID = $predata["ACC2_ID"];

				if($acc1ID == $usrID){
					$friendID = $acc2ID;
				}
				else{
					$friendID = $acc1ID;
				}

				if(!in_array($friendID, $blacklistArray)){	// Display only if not blacklisted
					$statement = $db->prepare('SELECT NAME, SURNAME FROM accounts_data WHERE ACC_ID = :friendID');
					$statement->bindParam(':friendID', $friendID, PDO::PARAM_INT);
					$statement->execute();
					$result = $statement->fetchAll();

					$toPrint .= '<div id="FID'.$friendID.'" class="listLine">
									<span class="usrListName">'.$result[0][NAME]." ".$result[0][SURNAME].'</span>
									<img src="images/icons/remove.png" class="listIcon" onclick="friends(\'remove\', \''.$friendID.'\'); $(\'#FID'.$friendID.'\').slideUp();"/>
								</div>';
				}
				else{	// If blacklist, delete request or friendship
					$statement = $db->prepare("DELETE FROM accounts_friendslist WHERE ACC1_ID = :friendID AND ACC2_ID = :usrID OR  ACC1_ID = :usrID AND ACC2_ID = :friendID");
					$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
					$statement->bindParam(':friendID', $friendID, PDO::PARAM_INT);
					$statement->execute();
				}
			}
			echo $toPrint;
		}
		else{
			$err = array('notif' => 'none', 'err' => 'requestNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "checkFriendsRequests"){
	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){			
			$statement = $db->prepare("SELECT BLOCKED_ID FROM accounts_blacklist WHERE ACC_ID = :usrID ");
			$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
			$statement->execute();
			$blacklist = $statement->fetchColumn();
			if(isset($blacklist) and $blacklist != NULL){
				$blockedIDs = str_replace(";", ",", $blacklist);
			}
			else{
				$blockedIDs = 0;
			}

			$prereq = $db->prepare('SELECT COUNT(accounts_friendslist.ID) AS TOTAL_REQUESTS FROM accounts_friendslist WHERE ACC2_ID = :usrID AND ACC1_ID NOT IN (:blockedIDs) AND STATUS = 0');
			$prereq->bindParam(':usrID', $usrID, PDO::PARAM_INT);
			$prereq->bindParam(':blockedIDs', $blockedIDs, PDO::PARAM_STR);
			$prereq->execute();			
			$totalRequests = $prereq->fetchColumn();
			echo $totalRequests;
		}
		else{
			echo '-1';
		}
	}
	else{
		echo '-1';
	}
}
else if($_GET["todo"] == "friendsInvitationsList"){
	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){			
			$statement = $db->prepare("SELECT BLOCKED_ID FROM accounts_blacklist WHERE ACC_ID = :usrID ");
			$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
			$statement->execute();
			$blacklist = $statement->fetchColumn();
			$blacklistArray = explode(";", $blacklist);
			
			$totalInvit = 0;
			$toPrint = '';

			$prereq = $db->prepare('SELECT accounts_friendslist.ID as ID, ACC1_ID, NAME, SURNAME FROM accounts_friendslist INNER JOIN accounts_data ON accounts_data.ACC_ID = accounts_friendslist.ACC1_ID WHERE ACC2_ID = :usrID AND STATUS = 0');
			$prereq->bindParam(':usrID', $usrID, PDO::PARAM_INT);
			$prereq->execute();
			while ($predata = $prereq->fetch())
			{
				$requestID = $predata["ID"];
				$friendID = $predata["ACC1_ID"];
				$fullName = $predata["NAME"]." ".$predata["SURNAME"];

				if(!in_array($friendID, $blacklistArray)){	// Display only if not blacklisted
					$toPrint .= '<div id="IID'.$requestID.'" class="listLine">
									<span class="usrListName">'.$fullName.'</span>
									<span class="friendsOptns">
										<img src="images/icons/accept.png" class="listIcon" onclick="friends(\'accept\', '.$requestID.'); $(\'#IID'.$requestID.'\').slideUp(); document.getElementById(\'totalInvitations\').value -= 1; $(\'#totalInvitations\').change();"/>
										<span style="display: block; width: 10px;"></span>
										<img src="images/icons/refuse.png" class="listIcon" onclick="friends(\'refuse\', '.$requestID.'); $(\'#IID'.$requestID.'\').slideUp(); document.getElementById(\'totalInvitations\').value -= 1; $(\'#totalInvitations\').change();"/>
									</span>
								</div>';
								$totalInvit++;
				}
				else{	// If blacklist, delete request or friendship
					$statement = $db->prepare("DELETE FROM accounts_friendslist WHERE ACC1_ID = :friendID AND ACC2_ID = :usrID");
					$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
					$statement->bindParam(':friendID', $friendID, PDO::PARAM_INT);
					$statement->execute();
				}
			}
			if($toPrint != ""){
				$toPrint .= '<input type="hidden" id="totalInvitations" value="'.$totalInvit.'" onchange="if(this.value == 0){$(\'#friendsInvitations\').slideUp();}"/>';
			}
			echo $toPrint;
		}
		else{
			$err = array('notif' => 'none', 'err' => 'requestNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "blackList"){
	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){			
			$statement = $db->prepare("SELECT BLOCKED_ID FROM accounts_blacklist WHERE ACC_ID = :usrID ");
			$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
			$statement->execute();
			$blacklist = $statement->fetchColumn();
			$blacklistArray = explode(";", $blacklist);

			$toPrint = "";

			if($blacklist != ""){
				for ($i = 0; $i < count($blacklistArray); $i++){
					$statement = $db->prepare('SELECT NAME, SURNAME FROM accounts_data WHERE ACC_ID = :accID');
					$statement->bindParam(':accID', $blacklistArray[$i], PDO::PARAM_INT);
					$statement->execute();
					$result = $statement->fetchAll();

					$toPrint .= '<div id="BID'.$blacklistArray[$i].'" class="listLine">
									<span class="usrListName">'.$result[0][NAME]." ".$result[0][SURNAME].'</span>
									<img src="images/icons/remove.png" class="listIcon" onclick="blacklist(\'remove\', \''.$blacklistArray[$i].'\'); $(\'#BID'.$blacklistArray[$i].'\').slideUp();"/>
								</div>';
				}
			}
			echo $toPrint;
		}
		else{
			$err = array('notif' => 'none', 'err' => 'userNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "blacklistUser"){
	if(isset($_GET["usrKey"]) and isset($_GET["id"]) and $_GET["usrKey"] != "" and $_GET["id"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){			
			$statement = $db->prepare("SELECT BLOCKED_ID FROM accounts_blacklist WHERE ACC_ID = :usrID ");
			$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
			$statement->execute();
			$blacklist = $statement->fetchColumn();
			
			// add user to blacklist
			if($blacklist != NULL){
				$blacklist .= ";".$_GET["id"];
			}
			else{
				$blacklist = $_GET["id"];
			}

			// convert blacklist to array to remove possible duplicates
			$blacklistArray = array_unique(explode(";", $blacklist));

			//recompose array in text format
			for($i = 0; $i < count($blacklistArray); $i++){
				if($i == 0){
					$blacklist = $blacklistArray[$i];
				}
				else{
					$blacklist .= ";".$blacklistArray[$i];
				}
			}

			$statement = $db->prepare("UPDATE accounts_blacklist SET BLOCKED_ID = :blacklist WHERE ACC_ID = :usrID ");
			$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
			$statement->bindParam(':blacklist', $blacklist, PDO::PARAM_STR);
			$statement->execute();	

			$prereq = $db->prepare('SELECT ACC1_ID, ACC2_ID FROM accounts_friendslist WHERE ACC1_ID = :usrID AND ACC2_ID = :targetID OR ACC1_ID = :targetID AND ACC2_ID = :usrID');
			$prereq->bindParam(':usrID', $usrID, PDO::PARAM_INT);
			$prereq->bindParam(':targetID', $_GET["id"], PDO::PARAM_INT);
			$prereq->execute();
			$resuls = $prereq->fetchAll();

			if($resuls[0][ACC1_ID] != ""){	// If in friends list, delete request or friendship
				$statement = $db->prepare("DELETE FROM accounts_friendslist WHERE ACC1_ID = :friendID AND ACC2_ID = :usrID OR ACC1_ID = :usrID AND ACC2_ID = :friendID");
				$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
				$statement->bindParam(':friendID', $_GET["id"], PDO::PARAM_INT);
				$statement->execute();
			}

			$err = array('notif' => 'userBlacklisted', 'err' => 'none', 'instruction' => 'refreshBlackList');
			$output = json_encode($err);
			echo $output;
		}
		else{
			$err = array('notif' => 'none', 'err' => 'userNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey,id]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "unblacklistUser"){
	if(isset($_GET["usrKey"]) and isset($_GET["id"]) and $_GET["usrKey"] != "" and $_GET["id"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){			
			$statement = $db->prepare("SELECT BLOCKED_ID FROM accounts_blacklist WHERE ACC_ID = :usrID ");
			$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
			$statement->execute();
			$blacklist = $statement->fetchColumn();			
			$blacklistArray = explode(";", $blacklist);

			if (($key = array_search($_GET["id"], $blacklistArray)) !== false) {
				array_splice($blacklistArray, $key, 1);
			}

			
			if(count($blacklistArray) > 0){
				$blacklist = "";
				$sql = "UPDATE accounts_blacklist SET BLOCKED_ID = :blacklist WHERE ACC_ID = :usrID";
				for($i = 0; $i < count($blacklistArray); $i++){
					if($i == 0){
						$blacklist = $blacklistArray[$i];
					}
					else{
						$blacklist .= ";".$blacklistArray[$i];
					}
				}
			}
			else{
				$sql = "UPDATE accounts_blacklist SET BLOCKED_ID = NULL WHERE ACC_ID = :usrID";
			}

			$statement = $db->prepare($sql);
			$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
			if(count($blacklistArray) > 0){
				$statement->bindParam(':blacklist', $blacklist, PDO::PARAM_STR);
			}
			$statement->execute();

			$err = array('notif' => 'userUnblacklisted', 'err' => count($blacklistArray), 'instruction' => 'refreshBlackList');
			$output = json_encode($err);
			echo $output;
		}
		else{
			$err = array('notif' => 'none', 'err' => 'userNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey,id]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "searchUsers"){
	if(isset($_GET["searchParam"]) and isset($_GET["searchType"]) and isset($_GET["column"]) and isset($_GET["usrKey"]) and $_GET["searchParam"] != "" and $_GET["searchType"] != "" and $_GET["column"] != "" and $_GET["usrKey"] != ""){		
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){
			if($_GET["column"] == "NAME"){
				$sql = 'SELECT accounts_data.ACC_ID, NAME, SURNAME, APPEAR_IN_SEARCH FROM accounts_data INNER JOIN accounts_settings ON accounts_settings.ACC_ID = accounts_data.ACC_ID WHERE NAME LIKE :searchParam OR SURNAME LIKE :searchParam ORDER BY :orderBy';
			}
			else if($_GET["column"] == "ACC_ID"){
				$sql = 'SELECT accounts_data.ACC_ID, NAME, SURNAME, APPEAR_IN_SEARCH FROM accounts_data INNER JOIN accounts_settings ON accounts_settings.ACC_ID = accounts_data.ACC_ID WHERE ACC_ID LIKE :searchParam ORDER BY :orderBy';
			}

			if(isset($_GET["orderBy"]) and $_GET["orderBy"] != ""){
				$orderBy = $_GET["orderBy"];
			}
			else{
				$orderBy = $_GET["column"]." ASC";
			}
			$searchParam = "%".$_GET["searchParam"]."%";

			$toPrint = "";
			$statement = $db->prepare($sql);
			$statement->bindParam(':column', $_GET["column"], PDO::PARAM_STR);
			$statement->bindParam(':searchParam', $searchParam, PDO::PARAM_STR);
			$statement->bindParam(':orderBy', $orderBy, PDO::PARAM_STR);
			$statement->execute();

			while ($predata = $statement->fetch()){
				$accID = $predata["ACC_ID"];
				$accName = $predata["NAME"];
				$accSurname = $predata["SURNAME"];
				$appearInSearch = $predata["APPEAR_IN_SEARCH"];

				$statement = $db->prepare("SELECT BLOCKED_ID FROM accounts_blacklist WHERE ACC_ID = :usrID ");
				$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
				$statement->execute();
				$blacklist = $statement->fetchColumn();
				$blacklistArray = explode(";", $blacklist);
				
				if(!in_array($accID, $blacklistArray)){	// Display only if not blacklisted
					$statement = $db->prepare('SELECT STATUS FROM accounts_friendslist WHERE ACC1_ID = :usrID AND ACC2_ID = :accID OR ACC1_ID = :accID AND ACC2_ID = :usrID');
					$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
					$statement->bindParam(':accID', $accID, PDO::PARAM_INT);
					$statement->execute();
					$friendshipStatus = $statement->fetchColumn();

					if($_GET["searchType"] == 1){ //Friends Search
						if($appearInSearch == "1"){
							if($friendshipStatus != ""){
								$onclick = "remove";
							}
							else{
								$onclick = "invite";
							}
							$toPrint .= '<div id="FID'.$accID.'" class="listLine">
											<span class="usrListName">'.$accName.' '.$accSurname.'</span>
											<img src="images/icons/'.$onclick.'.png" class="listIcon" onclick="friends(\''.$onclick.'\', \''.$accID.'\'); $(\'#FID'.$accID.'\').slideUp();"/>
										</div>';
						}						
					}
					else if($_GET["searchType"] == 0){ //Blacklist Search
						$toPrint .= '<div id="TBID'.$accID.'" class="listLine">
										<span class="usrListName">'.$accName.' '.$accSurname.'</span>
										<img src="images/icons/add.png" class="listIcon" onclick="blacklist(\'add\', \''.$accID.'\'); $(\'#TBID'.$accID.'\').slideUp();"/>
									</div>';
					}					
				}
				else{	// If blacklisted and searching for friends, next result
					if($_GET["searchType"] == 0){ //Blacklisted Search
						$toPrint .= '<div id="BID'.$accID.'" class="listLine">
										<span class="usrListName">'.$accName.' '.$accSurname.'</span>
										<img src="images/icons/remove.png" class="listIcon" onclick="blacklist(\'remove\', \''.$accID.'\'); $(\'#BID'.$accID.'\').slideUp();"/>
									</div>';
					}
					else{
						//break;
					}
				}
			}
			echo $toPrint;
		}
	}
}
else if($_GET["todo"] == "updateProfile"){
	
	if(isset($_GET["usrKey"]) and isset($_GET["column"]) and isset($_GET["content"]) and $_GET["usrKey"] != "" and $_GET["column"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){	
			if($_GET["column"] == "username"){
				$statement = $db->prepare('UPDATE accounts_data SET USERNAME = :username WHERE ACC_ID = :usrID');
				$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
				$statement->bindParam(':username', $_GET["content"], PDO::PARAM_STR);
				$statement->execute();

				$validUsername = preg_replace('/[\p{P}\p{Zs}]+/u', "", $_GET["content"]);
				if($validUsername == ""){
					$statement = $db->prepare('UPDATE accounts_settings SET USE_USRNAME = 0 WHERE ACC_ID = :usrID');
					$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
					$statement->execute();
				}

				$err = array('notif' => 'profileUpdated[username]', 'err' => 'none');
				$output = json_encode($err);
				echo $output;
			}
			else if($_GET["column"] == "description"){
				$statement = $db->prepare('UPDATE accounts_data SET DESCRIPTION = :description WHERE ACC_ID = :usrID');
				$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
				$statement->bindParam(':description', $_GET["content"], PDO::PARAM_STR);
				$statement->execute();

				$err = array('notif' => 'profileUpdated[description]', 'err' => 'none');
				$output = json_encode($err);
				echo $output;
			}
			else if($_GET["column"] == "coordinates"){
				$statement = $db->prepare('UPDATE accounts_data SET LOCATION_COORDS = :coordinates WHERE ACC_ID = :usrID');
				$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
				$statement->bindParam(':coordinates', $_GET["content"], PDO::PARAM_STR);
				$statement->execute();

				$err = array('notif' => 'profileUpdated[coordinates]', 'err' => 'none');
				$output = json_encode($err);
				echo $output;
			}
			else if($_GET["column"] == "imgurl"){
				$statement = $db->prepare('UPDATE accounts_data SET IMGURL = null WHERE ACC_ID = :usrID');
				$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
				$statement->execute();

				$err = array('notif' => 'profileUpdated[imgurl]', 'err' => 'none');
				$output = json_encode($err);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'userNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey,column,content]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "loadProfile"){
	
	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){	
			$statement = $db->prepare('SELECT GSM_EXT, GSM, EMAIL, USERNAME, accounts_data.NAME, SURNAME, GENDER, COMPANY_ID, companies.NAME AS CNAME, companies.IMG, DESCRIPTION, IMGURL, LOCATION_COORDS, CURRENT_CITY FROM accounts_data INNER JOIN companies ON accounts_data.COMPANY_ID = companies.ID INNER JOIN accounts ON accounts.ID = accounts_data.ACC_ID WHERE ACC_ID = :usrID');
			$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
			$statement->execute();
			$result = $statement->fetchAll();

			/*$err = array('notif' => 'profileLoaded', 'err' => 'none');
			$output = json_encode($err);*/
			$output = json_encode($result[0]);
			echo $output;
		}
		else{
			$err = array('notif' => 'none', 'err' => 'userNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey,column,content]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "loadOtherProfile"){
	
	if(isset($_GET["usrKey"]) and isset($_GET["targetID"]) and $_GET["usrKey"] != "" and $_GET["targetID"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){	
			$statement = $db->prepare('SELECT accounts_data.ACC_ID, GSM_EXT, GSM, EMAIL, USERNAME, accounts_data.NAME, SURNAME, GENDER, COMPANY_ID, companies.NAME AS CNAME, companies.IMG, DESCRIPTION, IMGURL, LOCATION_COORDS, CURRENT_CITY, SHARE_LOC, SHARE_GSM, SHARE_EMAIL, SHARE_COMPANY, USE_USRNAME FROM accounts_data INNER JOIN companies ON accounts_data.COMPANY_ID = companies.ID INNER JOIN accounts ON accounts.ID = accounts_data.ACC_ID INNER JOIN accounts_settings ON accounts_settings.ACC_ID = accounts_data.ACC_ID WHERE accounts_data.ACC_ID = :targetID');
			$statement->bindParam(':targetID', $_GET["targetID"], PDO::PARAM_INT);
			$statement->execute();
			$result = $statement->fetchAll();

			/*$err = array('notif' => 'profileLoaded', 'err' => 'none');
			$output = json_encode($err);*/
			$output = json_encode($result[0]);
			echo $output;
		}
		else{
			$err = array('notif' => 'none', 'err' => 'userNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey,targetID]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "joinLobby"){

	if(isset($_GET["usrKey"]) and isset($_GET["lobbyRef"]) and isset($_GET["lobbyType"]) and $_GET["usrKey"] != "" and $_GET["lobbyRef"] != "" and $_GET["lobbyType"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){				
			$statement = $db->prepare('SELECT ID FROM lobbies WHERE REF = :lobbyRef');
			$statement->bindParam(':lobbyRef', $_GET["lobbyRef"], PDO::PARAM_STR);
			$statement->execute();
			$lobbyID = $statement->fetchColumn();

			// EXIT any other lobby if user already in
			$statement = $db->prepare("DELETE FROM lobbies_users WHERE ACC_ID = :usrID ");
			$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
			$statement->execute();
			
			if(isset($lobbyID) and $lobbyID != null){	// Lobby already exists, add user to lobby
				$statement = $db->prepare('INSERT INTO lobbies_users (ID, ACC_ID, LOBBY_ID, LOBBY_TYPE, ENTRY_DATE) VALUES (null, :usrID, :lobbyID, :lobbyType, "'.date('Y-m-d H:i:s').'")');
				$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
				$statement->bindParam(':lobbyID', $lobbyID, PDO::PARAM_INT);
				$statement->bindParam(':lobbyType', $_GET["lobbyType"], PDO::PARAM_INT);
				$statement->execute();
					
				$err = array('notif' => 'lobbyJoined['.$lobbyID.']', 'err' => 'none', 'instruction' => 'loadLobby');
				$output = json_encode($err);
				echo $output;
			}
			else{ // Lobby doesn't exist. Create it and add user to lobby			
				if($_GET["lobbyType"] == 1){	// Lobby de type localisation
					if(isset($_GET["lobbyCity"]) and isset($_GET["lobbyCountry"]) and $_GET["lobbyCity"] != "" and $_GET["lobbyCountry"] != ""){
						$statement = $db->prepare('INSERT INTO lobbies (ID, REF, TYPE, CITY, COUNTRY) VALUES (null, :lobbyRef, :lobbyType, :lobbyCity, :lobbyCountry)');
						$statement->bindParam(':lobbyRef', $_GET["lobbyRef"], PDO::PARAM_INT);
						$statement->bindParam(':lobbyType', $_GET["lobbyType"], PDO::PARAM_INT);
						$statement->bindParam(':lobbyCity', $_GET["lobbyCity"], PDO::PARAM_STR);
						$statement->bindParam(':lobbyCountry', $_GET["lobbyCountry"], PDO::PARAM_STR);
						$statement->execute();
	
						$newLobbyID = $db->lastInsertId();

						$statement = $db->prepare('INSERT INTO lobbies_users (ID, ACC_ID, LOBBY_ID, LOBBY_TYPE, ENTRY_DATE) VALUES (null, :usrID, :lobbyID, :lobbyType, "'.date('Y-m-d H:i:s').'")');
						$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
						$statement->bindParam(':lobbyID', $newLobbyID, PDO::PARAM_INT);
						$statement->bindParam(':lobbyType', $_GET["lobbyType"], PDO::PARAM_INT);
						$statement->execute();
					
						$err = array('notif' => 'lobbyCreatedAndJoined['.$newLobbyID.']', 'err' => 'none', 'instruction' => 'loadLobby');
						$output = json_encode($err);
						echo $output;
					}
					else{
						$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[lobbyType,lobbyCountry]');
						$output = json_encode($err);
						echo $output;
					}
				}
				else{
					$err = array('notif' => 'none', 'err' => 'wrongLobbyType:[lobbyCity]');
					$output = json_encode($err);
					echo $output;
				}
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'userNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey,column,content]');
		$output = json_encode($err);
		echo $output;
	}					
}
else if($_GET["todo"] == "exitLobby"){
	if(isset($_GET["usrKey"]) and isset($_GET["lobbyRef"]) and $_GET["usrKey"] != "" and $_GET["lobbyRef"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		$statement = $db->prepare('SELECT ID FROM lobbies WHERE REF = :lobbyRef');
		$statement->bindParam(':lobbyRef', $_GET["lobbyRef"], PDO::PARAM_STR);
		$statement->execute();
		$lobbyID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){		
			if(isset($lobbyID) and $lobbyID != null){		
				$statement = $db->prepare("DELETE FROM lobbies_users WHERE ACC_ID = :usrID AND LOBBY_ID = :lobbyID");
				$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
				$statement->bindParam(':lobbyID', $lobbyID, PDO::PARAM_INT);
				$statement->execute();

				$err = array('notif' => 'successfullyExitedLobby', 'err' => 'none', 'instruction' => 'exitLobby');
				$output = json_encode($err);
				echo $output;
			}
			else{
				$err = array('notif' => 'none', 'err' => 'lobbyNotFound:[lobbyRef]');
				$output = json_encode($err);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'userNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey,lobbyID]');
		$output = json_encode($err);
		echo $output;
	}		
}
else if($_GET["todo"] == "loadLobby"){
	$messagesQttToLoad = 20;

	if(isset($_GET["usrKey"]) and isset($_GET["lobbyRef"]) and $_GET["usrKey"] != "" and $_GET["lobbyRef"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID, BLOCKED_ID FROM accounts INNER JOIN accounts_blacklist ON accounts.ID = accounts_blacklist.ACC_ID WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$result = $statement->fetchAll();

		if(isset($result[0][ID]) and $result[0][ID] != null){
			if(isset($result[0][BLOCKED_ID]) and $result[0][BLOCKED_ID] != NULL){
				$blockedIDs = str_replace(";", ",", $result[0][BLOCKED_ID]);
			}
			else{
				$blockedIDs = 0;
			}
			$statement = $db->prepare('SELECT ID FROM lobbies WHERE REF = :lobbyRef');
			$statement->bindParam(':lobbyRef', $_GET["lobbyRef"], PDO::PARAM_STR);
			$statement->execute();
			$lobbyID = $statement->fetchColumn();
			
			if(isset($lobbyID) and $lobbyID != ""){	// Lobby exists
				if(isset($_GET["offset"]) and $_GET["offset"] != ""){
					$offset = $_GET["offset"];
				}
				else{
					$offset = 0;
				}
				$statement = $db->prepare('SELECT * FROM (SELECT messages.ID as MSGID, messages.ACC_ID AS AUTHOR_ID, messages.LOBBY_ID, MESSAGE, MESSAGE_TYPE, QUOTE_ID, POST_DATE, READ_LIST, STATUS, REF AS LOBBY_REF, TYPE AS LOBBY_TYPE, accounts_data.NAME, accounts_data.SURNAME, USERNAME, IMGURL, GENDER, PINNED FROM messages INNER JOIN lobbies ON messages.LOBBY_ID = lobbies.ID INNER JOIN accounts_data ON messages.ACC_ID = accounts_data.ACC_ID INNER JOIN lobbies_users ON lobbies_users.ACC_ID = :usrID LEFT JOIN accounts_messages ON accounts_messages.MESSAGE_ID = messages.ID WHERE messages.LOBBY_ID = :lobbyID AND lobbies.TYPE = 1 AND messages.ACC_ID NOT IN (:blockedIDs) AND POST_DATE >= DATE_SUB(ENTRY_DATE, INTERVAL 1 DAY) ORDER BY POST_DATE ASC LIMIT :offset, :messagesQttToLoad)var1 ORDER BY POST_DATE DESC');
				$statement->bindParam(':usrID', $result[0][ID], PDO::PARAM_INT);				
				$statement->bindParam(':lobbyID', $lobbyID, PDO::PARAM_INT);				
				$statement->bindParam(':blockedIDs', $blockedIDs, PDO::PARAM_STR);				
				$statement->bindParam(':offset', $offset, PDO::PARAM_INT);
				$statement->bindParam(':messagesQttToLoad', $messagesQttToLoad, PDO::PARAM_INT);
				$statement->execute();
				
				$toPrint = "";
				$messageBlock = "";
				$classNames2 = false;
				$usrOptns2  = false;
				$imgSrc2 = false;
				$displayName2 = false;
				$fullName2 = false;
				$username2 = false;
				$messageBlockAuthorID = 0;
				while ($predata = $statement->fetch()){		
					// Check USE_USRNAME value 
					$statement2 = $db->prepare('SELECT USE_USRNAME FROM accounts_settings WHERE ACC_ID = :targetID');
					$statement2->bindParam(':targetID', $predata["AUTHOR_ID"], PDO::PARAM_INT);				
					$statement2->execute();
					$useUsrname = $statement2->fetchColumn();

					//Pin message Check
					if($predata["PINNED"] == "1"){
						$pinMessageImg = "pinnedMessage";
						$pinMessageClass = "msgPinnedIcon";
					}
					else{
						$pinMessageImg = "pinMessage";
						$pinMessageClass = "msgPinIcon";
					}

					// Display or not fullName
					if($useUsrname == "0"){
						$fullName = $predata["NAME"].' '.$predata["SURNAME"];
						if($predata["USERNAME"] != ""){
							$username = $predata["USERNAME"];
						}
						else{
							$username = "username_not_set";
						}
						$displayName = $fullName;
					}
					else{
						if($predata["USERNAME"] != ""){
							$fullName = "PRIVATE";
							$username = $predata["USERNAME"];
							$displayName = $username;
						}
						else{
							$fullName = $predata["NAME"].' '.$predata["SURNAME"];
							$username = "username_not_set";
							$displayName = $fullName;
						}
					}
					// ------------------------------------------

					// Creaate image variable if no image uploaded
					if($predata["IMGURL"] != NULL){
						$imgSrc = "http://cheyennevalmond.com/layover/usrPics/" . $predata["IMGURL"] . ".jpg";
					}
					else{
						if(isset($predata["GENDER"]) and $predata["GENDER"] == "F"){
							$imgSrc = "images/femaleCCLogo.jpg";
						}
						else {
							$imgSrc = "images/maleCCLogo.jpg";
						}
					}
					// ------------------------------------------

					// ------------------------------------------
					if($predata["AUTHOR_ID"] == $result[0][ID]){ //Current user message
						$classNames = ["user", "msgUsrInfo", "usrMsg", "usrPin"];
						$usrOptns = "";
					}
					else{
						$classNames = ["other", "msgOthInfo", "othMsg", "othPin"];
						$usrOptns = '<img src="images/icons/profile.png" class="msgInfoIcon" onclick="loadOtherProfile('.$predata["AUTHOR_ID"].');" /><img src="images/icons/report.png" class="msgInfoIcon" onclick="toggleReportViewer(\'open\', '.$predata["AUTHOR_ID"].'); setReportVars(\''. $imgSrc .'\', \''. addslashes($fullName) .'\', \''. addslashes($username) .'\');" />';
					}
					// ------------------------------------------

					if($predata["AUTHOR_ID"] != $messageBlockAuthorID){
						if($messageBlock == ""){
							$messageBlock = '</div><span class="endofdiv"></span>';

							$messageContent = '<span class="messageBubble '.$classNames[2].'">';							
							$messageContent .= '<span class="messageText">'.htmlentities($predata["MESSAGE"]).'</span>';							
							$messageContent .= '<img id="pinBtn'.$predata["MSGID"].'" src="images/icons/'.$pinMessageImg.'.png" class="'.$pinMessageClass.' '.$classNames[3].'" onclick="pinUnpinMessage('.$predata["MSGID"].');" />';							
							$messageContent .= '</span>';	
							$messageBlock = $messageContent.$messageBlock;	

							$messageBlockAuthorID = $predata["AUTHOR_ID"];
							$classNames2 = $classNames;
							$usrOptns2 = $usrOptns;		
							$displayName2 = $displayName;
							$fullName2 = $fullName;
							$username2 = $username;
							$imgSrc2 = $imgSrc;
						}
						else{
							$messageTitle = '<div class="messageGroup '.$classNames2[0].'">';
							$messageTitle .= '<div class="messageInfo '.$classNames2[1].'">';
							$messageTitle .= '<img src="'.$imgSrc2.'" class="messageAuthorImg" onclick="toggleMessageInfo(\'change\');" />';
							$messageTitle .= '<span class="messageAuthorInfos">'.$displayName2.'</span>';
							$messageTitle .= $usrOptns2;
							$messageTitle .= '</div>';

							$messageBlock = $messageTitle.$messageBlock;	

							$toPrint = $messageBlock.$toPrint;
							
							$messageBlock = '</div><span class="endofdiv"></span>';

							$messageContent = '<span class="messageBubble '.$classNames[2].'">';							
							$messageContent .= '<span class="messageText">'.htmlentities($predata["MESSAGE"]).'</span>';							
							$messageContent .= '<img id="pinBtn'.$predata["MSGID"].'" src="images/icons/'.$pinMessageImg.'.png" class="'.$pinMessageClass.' '.$classNames[3].'" onclick="pinUnpinMessage('.$predata["MSGID"].');" />';							
							$messageContent .= '</span>';	
							$messageBlock = $messageContent.$messageBlock;	

							$messageBlockAuthorID = $predata["AUTHOR_ID"];
							$classNames2 = $classNames;
							$usrOptns2 = $usrOptns;	
							$displayName2 = $displayName;
							$fullName2 = $fullName;
							$username2 = $username;
							$imgSrc2 = $imgSrc;
						}
					}
					else{
						$messageContent = '<span class="messageBubble '.$classNames[2].'">';							
						$messageContent .= '<span class="messageText">'.htmlentities($predata["MESSAGE"]).'</span>';							
						$messageContent .= '<img id="pinBtn'.$predata["MSGID"].'" src="images/icons/'.$pinMessageImg.'.png" class="'.$pinMessageClass.' '.$classNames[3].'" onclick="pinUnpinMessage('.$predata["MSGID"].');" />';							
						$messageContent .= '</span>';	
						$messageBlock = $messageContent.$messageBlock;

						$classNames2 = $classNames;
						$usrOptns2 = $usrOptns;	
						$displayName2 = $displayName;
						$fullName2 = $fullName;
						$username2 = $username;
						$imgSrc2 = $imgSrc;
					}
				}
				
				if($messageBlock != ""){
					$messageTitle = '<div class="messageGroup '.$classNames2[0].'">';
					$messageTitle .= '<div class="messageInfo '.$classNames2[1].'">';
					$messageTitle .= '<img src="'.$imgSrc2.'" class="messageAuthorImg" onclick="toggleMessageInfo(\'change\');" />';
					$messageTitle .= '<span class="messageAuthorInfos">'.$displayName2.'</span>';
					$messageTitle .= $usrOptns2;
					$messageTitle .= '</div>';

					$messageBlock = $messageTitle.$messageBlock;
					$toPrint = $messageBlock.$toPrint;
					$messageBlock = "";

					echo $toPrint;
				}
			}
			else{
				$err = array('notif' => 'none', 'err' => 'requestNotFound:[lobbyID]');
				$output = json_encode($err);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'requestNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey,lobbyRef]');
		$output = json_encode($err);
		echo $output;
	}					
}
else if($_GET["todo"] == "checkLobbyEntries"){

	if(isset($_GET["usrKey"]) and isset($_GET["lobbyRef"]) and $_GET["usrKey"] != "" and $_GET["lobbyRef"] != ""){

		$statement = $db->prepare('SELECT accounts.ID, BLOCKED_ID FROM accounts INNER JOIN accounts_blacklist ON accounts.ID = accounts_blacklist.ACC_ID WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$result = $statement->fetchAll();

		$statement = $db->prepare('SELECT accounts.ID, BLOCKED_ID FROM accounts INNER JOIN accounts_blacklist ON accounts.ID = accounts_blacklist.ACC_ID WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$result = $statement->fetchAll();

		$statement = $db->prepare('SELECT ID FROM lobbies WHERE REF = :lobbyRef');
		$statement->bindParam(':lobbyRef', $_GET["lobbyRef"], PDO::PARAM_STR);
		$statement->execute();
		$lobbyID = $statement->fetchColumn();
			
		if(isset($lobbyID) and $lobbyID != ""){	// Lobby exists
			if(isset($result[0][BLOCKED_ID]) and $result[0][BLOCKED_ID] != NULL){
				$blockedIDs = str_replace(";", ",", $result[0][BLOCKED_ID]);
			}
			else{
				$blockedIDs = 0;
			}

			$statement = $db->prepare('SELECT COUNT(messages.ID) as TOTAL_MESSAGES FROM messages INNER JOIN lobbies ON lobbies.ID = :lobbyID INNER JOIN lobbies_users ON lobbies_users.ACC_ID = :usrID WHERE messages.LOBBY_ID = :lobbyID AND lobbies.TYPE = 1 AND POST_DATE >= DATE_SUB(ENTRY_DATE, INTERVAL 1 DAY) AND messages.ACC_ID NOT IN (:blockedIDs)');
			$statement->bindParam(':usrID', $result[0][ID], PDO::PARAM_INT);
			$statement->bindParam(':lobbyID', $lobbyID, PDO::PARAM_INT);
			$statement->bindParam(':blockedIDs', $blockedIDs, PDO::PARAM_STR);
			$statement->execute();
			$totalMessages = $statement->fetchColumn();

			if(isset($totalMessages) and $totalMessages != null){
				echo $totalMessages;
			}
			else{
				echo '-1';
			}
		}
		else{
			echo '-1';
		}
	}
	else{
		echo '-1';
	}					
}
else if($_GET["todo"] == "loadCompany"){
	$messagesQttToLoad = 20;

	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT accounts.ID, BLOCKED_ID, lobbies.ID AS LOBBY_ID FROM accounts INNER JOIN accounts_blacklist ON accounts.ID = accounts_blacklist.ACC_ID INNER JOIN accounts_data ON accounts_data.ACC_ID = accounts.ID INNER JOIN lobbies ON lobbies.REF = accounts_data.COMPANY_ID WHERE USR_KEY = :usrKey AND lobbies.TYPE = 2');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$result = $statement->fetchAll();

		if(isset($result[0][ID]) and $result[0][ID] != null){
			if(isset($result[0][BLOCKED_ID]) and $result[0][BLOCKED_ID] != NULL){
				$blockedIDs = str_replace(";", ",", $result[0][BLOCKED_ID]);
			}
			else{
				$blockedIDs = 0;
			}
			
			if(isset($result[0][LOBBY_ID]) and $result[0][LOBBY_ID] != ""){	// Company exists
				if(isset($_GET["offset"]) and $_GET["offset"] != ""){
					$offset = $_GET["offset"];
				}
				else{
					$offset = 0;
				}
				$statement = $db->prepare('SELECT messages.ID as MSGID, messages.ACC_ID AS AUTHOR_ID, LOBBY_ID, MESSAGE, MESSAGE_TYPE, QUOTE_ID, POST_DATE, READ_LIST, messages.STATUS, REF AS LOBBY_REF, TYPE AS LOBBY_TYPE, accounts_data.NAME, accounts_data.SURNAME, USERNAME, IMGURL, GENDER, PINNED, CREATION FROM messages INNER JOIN lobbies ON messages.LOBBY_ID = lobbies.ID INNER JOIN accounts_data ON messages.ACC_ID = accounts_data.ACC_ID LEFT JOIN accounts_messages ON accounts_messages.MESSAGE_ID = messages.ID AND accounts_messages.ACC_ID = :usrID INNER JOIN accounts ON accounts.ID = :usrID WHERE LOBBY_ID = :lobbyID AND lobbies.TYPE = 2 AND messages.ACC_ID NOT IN (:blockedIDs) AND POST_DATE >= CREATION ORDER BY POST_DATE DESC LIMIT :offset, :messagesQttToLoad');
				$statement->bindParam(':usrID', $result[0][ID], PDO::PARAM_INT);				
				$statement->bindParam(':lobbyID', $result[0][LOBBY_ID], PDO::PARAM_INT);				
				$statement->bindParam(':blockedIDs', $blockedIDs, PDO::PARAM_STR);				
				$statement->bindParam(':offset', $offset, PDO::PARAM_INT);
				$statement->bindParam(':messagesQttToLoad', $messagesQttToLoad, PDO::PARAM_INT);
				$statement->execute();
				
				$toPrint = "";
				$messageBlock = "";
				$classNames2 = false;
				$usrOptns2  = false;
				$imgSrc2 = false;
				$displayName2 = false;
				$fullName2 = false;
				$username2 = false;
				$messageBlockAuthorID = 0;
				while ($predata = $statement->fetch()){
					// Check USE_USRNAME value 
					$statement2 = $db->prepare('SELECT USE_USRNAME FROM accounts_settings WHERE ACC_ID = :targetID');
					$statement2->bindParam(':targetID', $predata["AUTHOR_ID"], PDO::PARAM_INT);				
					$statement2->execute();
					$useUsrname = $statement2->fetchColumn();

					//Pin message Check
					if($predata["PINNED"] == "1"){
						$pinMessageImg = "pinnedMessage";
						$pinMessageClass = "msgPinnedIcon";
					}
					else{
						$pinMessageImg = "pinMessage";
						$pinMessageClass = "msgPinIcon";
					}

					// Display or not fullName
					if($useUsrname == "0"){
						$fullName = $predata["NAME"].' '.$predata["SURNAME"];
						if($predata["USERNAME"] != ""){
							$username = $predata["USERNAME"];
						}
						else{
							$username = "username_not_set";
						}
						$displayName = $fullName;
					}
					else{
						if($predata["USERNAME"] != ""){
							$fullName = "PRIVATE";
							$username = $predata["USERNAME"];
							$displayName = $username;
						}
						else{
							$fullName = $predata["NAME"].' '.$predata["SURNAME"];
							$username = "username_not_set";
							$displayName = $fullName;
						}
					}
					// ------------------------------------------

					// Creaate image variable if no image uploaded
					if($predata["IMGURL"] != NULL){
						$imgSrc = "http://cheyennevalmond.com/layover/usrPics/" . $predata["IMGURL"] . ".jpg";
					}
					else{
						if(isset($predata["GENDER"]) and $predata["GENDER"] == "F"){
							$imgSrc = "images/femaleCCLogo.jpg";
						}
						else {
							$imgSrc = "images/maleCCLogo.jpg";
						}
					}
					// ------------------------------------------

					// ------------------------------------------
					if($predata["AUTHOR_ID"] == $result[0][ID]){ //Current user message
						$classNames = ["user", "msgUsrInfo", "usrMsg", "usrPin"];
						$usrOptns = "";
					}
					else{
						$classNames = ["other", "msgOthInfo", "othMsg", "othPin"];
						$usrOptns = '<img src="images/icons/profile.png" class="msgInfoIcon" onclick="loadOtherProfile('.$predata["AUTHOR_ID"].');" /><img src="images/icons/report.png" class="msgInfoIcon" onclick="toggleReportViewer(\'open\', '.$predata["AUTHOR_ID"].'); setReportVars(\''. $imgSrc .'\', \''. addslashes($fullName) .'\', \''. addslashes($username) .'\');" />';
					}
					// ------------------------------------------

					if($predata["AUTHOR_ID"] != $messageBlockAuthorID){
						if($messageBlock == ""){
							$messageBlock = '</div><span class="endofdiv"></span>';
							$messageContent = '<span class="messageBubble '.$classNames[2].'">';							
							$messageContent .= '<span class="messageText">'.htmlentities($predata["MESSAGE"]).'</span>';							
							$messageContent .= '<img id="pinBtn'.$predata["MSGID"].'" src="images/icons/'.$pinMessageImg.'.png" class="'.$pinMessageClass.' '.$classNames[3].'" onclick="pinUnpinMessage('.$predata["MSGID"].');" />';							
							$messageContent .= '</span>';	
							$messageBlock = $messageContent.$messageBlock;	

							$messageBlockAuthorID = $predata["AUTHOR_ID"];
							$classNames2 = $classNames;
							$usrOptns2 = $usrOptns;		
							$displayName2 = $displayName;
							$fullName2 = $fullName;
							$username2 = $username;
							$imgSrc2 = $imgSrc;
						}
						else{
							$messageTitle = '<div class="messageGroup '.$classNames2[0].'">';
							$messageTitle .= '<div class="messageInfo '.$classNames2[1].'">';
							$messageTitle .= '<img src="'.$imgSrc2.'" class="messageAuthorImg" onclick="toggleMessageInfo(\'change\');" />';
							$messageTitle .= '<span class="messageAuthorInfos">'.$displayName2.'</span>';
							$messageTitle .= $usrOptns2;
							$messageTitle .= '</div>';

							$messageBlock = $messageTitle.$messageBlock;	
							$toPrint = $messageBlock.$toPrint;
							
							$messageBlock = '</div><span class="endofdiv"></span>';

							$messageContent = '<span class="messageBubble '.$classNames[2].'">';							
							$messageContent .= '<span class="messageText">'.htmlentities($predata["MESSAGE"]).'</span>';							
							$messageContent .= '<img id="pinBtn'.$predata["MSGID"].'" src="images/icons/'.$pinMessageImg.'.png" class="'.$pinMessageClass.' '.$classNames[3].'" onclick="pinUnpinMessage('.$predata["MSGID"].');" />';							
							$messageContent .= '</span>';	
							$messageBlock = $messageContent.$messageBlock;

							$messageBlockAuthorID = $predata["AUTHOR_ID"];
							$classNames2 = $classNames;
							$usrOptns2 = $usrOptns;	
							$displayName2 = $displayName;
							$fullName2 = $fullName;
							$username2 = $username;
							$imgSrc2 = $imgSrc;
						}
					}
					else{
						$messageContent = '<span class="messageBubble '.$classNames[2].'">';							
						$messageContent .= '<span class="messageText">'.htmlentities($predata["MESSAGE"]).'</span>';							
						$messageContent .= '<img id="pinBtn'.$predata["MSGID"].'" src="images/icons/'.$pinMessageImg.'.png" class="'.$pinMessageClass.' '.$classNames[3].'" onclick="pinUnpinMessage('.$predata["MSGID"].');" />';							
						$messageContent .= '</span>';	
						$messageBlock = $messageContent.$messageBlock;

						$classNames2 = $classNames;
						$usrOptns2 = $usrOptns;	
						$displayName2 = $displayName;
						$fullName2 = $fullName;
						$username2 = $username;
						$imgSrc2 = $imgSrc;
					}
				}

				if($messageBlock != ""){
					$messageTitle = '<div class="messageGroup '.$classNames2[0].'">';
					$messageTitle .= '<div class="messageInfo '.$classNames2[1].'">';
					$messageTitle .= '<img src="'.$imgSrc2.'" class="messageAuthorImg" onclick="toggleMessageInfo(\'change\');" />';
					$messageTitle .= '<span class="messageAuthorInfos">'.$displayName2.'</span>';
					$messageTitle .= $usrOptns2;
					$messageTitle .= '</div>';

					$messageBlock = $messageTitle.$messageBlock;
					$toPrint = $messageBlock.$toPrint;
					$messageBlock = "";

					echo $toPrint;
				}
			}
			else{
				$err = array('notif' => 'none', 'err' => 'requestNotFound:[lobbyID]');
				$output = json_encode($err);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'requestNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey,lobbyRef]');
		$output = json_encode($err);
		echo $output;
	}					
}
else if($_GET["todo"] == "checkIncEntries"){

	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		$statement = $db->prepare('SELECT accounts.ID, COMPANY_ID, BLOCKED_ID FROM accounts INNER JOIN accounts_data ON accounts_data.ACC_ID = accounts.ID INNER JOIN accounts_blacklist ON accounts.ID = accounts_blacklist.ACC_ID WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$result = $statement->fetchAll();
			
		if(isset($result[0][ID]) and $result[0][ID] != ""){	// usr exists
			$statement = $db->prepare('SELECT lobbies.ID AS LOBBY_ID FROM lobbies WHERE REF = :lobbyRef AND lobbies.TYPE = 2');
			$statement->bindParam(':lobbyRef', $result[0][COMPANY_ID], PDO::PARAM_INT);
			$statement->execute();
			$companyLobbyID = $statement->fetchColumn();

			if(!isset($companyLobbyID) or isset($companyLobbyID) and $companyLobbyID == ""){
				$statement = $db->prepare('INSERT INTO lobbies (ID, REF, TYPE) VALUES(NULL, :lobbyRef, 2)');
				$statement->bindParam(':lobbyRef', $result[0][COMPANY_ID], PDO::PARAM_INT);
				$statement->execute();				
	
				$companyLobbyID = $db->lastInsertId();
			}
			if(isset($result[0][BLOCKED_ID]) and $result[0][BLOCKED_ID] != NULL){
				$blockedIDs = str_replace(";", ",", $result[0][BLOCKED_ID]);
			}
			else{
				$blockedIDs = 0;
			}

			$statement = $db->prepare('SELECT COUNT(messages.ID) as TOTAL_MESSAGES FROM messages INNER JOIN lobbies ON lobbies.ID = messages.LOBBY_ID WHERE LOBBY_ID = :incID AND lobbies.TYPE = 2 AND messages.ACC_ID NOT IN (:blockedIDs)');
			$statement->bindParam(':incID', $companyLobbyID, PDO::PARAM_INT);
			$statement->bindParam(':blockedIDs', $blockedIDs, PDO::PARAM_STR);
			$statement->execute();
			$totalMessages = $statement->fetchColumn();

			if(isset($totalMessages) and $totalMessages != null){
				echo $totalMessages;
			}
			else{
				echo 'null';
			}	
		}
		else{
			echo 'null';
		}
	}
	else{
		echo 'null';
	}					
}
else if($_GET["todo"] == "postMessage"){

	if(isset($_GET["usrKey"]) and isset($_GET["message"]) and isset($_GET["lobbyRef"]) and $_GET["usrKey"] != "" and $_GET["message"] != "" and $_GET["lobbyRef"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){
			if ($_GET["lobbyRef"] == "inc"){
				$statement = $db->prepare('SELECT lobbies.ID AS LOBBY_ID FROM accounts INNER JOIN accounts_data ON accounts_data.ACC_ID = accounts.ID INNER JOIN lobbies ON lobbies.REF = accounts_data.COMPANY_ID WHERE USR_KEY = :usrKey AND lobbies.TYPE = 2');
				$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
				$statement->execute();
				$lobbyID = $statement->fetchColumn();
			}
			else {				
				$statement = $db->prepare('SELECT ID FROM lobbies WHERE REF = :lobbyRef');
				$statement->bindParam(':lobbyRef', $_GET["lobbyRef"], PDO::PARAM_STR);
				$statement->execute();
				$lobbyID = $statement->fetchColumn();
			}
			
			if(isset($lobbyID) and $lobbyID != ""){
				if(isset($_GET["quoteID"]) and $_GET["quoteID"] != "NULL"){
					$quoteID = $_GET["quoteID"];
				}
				else{
					$quoteID = NULL;
				}
				if(isset($_GET["messageType"]) and $_GET["messageType"] != ""){
					$messageType = $_GET["messageType"];
				}
				else{
					$messageType = 1;
				}
				$message = urldecode($_GET["message"]);

				$statement = $db->prepare('INSERT INTO messages (ID, ACC_ID, LOBBY_ID, MESSAGE, MESSAGE_TYPE, QUOTE_ID, POST_DATE) VALUES(NULL, :usrID, :lobbyID, :message, :messageType, :quoteID, "'.date('Y-m-d H:i:s').'")');
				$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
				$statement->bindParam(':lobbyID', $lobbyID, PDO::PARAM_INT);
				$statement->bindParam(':message', $message, PDO::PARAM_STR);
				$statement->bindParam(':messageType', $messageType, PDO::PARAM_INT);
				$statement->bindParam(':quoteID', $quoteID, PDO::PARAM_INT);
				$statement->execute();
				//Ceci est un exemple de message  nº9 c'envoi à la base de données >> Avec pleins de CARACTÈRES ; Spéciaux. 
				
				$err = array('notif' => 'message posted', 'err' => 'none');
				$output = json_encode($err);
				echo $output;
			}
			else{
				$err = array('notif' => 'none', 'err' => 'lobbyNotFound:[lobbyID]');
				$output = json_encode($err);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'userNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey,message,lobbyRef]');
		$output = json_encode($err);
		echo $output;
	}				
}
else if($_GET["todo"] == "chatParticipants"){
	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT accounts.ID, BLOCKED_ID, COMPANY_ID FROM accounts INNER JOIN accounts_blacklist ON accounts.ID = accounts_blacklist.ACC_ID INNER JOIN accounts_data ON accounts_data.ACC_ID = accounts.ID WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$result = $statement->fetchAll();

		if(isset($result[0][ID]) and $result[0][ID] != null){
			if(isset($result[0][BLOCKED_ID]) and $result[0][BLOCKED_ID] != NULL){
				$blockedIDs = str_replace(";", ",", $result[0][BLOCKED_ID]);
			}
			else{
				$blockedIDs = 0;
			}
			
			if(isset($_GET["lobbyRef"]) and $_GET["lobbyRef"] == "inc"){
				if(isset($result[0][COMPANY_ID]) and $result[0][COMPANY_ID] != ""){	// Company exists
					$statement = $db->prepare('SELECT accounts_data.ACC_ID, NAME, SURNAME, USERNAME, IMGURL, USE_USRNAME, GENDER FROM accounts_data INNER JOIN accounts_settings ON accounts_settings.ACC_ID = accounts_data.ACC_ID WHERE accounts_data.COMPANY_ID = :CompanyID ORDER BY NAME ASC');
					$statement->bindParam(':CompanyID', $result[0][COMPANY_ID], PDO::PARAM_INT);	
					$statement->execute();

					$toPrint = "";
					while ($predata = $statement->fetch()){
						$statement2 = $db->prepare('SELECT accounts_friendslist.STATUS FROM accounts_friendslist WHERE accounts_friendslist.ACC1_ID = :usrID AND accounts_friendslist.ACC2_ID = :targetID OR accounts_friendslist.ACC1_ID = :targetID AND accounts_friendslist.ACC2_ID = :usrID');
						$statement2->bindParam(':usrID', $result[0][ID], PDO::PARAM_INT);		
						$statement2->bindParam(':targetID', $predata["ACC_ID"], PDO::PARAM_INT);		
						$statement2->execute();
						$friendshipStatus = $statement2->fetchColumn();

						// TARGET NAME DETERMINATION
						if($predata["USE_USRNAME"] == 0){
							$targetName = $predata["NAME"]." ".$predata["SURNAME"];
						}
						else if($predata["USE_USRNAME"] == 1){
							$targetName = $predata["USERNAME"];
						}			
						// IMAGE DETERMINATION
						if($predata["IMGURL"] != NULL){
							$imgSrc = $imgSrc = "http://cheyennevalmond.com/layover/usrPics/" . $predata["IMGURL"] . ".jpg";
						}
						else{
							if(isset($predata["GENDER"]) and $predata["GENDER"] == "F"){
								$imgSrc = "images/femaleCCLogo.jpg";
							}
							else {
								$imgSrc = "images/maleCCLogo.jpg";
							}
						}
						// FRIENDSHIP STATUS
						if($predata["ACC_ID"] != $result[0][ID]){
							if($friendshipStatus == NULL){
								$usrOptns = '<img src="images/icons/add.png" class="msgInfoIcon noSelect" onclick="friends(\'invite\', \''.$predata["ACC_ID"].'\');this.src=\'images/icons/done.png\';$(this).attr(\'onclick\', \'\');"/>';								
								$onclick = 'loadOtherProfile(\''.$predata["ACC_ID"].'\');';
							}
							else if($friendshipStatus == 0){
								$usrOptns = '<img src="images/icons/done.png" class="msgInfoIcon noSelect" title="Invitation sent"/>';
								$onclick = 'loadOtherProfile(\''.$predata["ACC_ID"].'\');';
							}
							else if($friendshipStatus == 1){
								$usrOptns = '<img src="images/icons/remove.png" class="msgInfoIcon noSelect" onclick="friends(\'remove\', \''.$predata["ACC_ID"].'\');this.src=\'images/icons/done.png\';$(this).attr(\'onclick\', \'\');"/>';
								$onclick = 'loadOtherProfile(\''.$predata["ACC_ID"].'\');';
							}
						}
						else{
							$usrOptns = 'You';
							$onclick = "";
						}
						
						$toPrint .= '<span class="chatDetailsLine">
										<span class="chatDetailsParticiantInfo" onclick="'.$onclick.'">
											<img src="'.$imgSrc.'" class="chatDetailsUserPicture" />
											<span class="charDetailsUserName">'.$targetName.'</span>
										</span>
										<span class="chatDetailsParticipantBtns">
											'.$usrOptns.'
										</span>
									</span>';						
					}
					echo $toPrint;
				}
				else{					
					echo 'CompanyNotFound';
				}
			}
			else if(isset($_GET["lobbyRef"]) and $_GET["lobbyRef"] != "inc"){
				$statement = $db->prepare('SELECT ID FROM lobbies WHERE REF = :lobbyRef');
				$statement->bindParam(':lobbyRef', $_GET["lobbyRef"], PDO::PARAM_STR);
				$statement->execute();
				$lobbyID = $statement->fetchColumn();
				$lobbyType = 1;

				$statement = $db->prepare('SELECT lobbies_users.ACC_ID, NAME, SURNAME, USERNAME, IMGURL, USE_USRNAME, GENDER FROM lobbies_users INNER JOIN accounts_data ON accounts_data.ACC_ID = lobbies_users.ACC_ID INNER JOIN accounts_settings ON accounts_settings.ACC_ID = accounts_data.ACC_ID WHERE lobbies_users.LOBBY_ID = :lobbyID AND LOBBY_TYPE = :lobbyType ORDER BY NAME ASC');
				$statement->bindParam(':lobbyID', $lobbyID, PDO::PARAM_STR);
				$statement->bindParam(':lobbyType', $lobbyType, PDO::PARAM_STR);
				$statement->execute();

				while ($predata = $statement->fetch()){
					$statement2 = $db->prepare('SELECT accounts_friendslist.STATUS FROM accounts_friendslist WHERE accounts_friendslist.ACC1_ID = :usrID AND accounts_friendslist.ACC2_ID = :targetID OR accounts_friendslist.ACC1_ID = :targetID AND accounts_friendslist.ACC2_ID = :usrID');
					$statement2->bindParam(':usrID', $result[0][ID], PDO::PARAM_INT);		
					$statement2->bindParam(':targetID', $predata["ACC_ID"], PDO::PARAM_INT);		
					$statement2->execute();
					$friendshipStatus = $statement2->fetchColumn();

					// TARGET NAME DETERMINATION
					if($predata["USE_USRNAME"] == 0){
						$targetName = $predata["NAME"]." ".$predata["SURNAME"];
					}
					else if($predata["USE_USRNAME"] == 1){
						$targetName = $predata["USERNAME"];
					}			
					// IMAGE DETERMINATION
					if($predata["IMGURL"] != NULL){
						$imgSrc = "http://cheyennevalmond.com/layover/usrPics/" . $predata["IMGURL"] . ".jpg";
					}
					else{
						if(isset($predata["GENDER"]) and $predata["GENDER"] == "F"){
							$imgSrc = "images/femaleCCLogo.jpg";
						}
						else {
							$imgSrc = "images/maleCCLogo.jpg";
						}
					}
					// FRIENDSHIP STATUS
					if($predata["ACC_ID"] != $result[0][ID]){
						if($friendshipStatus == NULL){
							$usrOptns = '<img src="images/icons/add.png" class="msgInfoIcon noSelect" onclick="friends(\'invite\', \''.$predata["ACC_ID"].'\');this.src=\'images/icons/done.png\';$(this).attr(\'onclick\', \'\');"/>';
						}
						else if($friendshipStatus == 0){
							$usrOptns = '<img src="images/icons/done.png" class="msgInfoIcon noSelect" title="Invitation sent"/>';
						}
						else if($friendshipStatus == 1){
							$usrOptns = '<img src="images/icons/remove.png" class="msgInfoIcon noSelect" onclick="friends(\'remove\', \''.$predata["ACC_ID"].'\');this.src=\'images/icons/done.png\';$(this).attr(\'onclick\', \'\');"/>';
						}
					}
					else{
						$usrOptns = 'You';
					}
					$toPrint .= '<span class="chatDetailsLine">
									<span class="chatDetailsParticiantInfo" onclick="loadOtherProfile(\''.$predata["ACC_ID"].'\');">
										<img src="'.$imgSrc.'" class="chatDetailsUserPicture" />
										<span class="charDetailsUserName">'.$targetName.'</span>
									</span>
									<span class="chatDetailsParticipantBtns">
										'.$usrOptns.'
									</span>
								</span>';						
				}
				echo $toPrint;
			}
			else{
				$err = array('notif' => 'none', 'err' => 'requestNotFound:[lobbyRef]');
				$output = json_encode($err);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'requestNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "reportUsr"){
	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Check if number already in DB
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();
		
		if(isset($usrID) and $usrID != ""){			
			if(isset($_GET["targetID"]) and isset($_GET["r"]) and isset($_GET["d"]) and $_GET["targetID"] != "" and $_GET["r"] != "" and $_GET["d"] != ""){					
				$description = urldecode($_GET["d"]);

				$statement = $db->prepare("INSERT INTO reports (ID, REPORTING_ID, REPORTED_ID, REASON, DESCRIPTION) VALUES (null, :usrID, :targetID, :reason, :description)");
				$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
				$statement->bindParam(':targetID', $_GET["targetID"], PDO::PARAM_INT);
				$statement->bindParam(':reason', $_GET["r"], PDO::PARAM_INT);
				$statement->bindParam(':description', $description, PDO::PARAM_STR);
				$statement->execute();

				$results = array('notif' => 'reportSent', 'err' => 'none', 'instruction' => 'none');
				$output = json_encode($results);
				echo $output;			
			}
			else{
				$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[targetID;r;d]');
				$output = json_encode($err);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'usrKey does not match any existing account');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey;id]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "selectSQLData"){
	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Check if number already in DB
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != ""){			
			if(isset($_GET["table"]) and isset($_GET["identifier"]) and isset($_GET["idValue"]) and isset($_GET["idValueType"]) and isset($_GET["columns"]) and $_GET["table"] != "" and $_GET["identifier"] != "" and $_GET["idValue"] != "" and $_GET["idValueType"] != "" and $_GET["column"] != ""){
				$sql = "SELECT ".$_GET["columns"]." FROM ".$_GET["table"]." WHERE ".$_GET["identifier"]." = :idValue";
				if($_GET["idValueType"] == "int"){$valueType = "PDO::PARAM_INT";}
				else if($_GET["idValueType"] == "str"){$valueType = "PDO::PARAM_STR";}
				$statement = $db->prepare($sql);
				$statement->bindParam(':idValue', $_GET["idValue"], $valueType);
				$statement->execute();
				$data = $statement->fetchAll();

				$output = json_encode($data);
				echo $output;			
			}
			else{
				$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[table;identifier;idValue,idValueType,Columns]');
				$output = json_encode($err);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'usrKey does not match any existing account');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey;id]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "pinUnpinMessage"){
	if(isset($_GET["usrKey"]) and isset($_GET["messageID"]) and $_GET["usrKey"] != "" and $_GET["messageID"] != ""){
		// Check if number already in DB
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){			
			$prereq = $db->prepare('SELECT PINNED FROM accounts_messages WHERE MESSAGE_ID = :messageID');
			$prereq->bindParam(':messageID', $_GET["messageID"], PDO::PARAM_INT);
			$prereq->execute();
			$msgPinStatus = $prereq->fetchColumn();

			if($msgPinStatus != ""){						
				$statement = $db->prepare("DELETE FROM accounts_messages WHERE MESSAGE_ID = :messageID");
				$statement->bindParam(':messageID', $_GET["messageID"], PDO::PARAM_INT);
				$statement->execute();

				$results = array('notif' => 'MessageUnpinned', 'err' => 'none', 'instruction' => 'unpinned');
				$output = json_encode($results);
				echo $output;
			}
			else{
				$statement = $db->prepare("INSERT INTO accounts_messages (ID, ACC_ID, MESSAGE_ID) VALUES (null, :usrID, :messageID)");
				$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
				$statement->bindParam(':messageID', $_GET["messageID"], PDO::PARAM_INT);
				$statement->execute();

				$results = array('notif' => 'MessagePinned', 'err' => 'none', 'instruction' => 'pinned');
				$output = json_encode($results);
				echo $output;
			}			
		}
		else{
			$err = array('notif' => 'none', 'err' => 'accountNotFound:[usrKey]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey;messageID]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "loadImpMsgs"){	

	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT accounts.ID, lobbies.ID AS LOBBY_ID FROM accounts INNER JOIN accounts_data ON accounts_data.ACC_ID = accounts.ID INNER JOIN lobbies ON lobbies.REF = accounts_data.COMPANY_ID WHERE USR_KEY = :usrKey AND lobbies.TYPE = 2');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$result = $statement->fetchAll();

		if(isset($result[0][ID]) and $result[0][ID] != null){		
			if(isset($_GET["lobbyRef"]) and $_GET["lobbyRef"] != ""){
				if($_GET["lobbyRef"] == "inc"){
					if(isset($result[0][LOBBY_ID]) and $result[0][LOBBY_ID] != ""){	// Company exists
						$lobbyID = $result[0][LOBBY_ID];
					}
				}
				else{
					$statement = $db->prepare('SELECT lobbies.ID FROM lobbies WHERE REF = :lobbyRef AND lobbies.TYPE = 1');
					$statement->bindParam(':lobbyRef', $_GET["lobbyRef"], PDO::PARAM_STR);
					$statement->execute();
					$result2 = $statement->fetchColumn();

					if($result != ""){
						$lobbyID = $result2;
					}
				}
			}
			
			if(isset($lobbyID) and $lobbyID != ""){	// lobbyID is valid				
				$statement = $db->prepare('SELECT MESSAGE_ID, messages.ACC_ID AS AUTHOR_ID, LOBBY_ID, MESSAGE, MESSAGE_TYPE, POST_DATE, STATUS, accounts_data.NAME, accounts_data.SURNAME, USERNAME, IMGURL, GENDER, PINNED, USE_USRNAME FROM accounts_messages INNER JOIN messages ON messages.ID = MESSAGE_ID INNER JOIN accounts_data ON messages.ACC_ID = accounts_data.ACC_ID INNER JOIN accounts_settings ON accounts_settings.ACC_ID = :usrID WHERE LOBBY_ID = :lobbyID AND accounts_messages.ACC_ID = :usrID AND PINNED = 1 ORDER BY POST_DATE DESC');
				$statement->bindParam(':usrID', $result[0][ID], PDO::PARAM_INT);				
				$statement->bindParam(':lobbyID', $lobbyID, PDO::PARAM_INT);					
				$statement->execute();
				
				$toPrint = "";
				$messageBlock = "";
				while ($predata = $statement->fetch()){	

					// Check USE_USRNAME value 
					$statement2 = $db->prepare('SELECT USE_USRNAME FROM accounts_settings WHERE ACC_ID = :targetID');
					$statement2->bindParam(':targetID', $predata["AUTHOR_ID"], PDO::PARAM_INT);				
					$statement2->execute();
					$useUsrname = $statement2->fetchColumn();

					// Display or not fullName
					if($useUsrname == "0"){
						$fullName = $predata["NAME"].' '.$predata["SURNAME"];
						if($predata["USERNAME"] != ""){
							$username = $predata["USERNAME"];
						}
						else{
							$username = "username_not_set";
						}
						$displayName = $fullName;
					}
					else{
						if($predata["USERNAME"] != ""){
							$fullName = "PRIVATE";
							$username = $predata["USERNAME"];
							$displayName = $username;
						}
						else{
							$fullName = $predata["NAME"].' '.$predata["SURNAME"];
							$username = "username_not_set";
							$displayName = $fullName;
						}
					}
					// ------------------------------------------

					// Creaate image variable if no image uploaded
					if($predata["IMGURL"] != NULL){
						$imgSrc = "http://cheyennevalmond.com/layover/usrPics/" . $predata["IMGURL"] . ".jpg";
					}
					else{
						if(isset($predata["GENDER"]) and $predata["GENDER"] == "F"){
							$imgSrc = "images/femaleCCLogo.jpg";
						}
						else {
							$imgSrc = "images/maleCCLogo.jpg";
						}
					}
					// ------------------------------------------

					// ------------------------------------------
					if($predata["AUTHOR_ID"] == $result[0][ID]){ //Current user message
						$classNames = ["other", "msgUsrInfo", "usrMsg", "usrPin"];
						$usrOptns = "";
					}
					else{
						$classNames = ["other", "msgOthInfo", "othMsg", "othPin"];
						$usrOptns = '<img src="images/icons/profile.png" class="msgInfoIcon" onclick="loadOtherProfile('.$predata["AUTHOR_ID"].');" /><img src="images/icons/report.png" class="msgInfoIcon" onclick="toggleReportViewer(\'open\', '.$predata["AUTHOR_ID"].'); setReportVars(\''. $imgSrc .'\', \''. addslashes($fullName) .'\', \''. addslashes($username) .'\');" />';
					}
					// ------------------------------------------

					$messageBlock = '<div id="impMsg'.$predata["MESSAGE_ID"].'" class="messageGroup '.$classNames[0].'">';
					$messageBlock .= '<div class="importantMessageInfo" style="border-bottom: 0px; width: 100%; border-right: 0px;">';
					$messageBlock .= '<img src="'.$imgSrc.'" class="importantMessageAuthorImg" onclick="toggleMessageInfo(\'change\');">';
					$messageBlock .= '<span class="messageAuthorInfos">'.$displayName.'</span>';
					$messageBlock .= $usrOptns;
					$messageBlock .= '</div>';
					$messageBlock .= '<span class="importantMessageBubble '.$classNames[2].'" style="flex-direction: row; margin-top: 10px; margin-bottom: 10px;">';
					$messageBlock .= '<span class="messageText">'.htmlentities($predata["MESSAGE"]).'</span>';
					$messageBlock .= '<img id="pinBtn'.$predata["MESSAGE_ID"].'" src="images/icons/pinnedMessage.png" class="msgPinnedIcon impOthPin" onclick="pinUnpinMessage('.$predata["MESSAGE_ID"].');$(\'#impMsg'.$predata["MESSAGE_ID"].'\').slideUp();">';
					$messageBlock .= '</span>';
					$messageBlock .= '<span class="importantMessageDate">';
					$messageBlock .= $predata["POST_DATE"];
					$messageBlock .= '</span>';
					$messageBlock .= '</div>';

					$toPrint .= $messageBlock;
					$messageBlock = "";
				}

				if($toPrint != ""){
					$toPrint .= "<hr />";
					echo $toPrint;
				}
				else{
					$toPrint = '<span style="width: 100%; height: 150px; display: flex; flex-direction: column; justify-content: center; align-items: center; border-top: 1px solid grey; border-bottom: 1px solid grey;">No important message found</span>';
					echo $toPrint;
				}
			}
			else{
				$err = array('notif' => 'none', 'err' => 'requestNotFound:[lobbyID]');
				$output = json_encode($err);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'userNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[lobbyRef]');
		$output = json_encode($err);
		echo $output;
	}					
}
else if($_GET["todo"] == "searchMessage"){	
	
	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT accounts.ID, lobbies.ID AS LOBBY_ID FROM accounts INNER JOIN accounts_data ON accounts_data.ACC_ID = accounts.ID INNER JOIN lobbies ON lobbies.REF = accounts_data.COMPANY_ID WHERE USR_KEY = :usrKey AND lobbies.TYPE = 2');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$result = $statement->fetchAll();

		if(isset($result[0][ID]) and $result[0][ID] != null){		
			if(isset($_GET["lobbyRef"]) and $_GET["lobbyRef"] != ""){
				if($_GET["lobbyRef"] == "inc"){
					if(isset($result[0][LOBBY_ID]) and $result[0][LOBBY_ID] != ""){	// Company exists
						$lobbyID = $result[0][LOBBY_ID];
					}
				}
				else{
					$statement = $db->prepare('SELECT lobbies.ID FROM lobbies WHERE REF = :lobbyRef AND lobbies.TYPE = 1');
					$statement->bindParam(':lobbyRef', $_GET["lobbyRef"], PDO::PARAM_STR);
					$statement->execute();
					$result2 = $statement->fetchColumn();

					if($result != ""){
						$lobbyID = $result2;
					}
				}
			}
			else{
				$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[lobbyRef]');
				$output = json_encode($err);
				echo $output;
				exit;
			}
			
			if(isset($lobbyID) and $lobbyID != ""){	// lobbyID is valid		
				if(isset($_GET["searchParam"]) and $_GET["searchParam"] != ""){	
					if($_GET["lobbyRef"] == "inc"){
						$sql = 'SELECT messages.ID AS MESSAGE_ID, messages.ACC_ID AS AUTHOR_ID, messages.LOBBY_ID, MESSAGE, MESSAGE_TYPE, POST_DATE, messages.STATUS, accounts_data.NAME, accounts_data.SURNAME, USERNAME, IMGURL, GENDER, PINNED, USE_USRNAME, CREATION FROM messages LEFT JOIN accounts_messages ON messages.ACC_ID = :usrID AND messages.ID = accounts_messages.MESSAGE_ID INNER JOIN accounts_data ON messages.ACC_ID = accounts_data.ACC_ID INNER JOIN accounts_settings ON accounts_settings.ACC_ID = messages.ACC_ID INNER JOIN accounts ON accounts.ID = :usrID WHERE messages.LOBBY_ID = :lobbyID AND MESSAGE LIKE :searchParam AND POST_DATE >= CREATION ORDER BY POST_DATE DESC';
					}
					else{
						$sql = 'SELECT messages.ID AS MESSAGE_ID, messages.ACC_ID AS AUTHOR_ID, messages.LOBBY_ID, MESSAGE, MESSAGE_TYPE, POST_DATE, STATUS, accounts_data.NAME, accounts_data.SURNAME, USERNAME, IMGURL, GENDER, PINNED, USE_USRNAME, ENTRY_DATE FROM messages LEFT JOIN accounts_messages ON messages.ACC_ID = :usrID AND messages.ID = accounts_messages.MESSAGE_ID INNER JOIN accounts_data ON messages.ACC_ID = accounts_data.ACC_ID INNER JOIN accounts_settings ON accounts_settings.ACC_ID = messages.ACC_ID INNER JOIN lobbies_users ON lobbies_users.ACC_ID = :usrID WHERE messages.LOBBY_ID = :lobbyID AND MESSAGE LIKE :searchParam AND POST_DATE >= DATE_SUB(ENTRY_DATE, INTERVAL 1 DAY) ORDER BY POST_DATE DESC';
					}
					$searchParam = "%".$_GET["searchParam"]."%";
					$statement = $db->prepare($sql);
					$statement->bindParam(':usrID', $result[0][ID], PDO::PARAM_INT);				
					$statement->bindParam(':lobbyID', $lobbyID, PDO::PARAM_INT);	
					$statement->bindParam(':searchParam', $searchParam, PDO::PARAM_STR);				
					$statement->execute();
				
					$toPrint = "";
					$messageBlock = "";
					while ($predata = $statement->fetch()){		
						//Pin message Check
						if($predata["PINNED"] == "1"){
							$pinMessageImg = "pinnedMessage";
							$pinMessageClass = "msgPinnedIcon";
						}
						else{
							$pinMessageImg = "pinMessage";
							$pinMessageClass = "msgPinIcon";
						}

						// Check USE_USRNAME value 
						$statement2 = $db->prepare('SELECT USE_USRNAME FROM accounts_settings WHERE ACC_ID = :targetID');
						$statement2->bindParam(':targetID', $predata["AUTHOR_ID"], PDO::PARAM_INT);				
						$statement2->execute();
						$useUsrname = $statement2->fetchColumn();

						// Display or not fullName
						if($useUsrname == "0"){
							$fullName = $predata["NAME"].' '.$predata["SURNAME"];
							if($predata["USERNAME"] != ""){
								$username = $predata["USERNAME"];
							}
							else{
								$username = "username_not_set";
							}
							$displayName = $fullName;
						}
						else{
							if($predata["USERNAME"] != ""){
								$fullName = "PRIVATE";
								$username = $predata["USERNAME"];
								$displayName = $username;
							}
							else{
								$fullName = $predata["NAME"].' '.$predata["SURNAME"];
								$username = "username_not_set";
								$displayName = $fullName;
							}
						}
						// ------------------------------------------

						// Creaate image variable if no image uploaded
						if($predata["IMGURL"] != NULL){
							$imgSrc = "http://cheyennevalmond.com/layover/usrPics/" . $predata["IMGURL"] . ".jpg";
						}
						else{
							if(isset($predata["GENDER"]) and $predata["GENDER"] == "F"){
								$imgSrc = "images/femaleCCLogo.jpg";
							}
							else {
								$imgSrc = "images/maleCCLogo.jpg";
							}
						}
						// ------------------------------------------

						// ------------------------------------------
						if($predata["AUTHOR_ID"] == $result[0][ID]){ //Current user message
							$classNames = ["other", "msgUsrInfo", "usrMsg", "usrPin"];
							$usrOptns = "";
						}
						else{
							$classNames = ["other", "msgOthInfo", "othMsg", "othPin"];
							$usrOptns = '<img src="images/icons/profile.png" class="msgInfoIcon" onclick="loadOtherProfile('.$predata["AUTHOR_ID"].');" /><img src="images/icons/report.png" class="msgInfoIcon" onclick="toggleReportViewer(\'open\', '.$predata["AUTHOR_ID"].'); setReportVars(\''. $imgSrc .'\', \''. addslashes($fullName) .'\', \''. addslashes($username) .'\');" />';
						}
						// ------------------------------------------

						$messageBlock = '<div id="impMsg'.$predata["MESSAGE_ID"].'" class="messageGroup '.$classNames[0].'">';
						$messageBlock .= '<div class="importantMessageInfo" style="border-bottom: 0px; width: 100%; border-right: 0px;">';
						$messageBlock .= '<img src="'.$imgSrc.'" class="importantMessageAuthorImg" onclick="toggleMessageInfo(\'change\');">';
						$messageBlock .= '<span class="messageAuthorInfos">'.$displayName.'</span>';
						$messageBlock .= $usrOptns;
						$messageBlock .= '</div>';
						$messageBlock .= '<span class="importantMessageBubble '.$classNames[2].'" style="flex-direction: row; margin-top: 10px; margin-bottom: 10px;">';
						$messageBlock .= '<span class="messageText">'.htmlentities($predata["MESSAGE"]).'</span>';
						$messageBlock .= '<img id="searchPinBtn'.$predata["MESSAGE_ID"].'" src="images/icons/'.$pinMessageImg.'.png" class="'.$pinMessageClass.' impOthPin" onclick="pinUnpinMessage('.$predata["MESSAGE_ID"].');">';
						$messageBlock .= '</span>';
						$messageBlock .= '<span class="importantMessageDate">';
						$messageBlock .= $predata["POST_DATE"];
						$messageBlock .= '</span>';
						$messageBlock .= '</div>';

						$toPrint .= $messageBlock;
						$messageBlock = "";
					}

					if($toPrint != ""){
						$toPrint .= "<hr />";
						echo $toPrint;
					}
					else{
						$toPrint = '<span style="width: 100%; height: 150px; display: flex; flex-direction: column; justify-content: center; align-items: center; border-top: 1px solid grey; border-bottom: 1px solid grey;">No message containing "'.$_GET["searchParam"].'" was found</span>';
						echo $toPrint;
					}
				}
				else{
					$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[searchParam]');
					$output = json_encode($err);
					echo $output;
				}	
			}
			else{
				$err = array('notif' => 'none', 'err' => 'lobbyNotFound:[lobbyID]');
				$output = json_encode($err);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'userNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey]');
		$output = json_encode($err);
		echo $output;
	}					
}
else if($_GET["todo"] == "loadSettings"){
	
	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){	
			$statement = $db->prepare('SELECT accounts_settings.*, accounts_data.USERNAME FROM accounts_settings LEFT JOIN accounts_data ON accounts_data.ACC_ID = :usrID WHERE accounts_settings.ACC_ID = :usrID');
			$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
			$statement->execute();
			$result = $statement->fetchAll();

			/*$err = array('notif' => 'profileLoaded', 'err' => 'none');
			$output = json_encode($err);*/
			$output = json_encode($result[0]);
			echo $output;
		}
		else{
			$err = array('notif' => 'none', 'err' => 'userNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey,column,content]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "updateSettings"){
	
	if(isset($_GET["usrKey"]) and isset($_GET["column"]) and $_GET["usrKey"] != "" and $_GET["column"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){	
			
			if($_GET["column"] == "settUseUsrname"){$column = "USE_USRNAME";}
			else if($_GET["column"] == "settShareCoords"){$column = "SHARE_LOC";}
			else if($_GET["column"] == "settShowEmail"){$column = "SHARE_EMAIL";}
			else if($_GET["column"] == "settShowPhoneNumber"){$column = "SHARE_GSM";}
			else if($_GET["column"] == "settShowCompany"){$column = "SHARE_COMPANY";}
			else if($_GET["column"] == "settAppearInSearch"){$column = "APPEAR_IN_SEARCH";}
			else if($_GET["column"] == "settDarkMode"){$column = "DARK_MODE";}
			else if($_GET["column"] == "settEnableNotifs"){$column = "ENABLE_NOTIFS";}
			else if($_GET["column"] == "settNotifsPreview"){$column = "NOTIF_PREVIEW";}
			else if($_GET["column"] == "settLobbyMessagesNotifs"){$column = "LOBBY_NOTIFS";}
			else if($_GET["column"] == "settIncMessagesNotifs"){$column = "INC_NOTIFS";}
			else if($_GET["column"] == "settNewMeetingNotifs"){$column = "MEETINGS_NOTIFS";}
			else if($_GET["column"] == "settFriendsNotifs"){$column = "INVITATIONS_NOTIFS";}
			else {
				$err = array('notif' => 'none', 'err' => 'wrongParameter['.$_GET["column"].']');
				$output = json_encode($err);
				echo $output;
				exit;
			}

			$sql = 'SELECT '.$column.' FROM accounts_settings WHERE ACC_ID = :usrID';
			$statement = $db->prepare($sql);
			$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
			$statement->execute();
			$columnValue = $statement->fetchColumn();

			if(isset($columnValue) and $columnValue != null){	
			
				if($columnValue == 1){ $newSettingValue = 0;}
				else if($columnValue == 0){ $newSettingValue = 1;}
				
				$sql2 = 'UPDATE accounts_settings SET '.$column.' = :newValue WHERE ACC_ID = :usrID';
				$statement = $db->prepare($sql2);
				$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
				$statement->bindParam(':newValue', $newSettingValue, PDO::PARAM_INT);
				$statement->execute();

				$err = array('notif' => 'uID:'.$usrID.'; settingsUpdated['.$_GET["column"].']:('.$newSettingValue.')', 'err' => 'none');
				$output = json_encode($err);
				echo $output;
			}
			else{
				$err = array('notif' => 'none', 'err' => 'unknownParameter:[column]');
				$output = json_encode($err);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'userNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey,column,content]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "manageMeetings"){ // Premium Max Meetings : 5 // Regular Max Meetings : 1
	// todo=manageMeetings&usrKey=kjasdkjas&action=created&l=12%20av%20du%20marechal%20de%20turenne&s=2019-11-07T20%3A00&e=2019-11-07T23%3A00&lobbyRef=inc&apiKey=WKZXdrMlKfS3EbPc
	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Check if number already in DB
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){	
			// Get user Account Premium Status
			$statement = $db->prepare('SELECT PREMIUM FROM accounts WHERE ID = :usrID');
			$statement->bindParam(':usrID', $usrID, PDO::PARAM_STR);
			$statement->execute();
			$usrPremiumStatus = $statement->fetchColumn();		

			if(isset($_GET["l"]) and isset($_GET["s"]) and isset($_GET["e"]) and isset($_GET["t"]) and $_GET["l"] != "" and $_GET["s"] != "" and $_GET["e"] != "" and $_GET["t"] != ""){
				// CHECK/FORMAT VARIABLES FORMAT BEFORE PROCESSING
				$mtgAddress = urldecode($_GET["l"]);

				$mtgStart = urldecode($_GET["s"]);
				$mtgStart = str_replace("T", " ", $mtgStart);

				$mtgEnd = urldecode($_GET["e"]);
				$mtgEnd = str_replace("T", " ", $mtgEnd);

				$mtgTZOffset = urldecode($_GET["t"]);

				if(isset($_GET["meetingID"]) and $_GET["meetingID"] != ""){
					$prereq = $db->prepare('SELECT ID FROM meetings WHERE ID = :meetingID AND AUTHOR_ID = :usrID');
					$prereq->bindParam(':meetingID', $_GET["meetingID"], PDO::PARAM_INT);
					$prereq->bindParam(':usrID', $usrID, PDO::PARAM_INT);
					$prereq->execute();
					$meetingID = $prereq->fetchColumn();

					if($meetingID != null){
						if(isset($_GET["action"]) and $_GET["action"] == "edit"){
							//Edit Meeting							
							$prereq = $db->prepare('UPDATE meetings SET MTG_START = :mtgStart, MTG_END = :mtgEnd, TZ_OFFSET = :mtgTZOffset, ADDRESS = :mtgAddress WHERE ID = :meetingID AND AUTHOR_ID = :usrID AND STATUS = 1');
							$prereq->bindParam(':meetingID', $meetingID, PDO::PARAM_INT);
							$prereq->bindParam(':usrID', $usrID, PDO::PARAM_INT);
							$prereq->bindParam(':mtgStart', $mtgStart, PDO::PARAM_STR);
							$prereq->bindParam(':mtgEnd', $mtgEnd, PDO::PARAM_STR);
							$prereq->bindParam(':mtgTZOffset', $mtgTZOffset, PDO::PARAM_INT);
							$prereq->bindParam(':mtgAddress', $mtgAddress, PDO::PARAM_STR);
							$prereq->execute();

							$prereq = $db->prepare('UPDATE meetings_users SET STATUS = 2 WHERE MTG_ID = :meetingID AND ACC_ID != :usrID');
							$prereq->bindParam(':meetingID', $meetingID, PDO::PARAM_INT);
							$prereq->bindParam(':usrID', $usrID, PDO::PARAM_INT);
							$prereq->execute();

							/*$message = "This meeting has been modified. Don't forget to check it out !";
							$statement = $db->prepare('INSERT INTO messages (ID, ACC_ID, LOBBY_ID, MESSAGE, MESSAGE_TYPE, QUOTE_ID, POST_DATE) VALUES(NULL, :usrID, :lobbyID, :message, 2, :quoteID, "'.date('Y-m-d H:i:s').'")');
							$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
							$statement->bindParam(':lobbyID', $lobbyID, PDO::PARAM_INT);
							$statement->bindParam(':message', $message, PDO::PARAM_STR);
							$statement->bindParam(':quoteID', $meetingID, PDO::PARAM_INT);
							$statement->execute();*/
							
							$err = array('notif' => 'Meeting ID['.$meetingID.'] successfully editted', 'err' => 'none');
							$output = json_encode($err);
							echo $output;
						}
						else if(isset($_GET["action"]) and $_GET["action"] == "delete"){
							//Delete Meeting and participants		
							//PERSISTENT DATA (Change meeting STATUS in DB)											
							$prereq = $db->prepare('UPDATE meetings SET STATUS = 9 WHERE ID = :meetingID AND AUTHOR_ID = :usrID AND STATUS = 1');
							$prereq->bindParam(':meetingID', $meetingID, PDO::PARAM_INT);
							$prereq->bindParam(':usrID', $usrID, PDO::PARAM_INT);
							$prereq->execute();

							/*$message = "This meeting has just been canceled. Don't forget to check it out !";
							$statement = $db->prepare('INSERT INTO messages (ID, ACC_ID, LOBBY_ID, MESSAGE, MESSAGE_TYPE, QUOTE_ID, POST_DATE) VALUES(NULL, :usrID, :lobbyID, :message, 2, :quoteID, "'.date('Y-m-d H:i:s').'")');
							$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
							$statement->bindParam(':lobbyID', $lobbyID, PDO::PARAM_INT);
							$statement->bindParam(':message', $message, PDO::PARAM_STR);
							$statement->bindParam(':quoteID', $meetingID, PDO::PARAM_INT);
							$statement->execute();*/
							
							$err = array('notif' => 'Meeting ID['.$meetingID.'] successfully removed', 'err' => 'none');
							$output = json_encode($err);
							echo $output;

							//NON-PERSISTENT DATA (Remove all entries from DB)
							/*$prereq = $db->prepare('DELETE FROM meetings WHERE ID = :meetingID AND AUTHOR_ID = :usrID');
							$prereq->bindParam(':meetingID', $_GET["id"], PDO::PARAM_INT);
							$prereq->bindParam(':usrID', $usrID, PDO::PARAM_INT);
							$prereq->execute();					

							$prereq = $db->prepare('DELETE FROM meetings_users WHERE MTG_ID = :meetingID');
							$prereq->bindParam(':meetingID', $_GET["id"], PDO::PARAM_INT);
							$prereq->bindParam(':usrID', $usrID, PDO::PARAM_INT);
							$prereq->execute();*/
						}
						else{
							$err = array('notif' => 'none', 'err' => 'wrongParameter_or_missingParameter:[action]');
							$output = json_encode($err);
							echo $output;
						}
					}
					else{
						$err = array('notif' => 'none', 'err' => 'meetingNotFound_or_notMeetingAuthor:[id]');
						$output = json_encode($err);
						echo $output;
					}
				}
				else {
					if(isset($_GET["action"]) and $_GET["action"] == "create"){						
						if(isset($_GET["lobbyRef"]) and $_GET["lobbyRef"] != ""){
							if($_GET["lobbyRef"] == "inc"){
								$prereq = $db->prepare('SELECT ID FROM lobbies INNER JOIN accounts_data ON accounts_data.ACC_ID = :usrID WHERE REF = accounts_data.COMPANY_ID');
								$prereq->bindParam(':usrID', $usrID, PDO::PARAM_INT);
								$prereq->execute();
								$lobbyID = $prereq->fetchColumn();
							}
							else{
								$prereq = $db->prepare('SELECT ID FROM lobbies WHERE REF = :lobbyRef');
								$prereq->bindParam(':lobbyRef', $_GET["lobbyRef"], PDO::PARAM_INT);
								$prereq->execute();
								$lobbyID = $prereq->fetchColumn();
							}

							if(isset($lobbyID) and $lobbyID != null){	
								// Get user total created meetings
								$prereq = $db->prepare('SELECT COUNT(ID) FROM meetings WHERE AUTHOR_ID = :usrID and STATUS = 1');
								$prereq->bindParam(':usrID', $usrID, PDO::PARAM_INT);
								$prereq->execute();
								$totalActiveMeetings = $prereq->fetchColumn();

								$createMeeting = false;
								$accMeetingsLimit = 0;
								if($usrPremiumStatus == 1){
									$accMeetingsLimit = 5;
									if($totalActiveMeetings < 5){ 
										$createMeeting = true;
									}
								}
								else if($usrPremiumStatus == 0){
									$accMeetingsLimit = 1;
									if($totalActiveMeetings < 1){ 
										$createMeeting = true;
									}
								}

								if($createMeeting){							
									//Create Meeting			
									$prereq = $db->prepare('INSERT INTO meetings (ID, LOBBY_ID, AUTHOR_ID, MTG_START, MTG_END, TZ_OFFSET, ADDRESS, STATUS) VALUES (null, :lobbyID, :usrID, :mtgStart, :mtgEnd, :mtgTZOffset, :mtgAddress, 1)');
									$prereq->bindParam(':lobbyID', $lobbyID, PDO::PARAM_INT);
									$prereq->bindParam(':usrID', $usrID, PDO::PARAM_INT);
									$prereq->bindParam(':mtgStart', $mtgStart, PDO::PARAM_STR);
									$prereq->bindParam(':mtgEnd', $mtgEnd, PDO::PARAM_STR);
									$prereq->bindParam(':mtgTZOffset', $mtgTZOffset, PDO::PARAM_INT);
									$prereq->bindParam(':mtgAddress', $mtgAddress, PDO::PARAM_STR);
									$prereq->execute();

									$lastId = $db->lastInsertId();

									$message = "This new meeting has been created. Check it out !";
									$statement = $db->prepare('INSERT INTO messages (ID, ACC_ID, LOBBY_ID, MESSAGE, MESSAGE_TYPE, QUOTE_ID, POST_DATE) VALUES(NULL, :usrID, :lobbyID, :message, 2, :quoteID, "'.date('Y-m-d H:i:s').'")');
									$statement->bindParam(':usrID', $usrID, PDO::PARAM_INT);
									$statement->bindParam(':lobbyID', $lobbyID, PDO::PARAM_INT);
									$statement->bindParam(':message', $message, PDO::PARAM_STR);
									$statement->bindParam(':quoteID', $lastId, PDO::PARAM_INT);
									$statement->execute();

									$prereq = $db->prepare('INSERT INTO meetings_users (ID, MTG_ID, ACC_ID, STATUS) VALUES (NULL, :meetingID, :usrID, 1)');
									$prereq->bindParam(':meetingID', $lastId, PDO::PARAM_INT);
									$prereq->bindParam(':usrID', $usrID, PDO::PARAM_INT);
									$prereq->execute();
							
									$err = array('notif' => 'Meeting ID['.$lastId.'] successfully created', 'err' => 'none');
									$output = json_encode($err);
									echo $output;
								}
								else{
									$err = array('notif' => 'none', 'err' => 'meetingsMaxPerAccountReached:[premium:'.$usrPremiumStatus.';total:'.$totalActiveMeetings.'/'.$accMeetingsLimit.']');
									$output = json_encode($err);
									echo $output;
								}
							}
							else{
								$err = array('notif' => 'none', 'err' => 'lobbyNotFound[lobbyRef]');
								$output = json_encode($err);
								echo $output;
							}	
						}
						else{
							$err = array('notif' => 'none', 'err' => 'wrongParameter_or_missingParameter[lobbyRef]');
							$output = json_encode($err);
							echo $output;
						}		
					}			
					else{
						$err = array('notif' => 'none', 'err' => 'wrongParameter_or_missingParameter:[id]');
						$output = json_encode($err);
						echo $output;
					}
				}
			}
			else{
				$err = array('notif' => 'none', 'err' => 'missing_parameter:[l;s;e]');
				$output = json_encode($err);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'accountNotFound:[usrKey]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "loadMeetingsList"){

	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT accounts.ID, BLOCKED_ID FROM accounts INNER JOIN accounts_blacklist ON accounts.ID = accounts_blacklist.ACC_ID WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$result = $statement->fetchAll();

		if(isset($result[0][ID]) and $result[0][ID] != null){
			if(isset($result[0][BLOCKED_ID]) and $result[0][BLOCKED_ID] != NULL){
				$blockedIDs = str_replace(";", ",", $result[0][BLOCKED_ID]);
			}
			else{
				$blockedIDs = 0;
			}			
			
			if(isset($_GET["lobbyRef"]) and $_GET["lobbyRef"] != ""){
				if($_GET["lobbyRef"] == "inc"){
					$prereq = $db->prepare('SELECT ID FROM lobbies INNER JOIN accounts_data ON accounts_data.ACC_ID = :usrID WHERE REF = accounts_data.COMPANY_ID');
					$prereq->bindParam(':usrID', $result[0][ID], PDO::PARAM_INT);
					$prereq->execute();
					$lobbyID = $prereq->fetchColumn();
				}
				else{
					$prereq = $db->prepare('SELECT ID FROM lobbies WHERE REF = :lobbyRef');
					$prereq->bindParam(':lobbyRef', $_GET["lobbyRef"], PDO::PARAM_INT);
					$prereq->execute();
					$lobbyID = $prereq->fetchColumn();
				}

				if(isset($lobbyID) and $lobbyID != null){		
					
					$statement = $db->prepare('SELECT meetings.*, meetings_users.STATUS AS PARTICIPATION FROM meetings LEFT JOIN meetings_users ON meetings_users.ACC_ID = :usrID AND meetings_users.MTG_ID = meetings.ID WHERE LOBBY_ID = :lobbyID AND MTG_END - INTERVAL 30 MINUTE > NOW() AND meetings.STATUS = 1 ORDER BY MTG_START ASC');
					$statement->bindParam(':lobbyID', $lobbyID, PDO::PARAM_INT);				
					$statement->bindParam(':usrID', $result[0][ID], PDO::PARAM_INT);				
					$statement->execute();

					$resultArray = array();
					while ($predata = $statement->fetch()){				
						if($predata["AUTHOR_ID"] == $result[0][ID]){
							$usrAuthor = 1;
						}
						else{
							$usrAuthor = 0;
						}
						$meetingData = 
						array('ID' => $predata["ID"],
						'lobbyID' => $predata["LOBBY_ID"], 
						'authorID' => $predata["AUTHOR_ID"], 
						'usrAuthor' => $usrAuthor, 
						'mtgStart' => $predata["MTG_START"], 
						'mtgEnd' => $predata["MTG_END"], 
						'tzOffset' => $predata["TZ_OFFSET"], 
						'address' => $predata["ADDRESS"], 
						'participation' => $predata["PARTICIPATION"]);
						array_push($resultArray, $meetingData);						
					}
					$output = json_encode($resultArray);
					echo $output;			
				}
				else{
					$err = array('notif' => 'none', 'err' => 'lobbyNotFound:['.$_GET["lobbyRef"].']');
					$output = json_encode($err);	
					echo $output;			
				}
			}
			else if(isset($_GET["targetID"]) and $_GET["targetID"] != ""){
					$statement = $db->prepare('SELECT meetings.*, meetings_users.STATUS AS PARTICIPATION FROM meetings LEFT JOIN meetings_users ON meetings_users.ACC_ID = :usrID AND meetings_users.MTG_ID = meetings.ID WHERE meetings.ID = :meetingID');
					$statement->bindParam(':meetingID', $_GET["targetID"], PDO::PARAM_INT);				
					$statement->bindParam(':usrID', $result[0][ID], PDO::PARAM_INT);	
					$statement->execute();

					$resultArray = array();
					while ($predata = $statement->fetch()){					
						if($predata["AUTHOR_ID"] == $result[0][ID]){
							$usrAuthor = 1;
						}
						else{
							$usrAuthor = 0;
						}
						$meetingData = 
						array('ID' => $predata["ID"],
						'lobbyID' => $predata["LOBBY_ID"], 
						'authorID' => $predata["AUTHOR_ID"],
						'usrAuthor' => $usrAuthor, 
						'mtgStart' => $predata["MTG_START"], 
						'mtgEnd' => $predata["MTG_END"], 
						'tzOffset' => $predata["TZ_OFFSET"], 
						'address' => $predata["ADDRESS"], 
						'participation' => $predata["PARTICIPATION"]);
						array_push($resultArray, $meetingData);						
					}
					$output = json_encode($resultArray);
					echo $output;	
			}
			else{
				$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[lobbyRef,targetID]');
				$output = json_encode($err);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'userNotFound:[usrKey]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey]');
		$output = json_encode($err);
		echo $output;
	}					
}
else if($_GET["todo"] == "loadMeetingsParticipants"){
	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Get currentUsrID
		$statement = $db->prepare('SELECT accounts.ID, BLOCKED_ID, COMPANY_ID FROM accounts INNER JOIN accounts_blacklist ON accounts.ID = accounts_blacklist.ACC_ID INNER JOIN accounts_data ON accounts_data.ACC_ID = accounts.ID WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$result = $statement->fetchAll();

		if(isset($result[0][ID]) and $result[0][ID] != null){
			if(isset($result[0][BLOCKED_ID]) and $result[0][BLOCKED_ID] != NULL){
				$blockedIDs = explode(";", $result[0][BLOCKED_ID]);
			}
			else{
				$blockedIDs = 0;
			}
			
			if(isset($_GET["meetingID"]) and $_GET["meetingID"] != ""){
				$statement = $db->prepare('SELECT meetings_users.ACC_ID, meetings_users.STATUS AS PARTICIPATION, NAME, SURNAME, USERNAME, USE_USRNAME FROM meetings INNER JOIN meetings_users ON meetings_users.MTG_ID = meetings.ID INNER JOIN accounts_data ON accounts_data.ACC_ID = meetings_users.ACC_ID INNER JOIN accounts_settings ON accounts_settings.ACC_ID = accounts_data.ACC_ID WHERE meetings.ID = :meetingID ORDER BY NAME ASC');
				$statement->bindParam(':meetingID', $_GET["meetingID"], PDO::PARAM_INT);	
				$statement->execute();

				$toPrint = "";
				while ($predata = $statement->fetch()){

					// TARGET NAME DETERMINATION
					if($predata["USE_USRNAME"] == 0){
						$targetName = $predata["NAME"]." ".$predata["SURNAME"];
					}
					else if($predata["USE_USRNAME"] == 1){
						$targetName = $predata["USERNAME"];
					}			

					// TARGET PARTICIPATION
					if($predata["PARTICIPATION"] == 0){
						$imgName = "refuse.png";
					}
					else if($predata["PARTICIPATION"] == 1){
						$imgName = "accept.png";
					}
					else if($predata["PARTICIPATION"] == 2){
						$imgName = "maybe.png";
					}	
					
					if(in_array($predata["ACC_ID"], $blockedIDs)){
						$blacklisted = "(Blacklisted)";
					}
					else{
						$blacklisted = "";
					}
					
					if($predata["ACC_ID"] == $result[0][ID]){
						$currentUser = "currentUser";
					}
					else{
						$currentUser = "";
					}
						
					$toPrint .= '<span class="meetingParticipantLine '.$currentUser.'">
                            <span class="meetingParticipantName">'.$targetName.' '.$blacklisted.'</span>
                            <img src="images/icons/'.$imgName.'" class="meetingParticipantParticipation" />
                        </span>';						
				}
				if($toPrint != ""){
					echo $toPrint;
				}
				else{
					$toPrint = "<span style='width:80%;padding:0 10% 0 10%;'>No participant registered yet.</span>";
					echo $toPrint;
				
				}
			}
			else{
				$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[meetingID]');
				$output = json_encode($err);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'accountNotFound:[usrID]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey]');
		$output = json_encode($err);
		echo $output;
	}
}
else if($_GET["todo"] == "isPremium"){

	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Check if number already in DB
		$statement = $db->prepare('SELECT PREMIUM FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$isPremium = $statement->fetchColumn();

		echo $isPremium;
	}
	else{
		$output = "error";
		echo $output;
	}
}
else if($_GET["todo"] == "meetingParticipation"){

	if(isset($_GET["usrKey"]) and $_GET["usrKey"] != ""){
		// Check if number already in DB
		$statement = $db->prepare('SELECT ID FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $_GET["usrKey"], PDO::PARAM_STR);
		$statement->execute();
		$usrID = $statement->fetchColumn();

		if(isset($usrID) and $usrID != null){	

			if(isset($_GET["meetingID"]) and isset($_GET["participation"]) and $_GET["meetingID"] != "" and $_GET["participation"] != ""){

				$prereq = $db->prepare('SELECT ID FROM meetings_users WHERE MTG_ID = :meetingID AND ACC_ID = :usrID');
				$prereq->bindParam(':meetingID', $_GET["meetingID"], PDO::PARAM_INT);
				$prereq->bindParam(':usrID', $usrID, PDO::PARAM_INT);
				$prereq->execute();
				$participationExists = $prereq->fetchColumn();

				if(isset($participationExists) and $participationExists != null){
					//Edit participation							
					$sql = 'UPDATE meetings_users SET STATUS = :participation WHERE MTG_ID = :meetingID AND ACC_ID = :usrID';							
					$err = array('notif' => 'Meeting ID['.$_GET["meetingID"].'] participation successfully editted['.$_GET["participation"].']', 'err' => 'none');
				}
				else {
					//Create participation							
					$sql = 'INSERT INTO meetings_users (ID, MTG_ID, ACC_ID, STATUS) VALUES (NULL, :meetingID, :usrID, :participation)';
					$err = array('notif' => 'Meeting participation successfully created', 'err' => 'none');
				}					
				$prereq = $db->prepare($sql);
				$prereq->bindParam(':meetingID', $_GET["meetingID"], PDO::PARAM_INT);
				$prereq->bindParam(':usrID', $usrID, PDO::PARAM_INT);
				$prereq->bindParam(':participation', $_GET["participation"], PDO::PARAM_INT);
				$prereq->execute();
				
				$output = json_encode($err);
				echo $output;
			}
			else{
				$err = array('notif' => 'none', 'err' => 'missing_parameter:[meetingID;participation]');
				$output = json_encode($err);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'accountNotFound:[usrKey]');
			$output = json_encode($err);
			echo $output;
		}
	}
	else{
		$err = array('notif' => 'none', 'err' => 'parameter_missing_or_empty:[usrKey]');
		$output = json_encode($err);
		echo $output;
	}
}

//FUNCTIONS FUNCTIONS FUNCTIONS FUNCTIONS FUNCTIONS FUNCTIONS FUNCTIONS FUNCTIONS

function getFullAPIKey($db, $apiKey){
	if(isset($apiKey) and $apiKey != ""){
		$statement = $db->prepare('SELECT ACC_ID FROM api_access WHERE API_KEY = :apiKey ');
		$statement->bindParam(':apiKey', $apiKey, PDO::PARAM_STR);
		$statement->execute();
		$accID = $statement->fetchColumn();

		if(isset($accID) and $accID != ""){
			$statement = $db->prepare('SELECT PRIVATE_KEY FROM accounts WHERE ID = :accID ');
			$statement->bindParam(':accID', $accID, PDO::PARAM_INT);
			$statement->execute();
			$privateKey = $statement->fetchColumn();

			$result = $apiKey . $privateKey;
			return $result;
		}
		else{
			return false; //WrongPublicAPIKey
			exit("An API Key is required to access this page.");
		}
	}
}

function APIKeyCheck($db, $apiKey){
	if(isset($apiKey) and $apiKey != ""){
		/*$statement = $db->prepare('SELECT ACC_ID, PRIVATE_KEY FROM api_access INNER JOIN accounts ON accounts.ID = api_access.ACC_ID WHERE API_KEY = :apiKey ');
		$statement->bindParam(':apiKey', $_GET["apiKey"], PDO::PARAM_STR);
		$statement->execute();
		$results = $statement->fetchAll();

		$fullAPIKey = $apiKey . $results[0][PRIVATE_KEY];
		echo $apiKey. "//" .$fullAPIKey;
		if($apiKey == $fullAPIKey){*/
		$statement = $db->prepare('SELECT ACC_ID FROM api_access WHERE API_KEY = :apiKey ');
		$statement->bindParam(':apiKey', $apiKey, PDO::PARAM_STR);
		$statement->execute();
		$accID = $statement->fetchColumn();

		if(isset($accID) and $accID != ""){
			return true;
		}
		else{
			exit("The API Key provided is not accepted.");
		}
	}
	else{
			exit("An API Key is required to access this page.");
	}
}

function createAccount(){
	global $db;

	//Generate an unique USER_KEY
	$uniqueUserKey = false;
	while (!$uniqueUserKey){			
		$usrKey = genRandomConf(8);
		$statement = $db->prepare('SELECT USR_KEY FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $usrKey, PDO::PARAM_STR);
		$statement->execute();
		$result = $statement->fetchColumn();
		if($result == null){
			$uniqueUserKey = true;
		}
	}

	// Generate a Validation code for SMS confirmation
	$validationCode = genValidationCode(6);

	// Create entries in DB
	$statement = $db->prepare("INSERT INTO accounts (ID, USR_KEY, GSM_EXT, GSM, EMAIL, VALIDATION_CODE, CREATION) VALUES ( NULL, :usrKey, :gsmExt, :gsm, :email, :validationCode, '".date('Y-m-d H:i:s')."')");
	$statement->bindParam(':usrKey', $usrKey, PDO::PARAM_STR);
	$statement->bindParam(':gsmExt', $_GET["gsmExt"], PDO::PARAM_STR);
	$statement->bindParam(':gsm', $_GET["gsm"], PDO::PARAM_INT);
	$statement->bindParam(':email', $_GET["email"], PDO::PARAM_STR);
	$statement->bindParam(':validationCode', $validationCode, PDO::PARAM_INT);

	$statement->execute();
	
	$lastId = $db->lastInsertId();

	$statement2 = $db->prepare("INSERT INTO accounts_data (ACC_ID) VALUES (".$lastId.")");
	$statement2->execute();

	$statement3 = $db->prepare("INSERT INTO accounts_blacklist (ACC_ID) VALUES (".$lastId.")");
	$statement3->execute();

	$statement5 = $db->prepare("INSERT INTO accounts_settings (ACC_ID) VALUES (".$lastId.")");
	$statement5->execute();

	$statement6 = $db->prepare("INSERT INTO api_access (ACC_ID, API_KEY, STATUS) VALUES ('".$lastId."', :apiKey, 1)");
	$statement6->bindParam(':apiKey', $_GET["apiKey"], PDO::PARAM_STR);
	$statement6->execute();

	$db = null;

	sendEmail(0, $_GET["email"], $validationCode);
			
	$results = array('notif' => 'AccountCreated', 'err' => 'none', 'usrKey' => $usrKey, 'accStatus' => '0', 'instruction' => 'validationCode');
	$output = json_encode($results);
	echo $output;
}

function checkAccStatus($db, $usrKey){
		// Check if user exists in DB
		$statement = $db->prepare('SELECT ID, EMAIL, STATUS, CONNECTED FROM accounts WHERE USR_KEY = :usrKey');
		$statement->bindParam(':usrKey', $usrKey, PDO::PARAM_STR);
		$statement->execute();
		$result = $statement->fetchAll();

		if(isset($result[0][STATUS]) and $result[0][STATUS] != null){	
			if($result[0][STATUS] == 0){
				//Account still not validated
				//Send back email
				$validationCode = genValidationCode(6);
				$statement = $db->prepare('UPDATE accounts SET VALIDATION_CODE = :validationCode WHERE USR_KEY = :usrKey ');
				$statement->bindParam(':usrKey', $usrKey, PDO::PARAM_STR);
				$statement->bindParam(':validationCode', $validationCode, PDO::PARAM_INT);

				$statement->execute();
				
				sendEmail(0, $result[0][EMAIL], $validationCode);

				$results = array('err' => 'none', 'usrKey' => $usrKey, 'accStatus' => $result[0][STATUS], 'instruction' => 'validationCode');
				$output = json_encode($results);
				echo $output;
			}
			else if($result[0][STATUS] == 1){
				$results = array('err' => 'none', 'usrKey' => $usrKey, 'accStatus' => $result[0][STATUS], 'instruction' => 'companyIDValidation');
				$output = json_encode($results);
				echo $output;
			}
			else if($result[0][STATUS] == 2){
				$results = array('err' => 'none', 'usrKey' => $usrKey, 'accStatus' => $result[0][STATUS], 'instruction' => 'licenceValidationPending');
				$output = json_encode($results);
				echo $output;
			}
			else if($result[0][STATUS] == 3){
				$statement = $db->prepare('SELECT REASON FROM company_id_validation WHERE ACC_ID = :accID');
				$statement->bindParam(':accID', $result[0][ID], PDO::PARAM_INT);
				$statement->execute();
				$statusReason = $statement->fetchColumn();

				$results = array('err' => 'none', 'usrKey' => $usrKey, 'accStatus' => $result[0][STATUS], 'statusReason' => $statusReason, 'instruction' => 'companyIDValidationR');
				$output = json_encode($results);
				echo $output;
			}
			else if($result[0][STATUS] == 4){
				$statement = $db->prepare('SELECT ACC_ID, API_KEY FROM api_access WHERE ACC_ID = :accID');
				$statement->bindParam(':accID', $result[0][ID], PDO::PARAM_INT);
				$statement->execute();
				$result2 = $statement->fetchAll();

				if(isset($result2[0][API_KEY]) and $result2[0][API_KEY] != ""){
					if($result2[0][API_KEY] == $_GET["apiKey"]){	// Compte ouvert la dernière fois avec cet appareil
						if(isset($result[0][CONNECTED]) and $result[0][CONNECTED] == 1){	// Compte connecté
							$lastConnection = date('Y-m-d H:i:s');
							$statement = $db->prepare('UPDATE accounts SET LAST_CONNECTION = :lastConnection WHERE USR_KEY = :usrKey ');
							$statement->bindParam(':usrKey', $usrKey, PDO::PARAM_STR);
							$statement->bindParam(':lastConnection', $lastConnection, PDO::PARAM_STR);

							$statement->execute();

							$results = array('instruction' => 'login', 'accStatus' => $result[0][STATUS], 'usrKey' => $usrKey);
							$output = json_encode($results);
							echo $output;
						}
						else{
							// Se connecter par SMS display div : "connectionValidationCode"
							$validationCode = genValidationCode(6);
							$statement = $db->prepare('UPDATE accounts SET VALIDATION_CODE = :validationCode WHERE USR_KEY = :usrKey ');
							$statement->bindParam(':usrKey', $usrKey, PDO::PARAM_STR);
							$statement->bindParam(':validationCode', $validationCode, PDO::PARAM_INT);

							$statement->execute();
				
							sendEmail(1, $result[0][EMAIL], $validationCode);

							$results = array('instruction' => 'connectionValidationCode', 'email' => $result[0][EMAIL], 'accStatus' => $result[0][STATUS], 'usrKey' => $usrKey, 'connected' => $result[0][CONNECTED]);
							$output = json_encode($results);
							echo $output;
						}
					}
					else{	// Compte ouvert la dernière fois sur un autre appareil
						if(isset($result[0][CONNECTED]) and $result[0][CONNECTED] == 1){	// Compte connecté sur un autre appareil
							// Demander à le déconnecter
							$results = array('instruction' => 'disconnectionRequest', 'accStatus' => $result[0][STATUS], 'usrKey' => $usrKey, 'connected' => $result[0][CONNECTED]);
							$output = json_encode($results);
							echo $output;
						}
						else{
							// Compte déconnecté mais différent appareil
							$results = array('instruction' => 'changeDevice', 'accStatus' => $result[0][STATUS], 'usrKey' => $usrKey, 'connected' => $result[0][CONNECTED]);
							$output = json_encode($results);
							echo $output;
						}
					}
				}
			}
			else if($result[0][STATUS] == 5){
				$results = array('err' => 'none', 'usrKey' => $usrKey, 'accStatus' => $result[0][STATUS], 'instruction' => 'accountIncidents');
				$output = json_encode($results);
				echo $output;
			}
			else if($result[0][STATUS] == 6 or $result[0][STATUS] == 7){
				$statement = $db->prepare('SELECT STATUS_NOTIF FROM accounts WHERE ID = :accID');
				$statement->bindParam(':accID', $result[0][ID], PDO::PARAM_INT);
				$statement->execute();
				$statusReason = $statement->fetchColumn();

				$results = array('err' => 'none', 'usrKey' => $usrKey, 'accStatus' => $result[0][STATUS], 'statusReason' => $statusReason, 'instruction' => 'accountIncidents');
				$output = json_encode($results);
				echo $output;
			}
			else if($result[0][STATUS] == 8){
				$validationCode = genValidationCode(6);
				$statement = $db->prepare('UPDATE accounts SET VALIDATION_CODE = :validationCode WHERE USR_KEY = :usrKey ');
				$statement->bindParam(':usrKey', $usrKey, PDO::PARAM_STR);
				$statement->bindParam(':validationCode', $validationCode, PDO::PARAM_INT);

				$statement->execute();
				
				sendEmail(0, $result[0][EMAIL], $validationCode);

				$results = array('err' => 'none', 'usrKey' => $usrKey, 'accStatus' => $result[0][STATUS], 'instruction' => 'codeValidation');
				$output = json_encode($results);
				echo $output;
			}
		}
		else{
			$err = array('notif' => 'none', 'err' => 'accountNotFound:[usrKey]');
			$output = json_encode($err);
			echo $output;
		}
}

function sendEmail($emailType, $email, $verificationCode){

	// Envoi du mail de confirmation du compte		 
    if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $email))
    {
        $rn = "\r\n";
    }
    else
    {
        $rn = "\n";
    }
	
	if($emailType == 0){

		//=====Déclaration des messages au format texte et au format HTML.
		$message_txt = "Hello there !".$rn.$rn."

		Your LayOver account is about to be created.".$rn."
		To do so, follow these 3 Steps to confirm your account :".$rn.$rn."
    
		1- Copy your verification code : ".$verificationCode.$rn."
		2- Type your code in the LayOver app".$rn."
		3- Press the ''Verify'' button in the app".$rn.$rn."
    
		If the actual code doesn't work, ask for another verification code on the app.".$rn.$rn.$rn."
    
		See you soon !".$rn.$rn."
	
		Sincerly yours,".$rn."
		LayOver's father. ".$rn.$rn;

		$message_html = "
		<html>    
			<head>
			</head>
			<body>
				<div style='width: 800px; box-shadow: 0px 0px 10px -2px black; border: 1px solid #777;'>
					<div style='display: block; text-align: center; line-height: 150px; vertical-align: middle; height: 150px; width: 800px; background-color: grey; background: linear-gradient(175deg, rgb(0, 137, 216), rgb(68, 187, 255)); border-bottom: 1px solid #999; color: white; font-size: 36px; font-weight: bold;'>LayOver</div>
					<div style='color: #222;background-color: #f3f3f3;padding-left: 70px;padding-right: 70px;padding-top: 30px;padding-bottom: 30px; width: 660px; box-shadow: 0px 0px 8px -3px black inset; color: black; font-family: arial; font-size: 20px; word-break: break-word;'>
						<p>Hello there !</p>
						<p>
							Your LayOver account is about to be created.<br />
							To do so, follow these 3 Steps to confirm your account :<br />
						</p>
						<span style='display: flex; flex-direction: row; justify-content: flex-start; align-items: center'>
							<span style='height: 40px; width: 40px; text-align: center; line-height: 40px; vertical-align: middle; margin-right: 20px; margin-bottom: 5px; border: 1px solid white; border-radius: 50px; color: white; font-weight: bold; background-color: rgb(0, 137, 216);'>1</span>
							Copy your verification code :<strong> ".$verificationCode."</strong>
						</span>
						<span style='display: flex; flex-direction: row; justify-content: flex-start; align-items: center'>
							<span style='height: 40px; width: 40px; text-align: center; line-height: 40px; vertical-align: middle; margin-right: 20px; margin-bottom: 5px; border: 1px solid white; border-radius: 50px; color: white; font-weight: bold; background-color: rgb(0, 137, 216);'>2</span>
							Type your code in the LayOver app
						</span>
						<span style='display: flex; flex-direction: row; justify-content: flex-start; align-items: center'>
							<span style='height: 40px; width: 40px; text-align: center; line-height: 40px; vertical-align: middle; margin-right: 20px; margin-bottom: 5px; border: 1px solid white; border-radius: 50px; color: white; font-weight: bold; background-color: rgb(0, 137, 216);'>3</span>
							Press the ''Verify'' button in the app
						</span>
						<br />
						If the actual code doesn't work, ask for another verification code on the app.<br />
						<br />
						<br />
						See you soon !<br />
						<br />
						Sincerly yours,<br/>
						LayOver's father. <br /><br />
					</div>
					<div style='display: block; height: 75px; width: 800px; background-color: #b9b9b9; background: linear-gradient(175deg, rgb(0, 137, 216), rgb(68, 187, 255)); border-top: 1px solid #999; padding-top: 35px;'>
            
					</div>
				</div>
			</body>
		</html>";
		//==========
        
		//=====Définition du sujet.
		$sujet = "LayOver - [".$verificationCode."] Confirm your new account";
		//=========
	}
	if($emailType == 1){

		//=====Déclaration des messages au format texte et au format HTML.
		$message_txt = "Hello there !".$rn.$rn."

		We are glad to see you again on LayOver.".$rn."
		To connect to your account, follow these 3 Steps :".$rn.$rn."
    
		1- Copy your verification code :".$verificationCode.$rn."
		2- Type your code in the LayOver app".$rn."
		3- Press the ''Verify'' button in the app".$rn.$rn."
    
		If the actual code doesn't work, ask for another verification code on the app.".$rn.$rn.$rn."


		If you have not requested this connection, you can ignore this email. No one else can change your password through the link provided.".$rn.$rn.$rn."
    
		See you soon !".$rn.$rn."
	
		Sincerly yours,".$rn."
		LayOver's father. ".$rn.$rn;

		$message_html = "
		<html>    
			<head>
			</head>
			<body>
				<div style='width: 800px; box-shadow: 0px 0px 10px -2px black; border: 1px solid #777;'>
					<div style='display: block; text-align: center; line-height: 150px; vertical-align: middle; height: 150px; width: 800px; background-color: grey; background: linear-gradient(175deg, rgb(0, 137, 216), rgb(68, 187, 255)); border-bottom: 1px solid #999; color: white; font-size: 36px; font-weight: bold;'>LayOver</div>
					<div style='color: #222;background-color: #f3f3f3;padding-left: 70px;padding-right: 70px;padding-top: 30px;padding-bottom: 30px; width: 660px; box-shadow: 0px 0px 8px -3px black inset; color: black; font-family: arial; font-size: 20px; word-break: break-word;'>
						<p>Hello there !</p>
						<p>
							We are glad to see you again on LayOver.<br />
							To connect to your account, follow these 3 Steps :<br />
						</p>
						<span style='display: flex; flex-direction: row; justify-content: flex-start; align-items: center'>
							<span style='height: 40px; width: 40px; text-align: center; line-height: 40px; vertical-align: middle; margin-right: 20px; margin-bottom: 5px; border: 1px solid white; border-radius: 50px; color: white; font-weight: bold; background-color: rgb(0, 137, 216);'>1</span>
							Copy your verification code :<strong>".$verificationCode."</strong>
						</span>
						<span style='display: flex; flex-direction: row; justify-content: flex-start; align-items: center'>
							<span style='height: 40px; width: 40px; text-align: center; line-height: 40px; vertical-align: middle; margin-right: 20px; margin-bottom: 5px; border: 1px solid white; border-radius: 50px; color: white; font-weight: bold; background-color: rgb(0, 137, 216);'>2</span>
							Type your code in the LayOver app
						</span>
						<span style='display: flex; flex-direction: row; justify-content: flex-start; align-items: center'>
							<span style='height: 40px; width: 40px; text-align: center; line-height: 40px; vertical-align: middle; margin-right: 20px; margin-bottom: 5px; border: 1px solid white; border-radius: 50px; color: white; font-weight: bold; background-color: rgb(0, 137, 216);'>3</span>
							Press the ''Verify'' button in the app
						</span>
						<br />
						If the actual code doesn't work, ask for another verification code on the app.<br />
						<br />
						<br />
						If you have not requested this connection, you can ignore this email. No one else can change your password through the link provided.<br />
						<br />
						<br />
						See you soon !<br />
						<br />
						Sincerly yours,<br/>
						LayOver's father. <br /><br />
					</div>
					<div style='display: block; height: 75px; width: 800px; background-color: #b9b9b9; background: linear-gradient(175deg, rgb(0, 137, 216), rgb(68, 187, 255)); border-top: 1px solid #999; padding-top: 35px;'>
            
					</div>
				</div>
			</body>
		</html>";
		//==========
        
		//=====Définition du sujet.
		$sujet = "LayOver - [".$verificationCode."] Confirm your connection request";
		//=========
	}
	if($emailType == 2){

		//=====Déclaration des messages au format texte et au format HTML.
		$message_txt = "Hello there !".$rn.$rn."

		It's nice to see you again on LayOver.".$rn."
		It seems that you are about to change the linked device to your account or reinstalled LayOver. To do so, follow these 3 Steps :".$rn.$rn."
    
		1- Copy your verification code :".$verificationCode.$rn."
		2- Type your code in the LayOver app".$rn."
		3- Press the ''Verify'' button in the app".$rn.$rn."
    
		If the actual code doesn't work, ask for another verification code on the app.".$rn.$rn.$rn."


		If you have not requested this change, you can ignore this email. No one else can change your device through the link provided.".$rn.$rn.$rn."
    
		See you soon !".$rn.$rn."
	
		Sincerly yours,".$rn."
		LayOver's father. ".$rn.$rn;

		$message_html = "
		<html>    
			<head>
			</head>
			<body>
				<div style='width: 800px; box-shadow: 0px 0px 10px -2px black; border: 1px solid #777;'>
					<div style='display: block; text-align: center; line-height: 150px; vertical-align: middle; height: 150px; width: 800px; background-color: grey; background: linear-gradient(175deg, rgb(0, 137, 216), rgb(68, 187, 255)); border-bottom: 1px solid #999; color: white; font-size: 36px; font-weight: bold;'>LayOver</div>
					<div style='color: #222;background-color: #f3f3f3;padding-left: 70px;padding-right: 70px;padding-top: 30px;padding-bottom: 30px; width: 660px; box-shadow: 0px 0px 8px -3px black inset; color: black; font-family: arial; font-size: 20px; word-break: break-word;'>
						<p>Hi there !</p>
						<p>
							It's nice to see you again on LayOver.<br />
							It seems that you are about to change the linked device to your account or reinstalled LayOver. To do so, follow these 3 Steps :<br />
						</p>
						<span style='display: flex; flex-direction: row; justify-content: flex-start; align-items: center'>
							<span style='height: 40px; width: 40px; text-align: center; line-height: 40px; vertical-align: middle; margin-right: 20px; margin-bottom: 5px; border: 1px solid white; border-radius: 50px; color: white; font-weight: bold; background-color: rgb(0, 137, 216);'>1</span>
							Copy your verification code :<strong>".$verificationCode."</strong>
						</span>
						<span style='display: flex; flex-direction: row; justify-content: flex-start; align-items: center'>
							<span style='height: 40px; width: 40px; text-align: center; line-height: 40px; vertical-align: middle; margin-right: 20px; margin-bottom: 5px; border: 1px solid white; border-radius: 50px; color: white; font-weight: bold; background-color: rgb(0, 137, 216);'>2</span>
							Type your code in the LayOver app
						</span>
						<span style='display: flex; flex-direction: row; justify-content: flex-start; align-items: center'>
							<span style='height: 40px; width: 40px; text-align: center; line-height: 40px; vertical-align: middle; margin-right: 20px; margin-bottom: 5px; border: 1px solid white; border-radius: 50px; color: white; font-weight: bold; background-color: rgb(0, 137, 216);'>3</span>
							Press the ''Verify'' button in the app
						</span>
						<br />
						If the actual code doesn't work, ask for another verification code on the app.<br />
						<br />
						<br />
						If you have not requested this change, you can ignore this email. No one else can change your device through the link provided.<br />
						<br />
						<br />
						See you soon !<br />
						<br />
						Sincerly yours,<br/>
						LayOver's father. <br /><br />
					</div>
					<div style='display: block; height: 75px; width: 800px; background-color: #b9b9b9; background: linear-gradient(175deg, rgb(0, 137, 216), rgb(68, 187, 255)); border-top: 1px solid #999; padding-top: 35px;'>
            
					</div>
				</div>
			</body>
		</html>";
		//==========
        
		//=====Définition du sujet.
		$sujet = "LayOver - [".$verificationCode."] Confirm your new device";
		//=========
	}
	if($emailType == 3){

		//=====Déclaration des messages au format texte et au format HTML.
		$message_txt = "Hi again !".$rn.$rn."

		Your account has been successfully linked to your new device/app.".$rn.$rn."

		If you have not requested this change, you can still retrieve your account access BUT we regret to inform you that your email account access is COMPROMISE !!".$rn.$rn."
		We suggest that you change your email account password and try to connect back to LayOver to retrieve your account as soon as possible.".$rn.$rn.$rn."
    
		Hoping to see you soon !".$rn.$rn."
	
		Sincerly yours,".$rn."
		LayOver's father. ".$rn.$rn;

		$message_html = "
		<html>    
			<head>
			</head>
			<body>
				<div style='width: 800px; box-shadow: 0px 0px 10px -2px black; border: 1px solid #777;'>
					<div style='display: block; text-align: center; line-height: 150px; vertical-align: middle; height: 150px; width: 800px; background-color: grey; background: linear-gradient(175deg, rgb(0, 137, 216), rgb(68, 187, 255)); border-bottom: 1px solid #999; color: white; font-size: 36px; font-weight: bold;'>LayOver</div>
					<div style='color: #222;background-color: #f3f3f3;padding-left: 70px;padding-right: 70px;padding-top: 30px;padding-bottom: 30px; width: 660px; box-shadow: 0px 0px 8px -3px black inset; color: black; font-family: arial; font-size: 20px; word-break: break-word;'>
						<p>Hi again !</p>
						<p>
							Your account has been successfully linked to your new device/app.
						</p>
						If you have not requested this change, you can still retrieve your account access BUT we regret to inform you that <strong>your email account access is COMPROMISE !!</strong><br />
						<br />
						We suggest that you change your email account password and try to connect back to LayOver to retrieve your account as soon as possible.<br />
						<br />
						<br />
						Hoping to see you soon !<br />
						<br />
						Sincerly yours,<br/>
						LayOver's father. <br /><br />
					</div>
					<div style='display: block; height: 75px; width: 800px; background-color: #b9b9b9; background: linear-gradient(175deg, rgb(0, 137, 216), rgb(68, 187, 255)); border-top: 1px solid #999; padding-top: 35px;'>
            
					</div>
				</div>
			</body>
		</html>";
		//==========
        
		//=====Définition du sujet.
		$sujet = "LayOver - Account successfully linked";
		//=========
	}
        
	//=====Création de la boundary
	$boundary = "-----=".md5(rand());
	//==========
        
	//=====Création du header de l'e-mail.
	$header = "From: \"LayOver\"<no-reply.layover@cheyennevalmond.com>".$rn;
	$header.= "MIME-Version: 1.0".$rn;
	$header.= "Content-Type: multipart/alternative;".$rn." boundary=\"$boundary\"".$rn;
	//==========
    //=====Création du message.
	$message = $rn."--".$boundary.$rn;
	//=====Ajout du message au format texte.
	$message.= "Content-Type: text/plain; charset=\"UTF-8\"".$rn;
	$message.= "Content-Transfer-Encoding: 8bit".$rn;
	$message.= $rn.$message_txt.$rn;
	//==========
	$message.= $rn."--".$boundary.$rn;
	//=====Ajout du message au format HTML
	$message.= "Content-Type: text/html; charset=\"UTF-8\"".$rn;
	$message.= "Content-Transfer-Encoding: 8bit".$rn;
	$message.= $rn.$message_html.$rn;
	//==========
	$message.= $rn."--".$boundary."--".$rn;
	$message.= $rn."--".$boundary."--".$rn;
	//==========	

    //=====Envoi de l'e-mail.
    mail($email,$sujet,$message,$header);
    //==========
}

function genRandomConf($car) {
	$string = "";
	$confirm_key = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	srand((double)microtime()*1000000);
	for($i=0; $i<$car; $i++) {
	$string .= $confirm_key[rand()%strlen($confirm_key)];
	}
	return $string;
}

function genValidationCode($car) {
	$string = "";
	$confirm_key = "123456789";
	srand((double)microtime()*1000000);
	for($i=0; $i<$car; $i++) {
	$string .= $confirm_key[rand()%strlen($confirm_key)];
	}
	return $string;
}

/*$decrypted = false;
while(!$decrypted){
	define('AES_256_CBC', 'aes-256-cbc');
	$encryption_key = openssl_random_pseudo_bytes(32);
	$encryption_key_hex = bin2hex($encryption_key);
	$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(AES_256_CBC));
	$iv_hex = bin2hex($iv);
	$data = $_POST["password"];
	$encrypted = openssl_encrypt($data, AES_256_CBC, $encryption_key, 0, $iv);
	$decrypted = openssl_decrypt($encrypted, AES_256_CBC, $encryption_key, 0, $iv);
}*/

?>