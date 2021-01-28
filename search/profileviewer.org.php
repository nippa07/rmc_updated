<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
require '../db_connect.php';

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"> 
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
		<title>Rate My Contractor | Contractor Profile</title>
		<meta name="keywords" content="Contractor, local reviews, reliable contractors, review website, rate">
		<meta name="description" content="Rate My Contractor provides quality local reviews for homeowners to view. Its a review website that allows homeowners to rate their contractors. Most homeowners normally find reliable contractors through our review website.">
		<link rel="shortcut icon" href="../resources/favicon.ico" type="image/x-icon">
        <link rel="icon" href="../resources/favicon.ico" type="image/x-icon">
		<link href="profileviewer.css" rel="stylesheet" type="text/css">
		<link href="../biz/style2.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="../modules/review/css/default.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link rel="stylesheet" href=https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css>
        <script src= https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js></script>
        <script src= https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		
			<!-- CSS here -->
			<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/slicknav.css">
        <link rel="stylesheet" href="../assets/css/slick.css">
        <link rel="stylesheet" href="../assets/css/nice-select.css">
        <link rel="stylesheet" href="../assets/css/style.css">


	</head>
	<body>
		
	
        <?php include "menu.php"; ?>

<?php
    
	$stmt = $conn->prepare("SELECT * FROM users WHERE id = '".$_GET['uID']."'");
	$stmt->execute();
	$result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
?>
<section id="body">
        <div class="container">
			<div class="row">
				<div class="col">
				    <br>
					<h1 class="text-center"> <?=$row['bname']?> </h1>
				</div>
			</div>
        </div>
		<div class="container">
			<div class="row">
				<div class="col-xl-3">
					<h1>   </h1>
					<img id="imgpro" src="<?php echo '../biz/update/images/' . $row['profile_image'] ?>" width="275" height="350" alt="">					
				</div>
				<div class="col-xl-5">
					<br>
					<br>
					<br>
					<h3> Owner: <?=$row['fname']?> <?=$row['lname']?></h3>
					<br>
					<br>
				    <h3>About Us:</h3>
					<?=$row['bio'] ?>
				</div>
				<div class="col-xl-2 text-left">
					<br>
					<br>
					<h3>Location:</h3>
					    <h5>City:</h5>
							<h4><?=$row['city'] ?><h4><br>
						<h5>State:</h5>
						    <h4><?=$row['state'] ?></h4><br>
						<h5>Zip Code:</h5>
					        <h4><?=$row['zip'] ?></h4>		    
				</div>
				<div class="col-xl-2">
					<br>
					<br>
					<h3>Contact:</h3>
					    <h5>Email:</h5>
						<h4><?=$row['emailUsers'] ?></h4><br>
				
						<h5>Phone:</h5>
						<h4><?=$row['phone'] ?></h4><br>
						
						<h5>Website:</h5>
						<h4><?=$row['web'] ?></h4>
				</div>			
			</div>
		</div>
    <?php if ($row['paying']==1) { ?>
        
    
    <main>
      <section class="container gallery-links">
          <h1>Gallery</h1>

          <div class="gallery-container">
              <?php

            $sql = "SELECT * FROM gallery WHERE userID= ? ORDER BY orderGallery DESC";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              echo "SQL statement failed!";
            } else {
                mysqli_stmt_bind_param($stmt, 's', $row['uidUsers']);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);
            }
              while ($row = mysqli_fetch_assoc($result)) {
                  ?>
        
                    <div id="parent" class="d-inline-flex"  style="margin: 1em auto">
                    <a href="#">
                  <div id="child" style="background-image: url(../biz/gallery/<?=$row["imgFullNameGallery"];?>);"></div>
                  <h3><?=$row["titleGallery"];?></h3>
                </a>
                    </div>
           <?php  }  ?>
          </div>
      </section>
    </main>
    <div class="container">
        <h1>Reviews</h1>
    </div>
	<div class="container">
		<?php include "../modules/review/reviewList.php" ?>
		<?php } ?>
	</div>
	<br>
	<br>

  <?php include "footer.php";?>
</section>
</body>
</html>