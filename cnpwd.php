
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="./resources/favicon.ico" type="image/x-icon">
  <link rel="icon" href="./resources/favicon.ico" type="image/x-icon">
  <title>Rate My Contractor</title>
  
  <link href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" rel="stylesheet">
  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="./css/signup.css" rel="stylesheet">
  		<!-- CSS here -->
          <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/slicknav.css">
        <link rel="stylesheet" href="assets/css/slick.css">
        <link rel="stylesheet" href="assets/css/nice-select.css">
        <link rel="stylesheet" href="assets/css/style.css">

</head>
<body id="body">
  
<?php include "menu.php"; ?>
<div>
    <div class="container"></div>
    <div class="row col"></div>
      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
    <div class="containter col-4"></div>
      <div class="text-center">
        <h1>Reset Your Password</h1>
      </div>
    </div>
</div>  
    <?php

$selector = $_GET["selector"];
$validator = $_GET["validator"];

if (empty($selector) || empty($validator)){
    echo "could not validate your request";
} else {
    if(ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) {

        ?>
    <br>
    <div class="container">
            <form action="cnpwd.inc.php" method="POST">
                <div class="form-label-group"> 
                    <Input class="form-control" type="password" id="cnpwd" autofocus name="pwd" placeholder="Enter  your new password">
                    <label for="cnpwd">Enter New Password...</label>
                </div>
                <div class="form-label-group">
                    <Input class="form-control" type="password" id="cnpwdrepeat" name="pwdRepeat" placeholder="Repeat Passsword">
                    <label for="cnpwdrepeat">Repeat Password</label>
                </div>
                <div class="form-label-group">
                    <Input class="form-control" type="hidden" name="selector" value="<?php echo $selector ?>">
                </div>
                <div class="form-label-group">
                    <Input class="form-control" type="hidden" name="validator" value="<?php echo $validator ?>">
                </div>
                <button class="btn btn-lg btn-primary btn-block rounded-pill" type="submit" name="pwdresetsubmit">Change Your Password</button>
            </form>
            <?php
    }}
    ?>
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
    <br>
   
<?php include "footer.php"; ?>

</body>

</html> 