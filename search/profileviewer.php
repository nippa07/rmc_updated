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


	<!-- <link rel="stylesheet" href="../modules/review/css/default.css"> -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	<link rel="stylesheet" href=https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css>
	<script src=https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js></script>
	<script src=https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

	<!-- CSS here -->
	<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/slicknav.css">
	<link rel="stylesheet" href="../assets/css/slick.css">
	<link rel="stylesheet" href="../assets/css/nice-select.css">
	<link rel="stylesheet" href="../assets/css/style.css">
	<link href="../biz/style2.css" rel="stylesheet" type="text/css">
	<link href="profileviewer.css" rel="stylesheet" type="text/css">
</head>

<body>


	<?php include "menu.php"; ?>

	<?php

	$stmt = $conn->prepare("SELECT * FROM users WHERE id = '" . $_GET['uID'] . "'");
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	$uID = $row['uidUsers'];

	?>

	<section id="body">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h1 class="text-center"> <?= $row['bname'] ?> </h1>
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
					<?= $row['emailUsers'] ?>
				</div>
				<div class="d-inline col-3">
					<h5>Phone:</h5>
					<?= $row['phone'] ?>
				</div>
				<div class="d-inline col-3">
					<h5>Website:</h5>
					<?= $row['web'] ?>
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
					<?= $row['bio'] ?>
				</div>
			</div>
			<div style="margin-top: -2em;" class="container ">
				<div class="row">
					<div class="col-2 text-justify">
						<h4>Location:</h4>
					</div>
				</div>
				<div style="font-size: 1.25em;" class="col-2 d-inline">

					<?= $row['city'] ?>,

					<?= $row['state'] ?>

					<?= $row['zip'] ?>
				</div>
			</div>
		</div>
		<br>
		<hr>

		<?php
		$query = "SELECT ROUND(AVG(overall),1) AS overall, ROUND(AVG(professionalism),1) AS pro, ROUND(AVG(quality),1) AS quality, ROUND(AVG(communication),1) AS comm, ROUND(AVG(cleanliness),1) AS clean, ROUND(AVG(price),1) AS price FROM review WHERE bizID = ?";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $query)) {
			echo "SQL statement failed!";
		} else {
			mysqli_stmt_bind_param($stmt, 's', $_GET['uID']);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
		}
		while ($row = mysqli_fetch_assoc($result)) {
			//print_r($row);

			$overall = $row['overall'];

			$avgPro = $row['pro'];

			$avgQuality = $row['quality'];

			$avgCom = $row['comm'];

			$avgClean = $row['clean'];

			$avgPrice = $row['price'];
		}

		?>

		<div class="container">
			<div class="row flex-row" style="height: 2rem;">
				<div class="col-9">
					<h3>Reviews</h3>
				</div>
				<div class="col-3 d-flex align-items-end flex-column">
					<div id="overall" class="text-justify">
						<h4>Overall Grade:</h4>
						<h3 style="text-align: center; margin-left: 0em;"><?= $overall; ?></h3>
						<br>
						<div class="text-justify">
							<h4>Professionalism:</h4>
							<h3 style="text-align: center; margin-left: 0em;"><?= $avgPro; ?></h3>

							<h4>Quality:</h4>
							<h3 style="text-align: center; margin-left: 0em;"><?= $avgQuality; ?></h3>

							<h4>Communication:</h4>
							<h3 style="text-align: center; margin-left: 0em;"><?= $avgCom; ?></h3>

							<h4>Cleanliness:</h4>
							<h3 style="text-align: center; margin-left: 0em;"><?= $avgClean; ?></h3>

							<h4>Price:</h4>
							<h3 style="text-align: center; margin-left: 0em;"><?= $avgPrice; ?></h3>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br>
		<?php

		$query = "SELECT * FROM review WHERE bizID = ?";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $query)) {
			echo "SQL statement failed!";
		} else {
			mysqli_stmt_bind_param($stmt, 's', $_GET['uID']);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
		}
		while ($row = mysqli_fetch_assoc($result)) {

		?>

			<div class="container">
				<div class="row flex-row">
					<div class="col-9">
						<h4 style="float: left;">"<?= $row['review']; ?>"</h4>
						<h5 style="float: right;"><?= $row['datetime']; ?></h5>
					</div>
					<div class="col-3">
						<h3> </h3>
					</div>
				</div>
				<div class="row flex-row">

					<div style="margin-top: 0.3em;" class="col-9">
						<h5 style="float: right;"><?= $row['uidClient']; ?></h5>
						<div class="col-3">
							<h3> </h3>
						</div>
						<hr>
					</div>
				</div>
			</div>


		<?php } ?>

		<hr>

		<?php   //php if ($row['paying']==1) {
		?>


		<main>
			<div class="container">
				<div class="row flex-row d-flex justify-content-between">
					<section class="col-8 gallery-links">
						<h1>Gallery</h1>

						<div class="gallery-container">
							<?php

							$sql = "SELECT * FROM gallery WHERE userID= ? ORDER BY orderGallery DESC";
							$stmt = mysqli_stmt_init($conn);
							if (!mysqli_stmt_prepare($stmt, $sql)) {
								echo "SQL statement failed!";
							} else {
								mysqli_stmt_bind_param($stmt, 's', $uID);
								mysqli_stmt_execute($stmt);
								$result = mysqli_stmt_get_result($stmt);
							}
							while ($row = mysqli_fetch_assoc($result)) {

							?>

								<div id="parent" class="d-inline-flex">
									<a href="#">
										<div id="child" style="background-image: url(../biz/gallery/<?= $row["imgFullNameGallery"]; ?>);"></div>
										<h3><?= $row["titleGallery"]; ?></h3>
									</a>
								</div>

							<?php    }    ?>

							<?php

							$stmt = $conn->prepare("SELECT * FROM users WHERE id = '" . $_GET['uID'] . "'");
							$stmt->execute();
							$result = $stmt->get_result();
							$row = $result->fetch_assoc();
							$uID = $row['bname'];

							?>

						</div>
					</section>
					<div class="col-4 text-right">
						<h1>Message this Pro</h1>
						<br>
						<div class="d-flex align-items-end flex-column">
							<form class="justify-content text-left" name="PM_sent" id="contactForm" action="PM_sent.php" method="POST">
								<input type="hidden" id="sessionStatus" value="<?php echo (isset($_SESSION['status_msg'])) ?  $_SESSION['status_msg'] : null; ?>" />
								<input hidden id="bizID" name="bizID" type="text" value="<?php echo $_GET['uID']; ?>">
								<input hidden id="bname" name="bname" type="text" value="<?php echo $uID; ?>">
								<div class="control-group form-group">
									<div class="controls">
										<label>Subject:</label>
										<input type="text" class="form-control" name="subject" id="name" required>
									</div>
								</div>
								<div class="control-group form-group">
									<div class="controls">
										<label>Message:</label>
										<textarea rows="10" cols="30" class="form-control" name="message" id="message" required maxlength="999"></textarea>
									</div>
								</div>
								<!-- For success/fail messages -->
								<button type="submit" class="btn btn-primary" id="sendMessageButton">Send Message</button>
							</form>
						</div>
					</div>
				</div>
		</main>

		<?php include "footer.php"; ?>

		<script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
	</section>

</body>


</html>