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

$sql = "SELECT * FROM messages WHERE (sent_by = '$id') ORDER BY created_at DESC LIMIT 5;";

$sent_msgs = $conn->query($sql);

$sql = "SELECT * FROM messages WHERE (sent_to = '$id') ORDER BY created_at DESC LIMIT 5;";

$received_msgs = $conn->query($sql);
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
	<script src=https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js></script>
	<script src=https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

	<link href="https://unpkg.com/tabulator-tables@4.5.3/dist/css/tabulator.min.css" rel="stylesheet">
	<script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.5.3/dist/js/tabulator.min.js"></script>
	<link rel="stylesheet" href="../table.css">
	<link rel="stylesheet" href="../css/biz.css">

	<style>
		h5 {
			text-decoration: none;
		}

		.message-modal {
			color: #337ab7 !important;
			text-decoration: none !important;
		}

		@media (min-width: 992px) {
			.modal-lg {
				max-width: 40%;
				width: 40%;
			}
		}
	</style>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
	<script>
		$(document).ready(function() {
			showAlert();
		});

		function showAlert() {
			if ($("#sessionStatus").val()) {
				alert($("#sessionStatus").val());
			}
		}

		function loadData(msg, id, name, type) {
			var prefix = "To - ";

			if (type == 2) {
				prefix = "From - ";
			}

			$('#name').html(prefix + name);
			$('#message').html(msg);

			$('#sent_to').val(id);
			$('#received_name').val(name);
		}
	</script>
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
				<p>Welcome back, <?= $uid ?>!</p>
			</div>
			<div class="">
				<a href="#"><input id="btn" class="btn-sm btn-primary text-center" value="Get RMC Certified"></a>
			</div>
		</div>
	</div>

	<div id="contents" class="container">
		<h2>Messages</h2>
		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title">Sent Messages</h5>
						<br>

						<?php
						if ($sent_msgs->num_rows > 0) {
							foreach ($sent_msgs as $sent_msg) {

						?>
								<div class="row">
									<div class="col-md-2">
										<span class="user-img">
											<img width="50" height="50" class="img-profile rounded-circle" src="../resources/avatar.png">
										</span>
									</div>
									<div class="col-md-10">
										<a class="message-modal" href="javascript:void('0')" data-toggle="modal" data-target="#messageModal" onclick="loadData('<?php echo $sent_msg['message'] ?>', '<?php echo $sent_msg['sent_to'] ?>', '<?php echo $sent_msg['received_name'] ?>', 1);">
											<div class="mail-contnet">
												<h4>To - <?php echo $sent_msg['received_name'] ?><br /></h4>
												<span class="message-title"><?php echo $sent_msg['subject'] ?></span><br />
												<small class="text-xs text-dark pt-5"><?php echo $sent_msg['created_at'] ?></small>
											</div>
										</a>
									</div>
								</div>
								<hr>
							<?php
							}
						} else {
							?>
							<div class="row">
								<div class="col-md-12 text-center">
									<div class="mail-contnet">
										<h4>No messages<br /></h4>
									</div>
								</div>
							</div>
						<?php
						}
						?>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title">Received Messages</h5>
						<br>

						<?php
						if ($received_msgs->num_rows > 0) {
							foreach ($received_msgs as $received_msg) {

						?>
								<div class="row">
									<div class="col-md-2">
										<span class="user-img">
											<img width="50" height="50" class="img-profile rounded-circle" src="../resources/avatar.png">
										</span>
									</div>
									<div class="col-md-10">
										<a class="message-modal" href="javascript:void('0')" data-toggle="modal" data-target="#messageModal" onclick="loadData('<?php echo $received_msg['message'] ?>', '<?php echo $received_msg['sent_by'] ?>', '<?php echo $received_msg['sent_name'] ?>', 2);">
											<div class="mail-contnet">
												<h4>From - <?php echo $received_msg['sent_name'] ?><br /></h4>
												<span class="message-title"><?php echo $received_msg['subject'] ?></span><br />
												<small class="text-xs text-dark pt-5"><?php echo $received_msg['created_at'] ?></small>
											</div>
										</a>
									</div>
								</div>
								<hr>
							<?php
							}
						} else {
							?>
							<div class="row">
								<div class="col-md-12 text-center">
									<div class="mail-contnet">
										<h4>No messages<br /></h4>
									</div>
								</div>
							</div>
						<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br>
	<br>

	<?php
	$sql = "SELECT * FROM users WHERE uidUsers = '$uid'";
	$result = $conn->query($sql);
	$row = mysqli_fetch_assoc($result);
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

		$sql = "SELECT fname, phone, time, leadinfo, date FROM leads WHERE zip= '$zip' AND DATE > current_date -'14' ORDER BY RAND()";
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
			echo 'var tabledata = [' . $row . '];';
			?>

			var table = new Tabulator("#example-table", {
				layout: "fitDataStretch",
				data: tabledata, //assign data to table
				columns: [ //Define Table Columns
					{
						title: "Name",
						field: "fname",
						width: 170,
						headerSort: false
					},
					{
						title: "Phone",
						field: "phone",
						align: "left",
						width: 130,
						headerSort: false
					},
					{
						title: "Time",
						field: "time",
						width: 140,
						headerSort: false
					},
					{
						title: "Lead Info",
						field: "leadinfo",
						align: "center",
						headerSort: false
					},

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


		<?php include 'footer.php'; ?>


	<?php
		$sql = "SELECT * FROM users WHERE uidUsers = '$uid'";
		$result = $conn->query($sql);
		$row = mysqli_fetch_assoc($result);
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
					<a href="../paypal/payingplus.php">
						<h3>Basic Plus</h3>
					</a>
				</div>
				<div class="col text-center">
					<a href="../paypal/payingpro.php">
						<h3>Professional</h3>
					</a>
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
<!-- Popup Modal Start -->
<div class="modal" id="messageModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Message</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-2">
								<span class="user-img">
									<img width="50" height="50" class="img-profile rounded-circle" src="../resources/avatar.png">
								</span>
							</div>
							<div class="col-md-10">
								<div class="mail-contnet">
									<h4 id="name"><br /></h4>
									<span id="message" class="message-title"></span><br />
								</div>
							</div>
						</div>
						<hr>
						<form class="justify-content text-left" name="PM_sent" id="contactForm" action="send_message.php" method="POST">
							<input type="hidden" id="sessionStatus" value="<?php echo (isset($_SESSION['status_msg'])) ?  $_SESSION['status_msg'] : null; ?>" />
							<input hidden id="sent_to" name="sent_to" type="text">
							<input hidden id="received_name" name="received_name" type="text">
							<div class="control-group form-group">
								<div class="controls">
									<label>Subject:</label>
									<input type="text" class="form-control" name="subject" id="name" required>
								</div>
							</div>
							<div class="control-group form-group">
								<div class="controls">
									<label>Message:</label>
									<textarea rows="5" cols="30" class="form-control" name="message" id="message" required maxlength="999"></textarea>
								</div>
							</div>
							<div class="text-center">
								<!-- For success/fail messages -->
								<button type="submit" class="btn btn-primary text-center" id="sendMessageButton">Reply</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Popup Modal Ends -->
<?php
unset($_SESSION['status_msg']);
?>