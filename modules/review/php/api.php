<?php
if(session_status() == PHP_SESSION_NONE){session_start();}

require("db_config.php");
global $db;

$err = false;
$currentDate = date("Y-m-d");

if(!isset($_GET["todo"])){
	$err = array('err' => '404;parameter_missing:[todo]');
	$output = json_encode($err);
	echo $output;
}
else if($_GET["todo"] == "test"){

}
else if($_GET["todo"] == "searchContractor"){
    if(isset($_GET["param"]) and $_GET["param"] != ""){

        $sql = 'SELECT id, bname FROM users WHERE bname LIKE :searchParam ORDER BY bname ASC LIMIT 5';
        $searchParam = "%". urldecode($_GET['param']) . "%";

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
else if($_GET["todo"] == "addContractor"){    
    if(isset($_SESSION["uidUsers"]) and $_SESSION["uidUsers"] != ""){
        // Check if user exists in DB
        if($_SESSION["userStatus"] == "lead"){
            $sql1 = 'SELECT id FROM leads WHERE uidUsers = :UserID';
        }
        else if($_SESSION["userStatus"] == "contractor"){
            $sql1 = 'SELECT id FROM users WHERE uidUsers = :UserID';
        }
        // Check if user exists in DB
        $statement = $db->prepare($sql1);
        $statement->bindParam(':UserID', $_SESSION["uidUsers"], PDO::PARAM_STR);
        $statement->execute();
        $userID = $statement->fetchColumn();

        if(isset($userID) and $userID != null){	
            if(isset($_GET["cPhone"]) and isset($_GET["cName"]) and isset($_GET["cZip"]) and $_GET["cPhone"] != "" and $_GET["cName"] != "" and $_GET["cZip"] != ""){
                    
                $decodedPhone = urldecode($_GET["cPhone"]);
                $decodedName = strtoupper(urldecode($_GET["cName"]));
                $decodedZip = urldecode($_GET["cZip"]);
        
                $statement = $db->prepare("INSERT INTO users (id, bname, phone, zip, userAdd) VALUES (null, :cName, :cPhone, :cZip, 1)");
                $statement->bindParam(':cName', $decodedName, PDO::PARAM_STR);
                $statement->bindParam(':cPhone', $decodedPhone, PDO::PARAM_STR);
                $statement->bindParam(':cZip', $decodedZip, PDO::PARAM_STR);
                $statement->execute();
                
                $last_id = $db->lastInsertId();

                $output = $last_id;
                // $output = json_encode($result);
                echo $output;
        
            }
            else{
                $err = array('err' => '404;parameter_missing:[cPhone,cName,cZip]');
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
else if($_GET["todo"] == "submitReview"){    
    if(isset($_SESSION["uidUsers"]) and $_SESSION["uidUsers"] != ""){
        // Check if user exists in DB
        $statement = $db->prepare('SELECT id FROM leads WHERE uidUsers = :homeUserID');
        $statement->bindParam(':homeUserID', $_SESSION["uidUsers"], PDO::PARAM_STR);
        $statement->execute();
        $leadID = $statement->fetchColumn();

        if(isset($leadID) and $leadID != null){	
            
            $statement = $db->prepare('SELECT COUNT(id) FROM reviews WHERE leadID = :leadID AND userID = :userID');
            $statement->bindParam(':leadID', $leadID, PDO::PARAM_STR);
            $statement->bindParam(':userID', $_GET["bizID"], PDO::PARAM_STR);
            $statement->execute();
            $alreadyReviewed = $statement->fetchColumn();

            if(isset($alreadyReviewed) and $alreadyReviewed != 1){	
                if(isset($_GET["bizID"]) and isset($_GET["rating"]) and isset($_GET["comment"]) and $_GET["bizID"] != "" and $_GET["rating"] != "" and $_GET["comment"] != ""){
            

                    $decodedComment = $message = urldecode($_GET["comment"]);
            
                    $statement = $db->prepare("INSERT INTO reviews (id, leadID, userID, rating, comment, postdate) VALUES (null, :leadID, :userID, :rating, :comment, '".$currentDate."')");
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
else if($_GET["todo"] == "submitReply"){    
    if(isset($_SESSION["userStatus"]) and $_SESSION["userStatus"] == "contractor"){
        if(isset($_SESSION["uidUsers"]) and $_SESSION["uidUsers"] != ""){
            // Check if user exists in DB
            $statement = $db->prepare('SELECT id FROM users WHERE uidUsers = :userID');
            $statement->bindParam(':userID', $_SESSION["uidUsers"], PDO::PARAM_STR);
            $statement->execute();
            $userID = $statement->fetchColumn();

            if(isset($userID) and $userID != null){	
                $statement = $db->prepare('SELECT userID FROM reviews WHERE id = :reviewID');
                $statement->bindParam(':reviewID', $_GET["reviewID"], PDO::PARAM_INT);
                $statement->execute();
                $reviewUserID = $statement->fetchColumn();

                //Check if current review to reply belongs to user logged in
                if(isset($reviewUserID) and $reviewUserID == $userID){	
                    
                    $statement = $db->prepare('SELECT id FROM replies WHERE reviewID = :reviewID');
                    $statement->bindParam(':reviewID', $_GET["reviewID"], PDO::PARAM_INT);
                    $statement->execute();
                    $reviewReply = $statement->fetchColumn();
                    
                    //Check if review already has a reply
                    if(isset($reviewReply) and $reviewReply == null){	
                        if(isset($_GET["comment"]) and isset($_GET["reviewID"]) and $_GET["comment"] != "" and $_GET["reviewID"] != ""){                

                            $decodedComment = $message = urldecode($_GET["comment"]);
                    
                            $statement = $db->prepare("INSERT INTO replies (ID, reviewID, comment, postdate) VALUES (null, :reviewID, :comment, '".$currentDate."')");
                            $statement->bindParam(':reviewID', $_GET["reviewID"], PDO::PARAM_INT);
                            $statement->bindParam(':comment', $decodedComment, PDO::PARAM_STR);
                            $statement->execute();
                    
                            $result = "review Successfully Submitted";
                            $output = json_encode($result);
                            echo $output;
                    
                        }
                        else{
                            $err = array('err' => '404;parameter_missing:[reviewID,comment]');
                            $output = json_encode($err);
                            echo $output;
                        }
                    }
                    else{
                        $err = array('err' => 'already_replied');
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
    else{
        $err = array('err' => '404;wrong_parameter_or_missing:$_SESSION["userStatus"]');
            $output = json_encode($err);
            echo $output;
    }   
}


?>