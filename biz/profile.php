<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['uidUsers'])) {
	header('Location: bizsignin.php');
	exit();
}
require '../db_connect.php'; 

$uid = $_SESSION['uidUsers'];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Rate My Contractor | Profile</title>
		<link rel="stylesheet" href="../modules/review/css/default.css" />
		<link rel="shortcut icon" href="../resources/favicon.ico" type="image/x-icon">
        <link rel="icon" href="../resources/favicon.ico" type="image/x-icon">
		<link href="homestyle.css" rel="stylesheet" type="text/css">
		<link href="style2.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link rel="stylesheet" href=https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css>
        <script src= https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js></script>
        <script src= https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<link href="../css/biz.css" rel="stylesheet">
	<?php 
			//$GLOBALS["modulesPath"] = "../modules/review/";
			//include("../modulesInclude.php");
		?>
	</head>
	<body class="loggedin">
		
	<?php include 'menu.php'; ?>

<?php
    
	$stmt = $conn->prepare("SELECT * FROM users WHERE uidUsers = '$uid'");
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	
?>
<section id="body">
        <div class="container">
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<div class="row">
				<div class="col-12">
					<h1 class="text-center"> <?=$row['bname']?> </h1>
				</div>
			</div>
        </div>
		<br>
		<div style="margin-bottom: -1em;" class="container">
				<div class="row">
				<div class="col-xl-3">
				</div>
					<div class="d-inline col-3">
					    <h5>Email:</h5>
						<?=$row['emailUsers'] ?>
				</div>
				<div class="d-inline col-3">
						<h5>Phone:</h5>
						<?=$row['phone'] ?>
						</div>
						<div class="d-inline col-3">	
						<h5>Website:</h5>
						<?=$row['web'] ?>
						</div>
					</div>
				</div>
				</div>
				</div>
		<div class="container">
			<div style="margin-top: -2em;" class="row">
				<div class="col-xl-3">
					<h1>   </h1>
					<img style="margin-top: -5em;" src="<?php echo './update/images/' . $row['profile_image'] ?>" width="256" height="300" alt="">					
				</div>
				<div style="height: 200;" class="col-xl-9">
					<br>
					<br>
					<!-- <h3> Owner: <?=$row['fname']?> <?=$row['lname']?></h3> -->
				    <h3>Description:</h3>
					<?=$row['bio'] ?>
				</div>
				

					<div class="container">
			<div class="row">
			    <div class="col-xl-3 mt-4">
				    <a  href="update/update.php"><button class="btn btn-primary btn-block text-uppercase" >Update Profile</button></a>
				</div>
			</div>
			<div class="col-xl-9">
			</div>
					
		
				<div class="container">
				<div class="row">
				<div class="col-xl-12">
					<!--<h3>Location:</h3> -->
					    <h4 >Location:</h4>
							<div style="font-size: 1.5em;" class="col-2 d-inline"><?=$row['city'] ?>,
						
						    <?=$row['state'] ?>
						
					        <?=$row['zip'] ?>
							</div>
							</div>
				</div>		    
				</div>
							
			</div>
		</div>
		<br>
		<hr>
		<br>
		<div class="container">
		<div style="height: 5em;" class="row">
		<div style="border: 2em; height: 10em;" class="col-4">
		    <h2>Overall Grade:</h2>
		</div>
		<div style= "height: 10em;" class="col-8">
		    <h2>Reviews</h2>
		</div>
		</div>
		</div>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>

          


	
		
	</div>
	<main>
      <section class="container gallery-links">
      <?php
      
      $sql = "SELECT * FROM users WHERE uidUsers= '$uid'";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  echo "SQL statement failed!";
} else {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_all($result, MYSQLI_ASSOC)) {
        if ($row[0]['paying'] == 1) {
            echo '<h3 style= "color: red";>Want to upload images of your GREAT work? <br> Want to reply to Positive and Negative client reviews? <br> Become a Paying CUSTOMERS Today and GAIN full ACCESS to your profile features.</h3>';
            exit();
        } elseif ($row[0]['paying']==0) {
            ?>
      
          <h1>Gallery</h1>

          <div class="gallery-container">
              <?php
              include "../db_connect.php";

            $sql = "SELECT * FROM gallery WHERE userID= '$uid' ORDER BY orderGallery DESC";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                echo "SQL statement failed!";
            } else {
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="d-inline-flex"  style="margin: 1em auto">
                    <a href="#">
                  <div style="background-image: url(./gallery/<?=$row["imgFullNameGallery"];?>);"></div>
                  <h3><?=$row["titleGallery"];?></h3>
                </a>
                    </div>
           <?php   }
            } ?>
          </div>
<?php

  
$sql = "SELECT * FROM gallery WHERE userID= '$uid'";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                echo "SQL statement failed!";
            } else {
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $rowCount = mysqli_num_rows($result);
                if (8 == $rowCount) {
                    echo '<h1>You have reached your Image Limit</h1>';
                    exit();
                } else {
                    if (!isset($_SESSION['uidUsers'])) {
                        echo '';
                        exit();
                    } else { ?>
          <div class="gallery-upload">
            <form action="gallery.inc.php" method="POST" enctype="multipart/form-data">
                <input class="form-control-lg" type="text" name="filename" placeholder="File Name...">
                <input class="form-control-lg" type="text" name="filetitle" placeholder="Image Title...">
                <input class="form-control-lg" type="file" name="file">
                <button class="btn-primary btn-lg" type="submit" name="submit">UPLOAD</button>
            </form>
		  </div>
		  <!-- <section class="container"> -->
          <!-- <h1>Reviews</h1> -->
<?php }
                }
            }
        }
        //include "../modules/review/reviewList.php";}
    }
}
?>

        </div>
      </section>
      </section>
    </main>
	</body>
	<br>

	<br>
	<br>
	<br>
	<br>
	<br>

  <?php include 'footer.php';?>
</section>
</html>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
