<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['uidClient'])) {
	header('Location: ../hosignin.php');
	exit();
}
require '../db_connect.php';

			
		try{$db = new PDO('mysql:host='.$dBServername.';dbname='.$dBName.';charset=utf8mb4', ''.$dBUsername.'', ''.$dBPassword) or die(print_r($db->errorInfo())); }
		catch (Exception $e){die('Erreur : ' . $e->getMessage());}

$statement = $db->prepare('SELECT * FROM leads WHERE uidClient = :uidClient');
$statement->bindParam(':uidClient', $_SESSION['uidClient'], PDO::PARAM_STR);
$statement->execute();
$leadData = $statement->fetchAll();

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Get Quotes | Rate My Contractor</title>
		<link rel="shortcut icon" href="../resources/favicon.ico" type="image/x-icon">
        <link rel="icon" href="../resources/favicon.ico" type="image/x-icon">
		<!--<link href="homestyle.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link rel="stylesheet" href=https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css>
        <script src= https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js></script>
        <script src= https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script> -->
        <script type="text/javascript" src="../js/formquotes.js"></script>
        <link href="../css/stylequotes.css" rel="stylesheet">
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
		
    <?php include 'homeMenu.php'; ?>


<div class="container">
<br>
    <div class="row justify-content-center"> 
	<div id="card2" class="col-lg-10 col-xl-9 mx-auto">
        <div class="card card-signin flex-row my-5">
          <div id="card" class="card-body">
            <h2 class="card-title text-center">Get Quotes</h2>
    <form id="user_form" name="user_form" action="getEstimates.inc.php"  method="post" enctype="multipart/form-data">
    <div class="form-group">
	<label>First Name</label>
	<input type="text" class="form-control" name="fname" required id="fname" placeholder="First Name" value="<?php if(isset($leadData[0]['fname'])){echo $leadData[0]['fname'];}?>">
	</div>
	<div class="form-group">
	<label>Email</label>
	<input type="email" class="form-control" required id="email" name="emailclient" placeholder="Email" value="<?php if(isset($leadData[0]['emailclient'])){echo $leadData[0]['emailclient'];}?>">
	</div>
	<div class="form-group">
	<label>Phone</label>
	<input type="text" class="form-control" id="PhoneInput" required name="phone" placeholder="Phone" value="<?php if(isset($leadData[0]['phone'])){echo $leadData[0]['phone'];}?>">
	</div>    
    <div class="form-group">
	<label>Zip</label>
	<input type="text" class="form-control" required id="zip" name="zip" placeholder="Zip Code" value="<?php if(isset($leadData[0]['zip'])){echo $leadData[0]['zip'];}?>">
    </div>
	<div class="form-group">
	<label>When do you need it done?</label>
	<select style="height: 3rem;"!important  class="custom-select" name= "time" required>
      <option value="Urgently">Urgently</option>
      <option value="This Week">This Week</option>
      <option value="Next Week">Next Week</option>
      <option value="In The Future">In The Future</option> 
    </select>
	</div>
	<div class="form-group">
	<label>Do you have a budget?</label>
	<input type="text" class="form-control" name="money" id="fname" placeholder="Amount">
	</div>
	<div class="form-group">
	<label>Job Category</label>
	<input type="text" class="form-control" name="type" id="type" required placeholder="e.g. Painting, Tiling, etc.">
	</div>
	<div class="form-group">
	<label>Please Describe What You Want To Have Done...</label>
	<textarea name="leadinfo" required form="user_form" id="describe" wrap="hard" cols="83" rows="3"></textarea>
	</div>
    <input type="submit" name="submit" id="submit" class="submit btn btn-success btn-block" value="Submit" />
    </form>
</div>
		  </div>
		</div>
	  </div>
	</div>
<br>
<br>
<?php include 'homeFooter.php';?>
</body>
<script type="text/javascript" src="../js/phone-format.js"></script>