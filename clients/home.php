<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...

require('../db_connect.php');

$email = $_SESSION['emailclient'];
$uid = $_SESSION['uidClient'];
$zip = $_SESSION['zip'];
$id = $_SESSION['id'];

$sql = "SELECT * FROM messages WHERE (sent_by = '$id') ORDER BY created_at DESC LIMIT 5;";

$sent_msgs = $conn->query($sql);

$sql = "SELECT * FROM messages WHERE (sent_to = '$id') ORDER BY created_at DESC LIMIT 5;";

$received_msgs = $conn->query($sql);
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
  <style>
    @media (min-width: 992px) {
      .modal-lg {
        max-width: 80%;
        width: 80%;
      }
    }
  </style>

</head>


<?php include "homeMenu.php"; ?>
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
  <h3>Messages</h3>
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
                  <a href="javascript:void('0')" data-toggle="modal" data-target="#messageModal" onclick="loadData('<?php echo $sent_msg['message'] ?>', '<?php echo $sent_msg['sent_to'] ?>', '<?php echo $sent_msg['received_name'] ?>', 1);">
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
                  <a href="javascript:void('0')" data-toggle="modal" data-target="#messageModal" onclick="loadData('<?php echo $received_msg['message'] ?>', '<?php echo $received_msg['sent_by'] ?>', '<?php echo $received_msg['sent_name'] ?>', 2);">
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
<?php include "homeFooter.php"; ?>
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
<?php
unset($_SESSION['status_msg']);
?>