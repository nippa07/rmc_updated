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
    
	if ($stmt = $conn->prepare("SELECT * FROM users WHERE id = '".$_GET['uID']."'"));{
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc(); ?>

<section id="body">
        <div class="container">
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
					<img style="margin-top: -5em;" src="<?php echo '../biz/update/images/' . $row['profile_image'] ?>" width="256" height="300" alt="">					
				</div>
				<div style="height: 200;" class="col-xl-9">
					<br>
					<br>
				    <h3>Description:</h3>
					<?=$row['bio'] ?>
				</div>
			</div>
			<div style="margin-top: -2em;" class="container ">
				<div class="row">
					<div class="col-2 text-justify">
						<h4>Location:</h4>
					</div>				
				</div>
					<div style="font-size: 1.25em;" class="col-2 d-inline">
							
						<?=$row['city'] ?>,
						
						<?=$row['state'] ?>
						
					    <?=$row['zip'] ?>
					</div>
				</div>
			</div>		    
				</div>
							
			</div>
		</div>
	<?php } ?>
		<br>
		<hr>
		<br>
		<?php
    
	$stmt = "SELECT overall AS overall, professionalism AS pro, quality AS quality, communication AS com, cleanliness AS clean, price AS price, COUNT(price) AS total FROM review WHERE bizID = $_GET[uID]";
        //$stmt->execute();
        $result = $conn->query($stmt);
        while ($row = $result->fetch_array(MYSQLI_BOTH)) {
			
			print_r($row);
    
            $overall = $row[0];
            $pro = $row[1];
            $quality = $row[2];
            $com = $row[3];
            $clean = $row[4];
            $price = $row[5];
            $total = $row[6];

            $overall = $overall/$total;
    
			$avgPro = $pro/$total;
    
            $avgQuality = $quality/$total;

            $avgCom = $com/$total;

            $avgClean = $clean/$total;

			$avgPrice = $price/$total;
			
			?>
		<div class="container">
			<div class="row" style="height: 2rem;" >
				<div class="col-4">
		    		<h2>Overall Grade:</h2>
					<?=$overall; ?>
				</div>
				<div class="col-8" style= "height: 10rem;" >
		    		<h2>Reviews</h2>
				</div>
			<div class="row" style="height: 15rem;">
				<div class="col-4">
					<div>
					<h4>Professionalism:</h4>
					<?=$avgPro; ?>
					</div>
					<div>
					<h4>Quality:</h4>
					<?=$avgQuality; ?>
					</div>
					<div>
					<h4>Communication:</h4>
					<?=$avgCom; ?>
					</div>
					<div>
					<h4>Cleanliness:</h4>
					<?=$avgClean; ?>
					</div>
					<div>
					<h4>Price:</h4>
					<?=$avgPrice; ?>
					</div>
				</div>
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
		<br>
		<?php } ?>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
        <?php if ($row['paying']==0) { ?>
    
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
                      <div id="child" style="background-image: url(../biz/gallery/<?=$row["imgFullNameGallery"]; ?>);"></div>
                      <h3><?=$row["titleGallery"]; ?></h3>
                    </a>
                        </div>
			   <?php
                  }
            
                }
			    ?>
            
          </section>
        </main>
        <?php ?>
	<br>
	<br>

  <?php include "footer.php";?>
</section>
</body>
</html>