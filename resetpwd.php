
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
    <div class="container col-9">  
          <div class="text-center">
            <h4>An Email Will Be Sent To Your Email On File With Instructions On How To Reset Your Password!</h4>
          </div>
        </div>
    </div>
    <br>
    <div class="container">
            <form action="resetpwd.inc.php" method="POST">
                <div class="form-label-group">
                    <Input class="form-control" type="text" name="email" id="resetpwd" placeholder="Enter your email...">
                    <label for="resetpwd">Enter your email...</label>
                </div>
                <button class="btn btn-lg btn-primary btn-block rounded-pill" type="submit" name="submit">Reset Your Password</button>
            </form>
            <br>
            <br>
            <?php if(isset($_GET["reset"])) {
                if($_GET["reset"] == "success") {
                    ?>
                    <div class="text-center"> 
                    <?php echo '<h3> Check Your Email!</h3>';?>
                    </div>
                <?php
                }
			}
			?>
        </div>
      </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    
<?php include "footer.php"; ?>

</body>

</html> 