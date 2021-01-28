<?php
if(session_status() == PHP_SESSION_NONE){session_start();}

require("db_config.php");
global $db;

$err = false;

if(!isset($_GET["todo"])){
	$err = array('err' => '404;parameter_missing:[todo]');
	$output = json_encode($err);
	echo $output;
}
else if($_GET["todo"] == "test"){

}
else if($_GET["todo"] == "searchContractor"){
    if(isset($_GET["param"]) and $_GET["param"] != ""){

        $sql = 'SELECT id, bname FROM users WHERE bname LIKE :searchParam ORDER BY bname ASC LIMIT 3';
        $searchParam = "%$_GET[param]%";

        $statement = $db->prepare($sql);
        $statement->bindParam(':searchParam', $searchParam, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll();
        $output = json_encode($result);
        echo $output;

    }
    else{
        $err = array('err' => '404;parameter_missing:[todo]');
        $output = json_encode($err);
        echo $output;
    }
}
else if($_GET["todo"] == "submitReview"){    
    if(isset($_SESSION["uidUsers"]) and $_SESSION["uidUsers"] != ""){
        // Check if user exists in DB
        $statement = $db->prepare('SELECT id FROM leads WHERE uidUsers = :homeUserID');
        $statement->bindParam(':homeUserID', $_SESSION["uidUsers"], PDO::PARAM_STR);
        $statement->execute();
        $leadID = $statement->fetchColumn();

        if(isset($leadID) and $leadID != null){	
            
            $statement = $db->prepare('SELECT COUNT(id) FROM reviews WHERE leadID = :leadID');
            $statement->bindParam(':leadID', $leadID, PDO::PARAM_STR);
            $statement->execute();
            $alreadyReviewed = $statement->fetchColumn();

            if(isset($alreadyReviewed) and $alreadyReviewed = 1){	
                if(isset($_GET["bizID"]) and isset($_GET["rating"]) and isset($_GET["comment"]) and $_GET["bizID"] != "" and $_GET["rating"] != "" and $_GET["comment"] != ""){
            

                    $decodedComment = $message = urldecode($_GET["comment"]);
            
                    $statement = $db->prepare("INSERT INTO reviews (id, leadID, userID, rating, comment) VALUES (null, :leadID, :userID, :rating, :comment)");
                    $statement->bindParam(':leadID', $leadID, PDO::PARAM_INT);
                    $statement->bindParam(':userID', $_GET["bizID"], PDO::PARAM_INT);
                    $statement->bindParam(':rating', $_GET["rating"], PDO::PARAM_INT);
                    $statement->bindParam(':comment', $decodedComment, PDO::PARAM_STR);
                    $statement->execute();
            
                    $result = "review Successfully Submitted";
                    $output = json_encode($result);
                    echo $output;
            
                }
                else{
                    $err = array('err' => '404;parameter_missing:[bizID,rating,comment]');
                    $output = json_encode($err);
                    echo $output;
                }
            }
            else{
                $err = array('err' => 'contractorAlreadyReviewed');
                    $output = json_encode($err);
                    echo $output;
            }
        }
        else{
            $err = array('err' => '404;userNotFoundInDB:$_SESSION["uidUsers"]'.$_SESSION["uidUsers"]);
                $output = json_encode($err);
                echo $output;
        }
    }
    else{
        $err = array('err' => '404;parameter_missing:$_SESSION["uidUsers"]');
            $output = json_encode($err);
            echo $output;
    }    
}


?>