<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if ($_SESSION['userStatus'] != 'contractor') {
	header('Location: bizsignin.php');
	exit();
}
require '../db_connect.php';

$uid = $_SESSION['uidUsers'];
$zip = $_SESSION['zip'];
$bname = $_SESSION['bname'];
$id = $_SESSION["id"];



?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Rate My Contractor | Home</title>
		<link rel="shortcut icon" href="../resources/favicon.ico" type="image/x-icon">
        <link rel="icon" href="../resources/favicon.ico" type="image/x-icon">
		<link href="./homestyle.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link rel="stylesheet" href=https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css>
        <script src= https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js></script>
        <script src= https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

		<link href="https://unpkg.com/tabulator-tables@4.5.3/dist/css/tabulator.min.css" rel="stylesheet">
        <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.5.3/dist/js/tabulator.min.js"></script>
		<link rel="stylesheet" href="../table.css">
		<link rel="stylesheet" href="../css/biz.css">
	</head>
	<body class="loggedin">
		
<?php include 'menu.php' ?>

<br>
<br>
<br>
<br>
<br>
<br>

<div id="contents" class="container"> 
			<div class="row">
		        <h2>Home Page</h2>
			</div>
		</div>
		<div class="container">
			<div class="row d-flex justify-content-xl-around">
				<div id="content" class="flex-grow-1">
			        <p>Welcome back, <?=$uid?>!</p>
			    </div>
		        <div class="">
			        <a href="#"><input id="btn" class="btn-sm btn-primary text-center" value="Get RMC Certified"></a>
				</div>
			</div>
		</div>
	<?php 
$sql="SELECT * FROM users WHERE uidUsers = '$uid'";
$result = $conn->query($sql);
$row= mysqli_fetch_assoc($result);
if ($row['paying'] == 0) { 

	?>
		<div class="container">
			<br>
			<br>
			<br>
			<div class="row">
				<h2>Leads you've recieved:</h2>
			</div>
		</div>
		<?php
		
		$sql= "SELECT fname, phone, time, leadinfo, date FROM leads WHERE zip= '$zip' AND DATE > current_date -'14' ORDER BY RAND()";
$result = $conn->query($sql);
	$row = $result->fetch_all(MYSQLI_ASSOC);
	$row = json_encode($row);



    
        ?>

        <div class="container">
            <div id="example-table">
            </div>
        </div>
				
		<script>
    <?php
        echo 'var tabledata = ['.$row.'];';
    ?>
   
 var table = new Tabulator("#example-table", {
 	layout: "fitDataStretch",
 	data:tabledata, //assign data to table
 	columns:[ //Define Table Columns
	 	{title:"Name", field:"fname", width:170, headerSort:false},
	 	{title:"Phone", field:"phone", align:"left", width:130, headerSort:false},
	 	{title:"Time", field:"time", width:140, headerSort:false},
		{title:"Lead Info", field:"leadinfo", align:"center", headerSort:false},
	 	
 	],
});

table.setData(tabledata[0]);

   </script>
          <?php  ?>
		<br>
		<br>
		<div class="container">
			<div class="row">
				<h2>Reply To Reviews In Your Profile Section</h2>
			</div>
		</div>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		

		<?php include 'footer.php';?>
		
		
		<?php 
$sql="SELECT * FROM users WHERE uidUsers = '$uid'";
$result = $conn->query($sql);
$row= mysqli_fetch_assoc($result);
} elseif ($row['paying'] == 1) {

	

	?>
		<div class="container">
			<div class="text-center">
				<h2>Upgrade Your Account to start recieving Leads and replying to Reviews!</h2>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col text-center">
				<a href="../paypal/payingplus.php"><h3>Basic Plus</h3></a>
				</div>
				<div class="col text-center">
				<a href="../paypal/payingpro.php"><h3>Professional</h3></a>
				</div>
			</div>
		</div>

		
	</body>
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
	<br>
	<br>
<?php } else { ?>

<?php include 'footer.php'; ?>

</html><?php } ?>