<?php if(session_status() == PHP_SESSION_NONE){session_start();}?>

<?php require("php/db_config.php");?>

    <div id="reviewContent">
        <?php
            if (!isset($_SESSION['uidUsers'])) {
            }

        ?>
        <div id="reviewListDiv">
            <?php            
                
                if(isset($_GET["uID"])){
                    $userID = $_GET["uID"];
                }
                else{
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
                }               

                if(isset($_GET["uID"])){
                    $sql2 = 'SELECT reviews.id, leadID, userID, rating, reviews.comment, reviews.postdate, bname, leads.uidUsers, replies.comment AS replyComment, replies.postdate AS replyPostdate FROM reviews LEFT JOIN leads ON reviews.leadID = leads.id LEFT JOIN replies ON replies.reviewID = reviews.id LEFT JOIN users ON reviews.userID = users.id WHERE userID = :userID ORDER BY postdate DESC';
                }
                else{
                    if($_SESSION["userStatus"] == "lead"){
                        $sql2 = 'SELECT reviews.id, leadID, userID, rating, reviews.comment, reviews.postdate, bname, replies.comment AS replyComment, replies.postdate AS replyPostdate FROM reviews LEFT JOIN users ON reviews.userID = users.id LEFT JOIN replies ON replies.reviewID = reviews.id WHERE leadID = :userID ORDER BY postdate DESC';
                    }
                    else if($_SESSION["userStatus"] == "contractor"){
                        $sql2 = 'SELECT reviews.id, leadID, userID, rating, reviews.comment, reviews.postdate, uidUsers, replies.comment AS replyComment, replies.postdate AS replyPostdate FROM reviews LEFT JOIN leads ON reviews.leadID = leads.id LEFT JOIN replies ON replies.reviewID = reviews.id WHERE userID = :userID ORDER BY postdate DESC';
                    }
                }                
        
                $statement = $db->prepare($sql2);
                $statement->bindParam(':userID', $userID, PDO::PARAM_INT);
                $statement->execute();
        
                $result = $statement->fetchAll();

                for($i = 0; $i < count($result); $i++){
            ?>
                <div id="reviewDiv" class="reviewDiv">
                    <span class="reviewTopSection">
                        <span class="reviewTitle">
                            <?php 
                                if(isset($_GET["uID"])){
                                    echo $result[$i]['uidUsers'];
                                }
                                else{
                                    if($_SESSION["userStatus"] == "lead"){
                                        echo $result[$i]['bname'];
                                    }
                                    else if($_SESSION["userStatus"] == "contractor"){
                                        echo $result[$i]['uidUsers'];
                                    }
                                }
                            ?>                    
                        </span>
                        <span class="reviewDate"><?= $result[$i]['postdate']?></span>
                        <span class="reviewRate">
                            <?php
                                for( $j = 1; $j <= 5; $j++){
                                    if($j <= $result[$i]['rating']){
                                        echo '<img src="../modules/review/img/starOn1.png" class="reviewStarList"/>';
                                    }
                                    else{
                                        echo '<img src="../modules/review/img/starOff1.png" class="reviewStarList"/>';
                                    }
                                }
                            ?>
                        </span>
                    </span>
                    <span class="reviewContentText"><?= $result[$i]['comment']?></span>
                </div>
                <?php
                    if($result[$i]['replyComment'] != NULL){ 
                ?>
                    <div id="replyDiv<?= $result[$i]['id'];?>" class="replyDiv">
                    <span class="reviewTopSection">
                        <span class="reviewTitle">
                            <?php 
                                echo $result[$i]['bname'];
                            ?>                    
                        </span>
                        <span class="replyDate"><?= $result[$i]['replyPostdate']?></span>
                    </span>
                        <span class="reviewContentText"><?= $result[$i]['replyComment']?></span>
                        
                    </div>
                <?php } else{ 
                    if(isset($_GET["uID"])) {
                        echo 'Please login as to your business account to reply!';
                    }elseif ($_SESSION["userStatus"] == "contractor"){ ?>
                        <div id="replyDiv<?= $result[$i]['id'];?>" class="replyDiv">
                            <textarea id="reviewCommentTextarea<?= $result[$i]['id'];?>" class="replyCommentTextarea" placeholder="REPLY HERE..." onfocus="this.style.height = '200px';this.style.zIndex = '1'; this.style.paddingBottom = '42px';" onfocusout="if(this.value == ''){this.style.height = '66px';this.style.zIndex = '3'; this.style.paddingBottom = '20px';}" onkeyup="reviewMaxCharsCount(<?= $result[$i]['id'];?>);" maxlength="512"></textarea>
                            <span id="reviewCommentMaxCounter<?= $result[$i]['id'];?>" class="reviewCommentMaxCounter">512</span>
                            <span class="replySendBtn" onclick="checkReply(<?= $result[$i]['id'];?>);">Reply</span>
                            <input type="hidden" id="replyReviewID<?= $result[$i]['id'];?>" value="<?= $result[$i]['id']?>"/>
                        </div>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </div>
    </div>