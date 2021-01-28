<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...

require 'db_connect.php';




?>
<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Reviews | Rate My Contractor</title>
        <link rel="shortcut icon" href="../resources/favicon.ico" type="image/x-icon">
        <link rel="icon" href="../resources/favicon.ico" type="image/x-icon">
		<link href="homestyle.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link rel="stylesheet" href=https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css>
        <script src= https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js></script>
        <script src= https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="../modules/review/css/default.css" />
		<script type="text/javascript" src="../modules/review/js/default.js"></script>
	</head>
	<body id="back" class="loggedin">
        
    <?php include 'menu.php'; ?> 

        <div class="container">
            <br>
            <br>
            <br>
            <div class="row">
                <div class="col-lg-10 col-xl-9 mx-auto"><br>
                    <div id="card2" class="card card-signin flex-row my-5">
                        <div class="card-img-left d-none d-md-flex">
		                </div>
                        <div class="card-body">
                        <br>
                        <h2 class="card-title text-center">Write A Review</h2>
                        <br>
                        <br>
                        <div>
                        <?php include "../modules/review/rateContractor.php"; ?>
                        </div>
                        </div>
                    </div>
		        </div>
            </div>
        </div>
<br>
</body>
<?php include 'footer.php';?>

            