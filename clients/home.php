<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...

require ('../db_connect.php');

$email = $_SESSION['emailclient'];
$uid = $_SESSION['uidClient'];
$zip = $_SESSION['zip'];
$id = $_SESSION['id'];


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="RateMyContractor was created with one goal in sight - to help people connect with the best contractor for their project. We are a growing compilation of homeowner’s contractor reviews with local contractors. The people who join Rate My Contractor are not only interested in sharing their experience but are looking for a trustworthy service professional that will perform the high quality work that all homeowners deserve.">
  <meta name="keywords" content="Rate my contractor, Contractor Reviews, local contractors near me, Hire the Best, Best Contractor">
  <link rel="shortcut icon" href="../resources/favicon.ico" type="image/x-icon">
  <link rel="icon" href="../resources/favicon.ico" type="image/x-icon">
  <title>Rate My Contractor | Find the Best Contractor</title>

		<!-- CSS here -->
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/slicknav.css">
        <link rel="stylesheet" href="../assets/css/slick.css">
        <link rel="stylesheet" href="../assets/css/nice-select.css">
        <link rel="stylesheet" href="../assets/css/style.css">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link href="clienthome.css" rel="stylesheet" />
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
        <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
   <!-- Including our scripting file. -->
   <script type="text/javascript" src="script.js"></script>


   </head>

   
    <?php include "homeMenu.php";?>
<!--<body>
    <div class="s01">
    <form action="clientSearch.php" method="POST">
      <div class="inner-form">
        <div class="input-field first-wrap">
          <input name="bname" id="search" type="text" value= "" placeholder= "Painting, Tiling, Handyman, Etc.">
        </div>
        <div class="input-field second-wrap">
          <input name="zip" id="location" type="text" value="" placeholder="Zip Code or City">
        </div>
        <div class="input-field third-wrap">
          <button class="btn-search" type="submit">Search</button>
        </div>
      </div>
    </form>
  </div>

  <div class="container-fluid">
      <div class="row">
          <div class="margin">
          <a class="a2" href="getEstimates.php">Request Estimates</a>
          <a class="a1">|</a>
          <a class="a2" href="clientReviews.php">Write Review</a>
          <a class="a1">|</a>
          <a class="a2" href="diy.php">DIY - Ask a Pro</a>
          <a class="a1">|</a>
          <a class="a2" href="trueValue.php">True Value</a>
        </div>
      </div>
  </div> --> 

  <div id="contents" class="container">
			<div class="row">
		        <h2>Members Page</h2>
			</div>
        </div>
        
        <br>
        <br>

        <div id="contents" class="container">
			<div class="row">
		        <h3>Messages</h3>
			</div>
        </div>

        <br>
        <br>

        <div id="contents" class="container">
			<div class="row">
		        <h3>Contractors Contacted for Project</h3>
			</div>
        </div>

        <br>
        <br>

        <div id="contents" class="container">
			<div class="row">
		        <h3>My Reviews</h3>
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
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>

<?php include "homeFooter.php"; ?>