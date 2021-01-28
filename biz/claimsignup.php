<?php

$uID= $_GET['uID'];


?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="../resources/favicon.ico" type="image/x-icon">
  <link rel="icon" href="../resources/favicon.ico" type="image/x-icon">
  <title>Rate My Contractor</title>
  
  <link href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" rel="stylesheet">
  <!-- Bootstrap core CSS -->
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../css/signup.css" rel="stylesheet">

</head>
<body id="body">
  
<?php include "bizmenu.php"; ?>

    <div class="container">
      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
      <div class="row">
        <div class="col-lg-10 col-xl-9 mx-auto">
          <div class="card card-signin flex-row my-5">
            <div id="cardleft" class="card-img-left d-none d-md-flex">
               <!-- Background image for card set in CSS! -->
            </div>
            <div class="card-body">
              <h5 class="card-title text-center">Claim Profile</h5>
              <form class="form-signin" action="./claim.php" method="POST">
                <div class="form-label-group">
                  <input type="text" name= "uidUsers" id="inputUserame" class="form-control" placeholder="Username" required autofocus>
                  <input type="text" name= "id" class="form-control" required hidden value=<?=$uID?>>
                  <label for="inputUserame">Username</label>
                </div>

                <div class="form-label-group">
                  <input type="email" name= "emailUsers" id="inputEmail" class="form-control" placeholder="Email address" required>
                  <label for="inputEmail">Email address</label>
                </div>
                
  
                <div class="form-label-group">
                  <input type="password" name= "pwdUsers" id="inputPassword" class="form-control" placeholder="Password" required>
                  <label for="inputPassword">Password</label>
                </div>
                
                <div class="form-label-group">
                  <input type="password" name= "pwdrepeat" id="inputPasswordRepeat" class="form-control" placeholder="Password Repeat" required>
                  <label for="inputPasswordRepeat">Password Repeat</label>
                </div>
  
                <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Claim</button>
                
                
                
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br>
    <br>
    <br>
<?php include "footer.php"; ?>

</body>

</html>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>