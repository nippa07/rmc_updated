<?php

require ('../db_connect.php');

if (isset($_POST["bname"])) {
    $bname = $_POST["bname"];
    $city = $_POST["zip"];
        
    $sql = "SELECT *, users.id AS uID FROM users WHERE (bname LIKE '%$bname%' OR type LIKE '%$bname%') AND (zip = '$city' OR city = '$city') ORDER BY RAND();";

    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
        ?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Rate My Contractor is meant for homeowners who are looking to find reliable contractors to work on their homes. Here they can find contractor ratings on local contractors who will do the work for them. Rate My Contractor is a growing population of homeowner reviews. These local reviews will help owners find a high quality contractor for the job. ">
    <meta name="keywords" content="find a contractor, reliable contractors, contractor ratings, local contractors, contractor near me, local reviews">
  <link rel="shortcut icon" href="../resources/favicon.ico" type="image/x-icon">
  <link rel="icon" href="../resources/favicon.ico" type="image/x-icon">
  <title>Find A Contractor | Rate My Contractor </title>

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href=https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css>
        <script src= https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js></script>
        <script src= https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

  <!-- Custom styles for this template -->
  <!-- CSS here -->
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/slicknav.css">
        <link rel="stylesheet" href="../assets/css/slick.css">
        <link rel="stylesheet" href="../assets/css/nice-select.css">
        <link rel="stylesheet" href="../assets/css/style.css">
  <link href="find.css" rel="stylesheet">

</head>

<body>

 <?php include "menu.php" ?>
<br>



<h3> </h3>

<?php while ($row = $result->fetch_assoc()) { ?>
    <div class="container">
        <div class="row">
            <div class="col-3">
               <img id="img" src="<?php echo '../biz/update/images/' . $row['profile_image']; ?>" width="266" height="186" alt="Contractor Profile"> 
            </div>
            <div id="mid" class="col-6 text-left">
                <a style="color: blue;" href="profileviewer.php?uID=<?=$row["uID"]; ?>"><?=$row["bname"]; ?></a><br>
                <div><?=$row["city"]; ?>, <?=$row["state"]; ?> <?=$row["zip"]; ?></div>
                <div><p>Description: <?=$row["bio"]; ?></p></div><br><br>
                <div>Categories: <?=$row["type"]; ?></div>
                <div><a style="color: blue;" href="#">Review This Contractor |</a><a style="color: blue;" href="#"> Get A Quote</a></div>
            </div>
            <div id="right" class="col-3">
                <?php

               try{$db = new PDO('mysql:host='.$dBServername.';dbname='.$dBName.';charset=utf8mb4', ''.$dBUsername.'', ''.$dBPassword) or die(print_r($db->errorInfo())); }
               catch (Exception $e){die('Erreur : ' . $e->getMessage());}
               
               $statement = $db->prepare('SELECT ROUND(AVG(overall),1) AS averageRating, COUNT(overall) AS totalRatings FROM review WHERE bizID = :bizID');

               $statement->bindParam(':bizID', $row['uID'], PDO::PARAM_INT);

               $statement->execute();

               $results = $statement->fetchAll();


                $averageRating = $results[0]["averageRating"];

                $totalRatings = $results[0]["totalRatings"];

   

                ?>

                <div style="height: 7rem; width: 8rem; padding-top: 0.7rem;" id="section" class="section text text-center">
                <div class="avg">
                    <h6>Overall Rating</h6>
                   <h6><?php echo "$averageRating"; ?></h6>
                </div>
                <hr/>
                <div class="total">
                <h6>Total Reviews</h6>
                    <h6><?php echo "$totalRatings"; ?></h6>  
                </div>
                </div>
                <br>                
                <?php if ($row['claimed']==0) {?>
                    <h5 class="text text-center">Is This Your Business?</h5>
                    <a style="text-align: center; width: 12.5rem; margin-left: auto; margin-right: auto;" class="btn-primary btn-sm btn-block" href="../biz/claimsignup.php?uID=<?=$row["uID"];?>">Claim Your Profile</a>
                <?php } ?>
            </div>
        </div>
    </div>
             <hr/>
             
        
<?php
       }
       
       
    }
}





?>
<br>
<br>
<br>
<?php include 'footer.php'; ?>

</body>
</html>