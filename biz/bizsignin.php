<?php
  
  require "header.php";
?>

<title>Contractor Signin | Rate My Contractor</title>
<meta name="keywords" content="Cheap Leads, Contractor, Business Advertising, Lead Generation, get Referrals">
<meta name="description" content="Web based lead generation for contractors based on homeowner reviews. Get referrals for your contracting business and make money today. Cheap leads and business advertising on the fly setup your account now.">

</head>

<body>

  <?php require './bizmenu.php'; ?>


  <div class="container">
      <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <div class="card card-signin my-5">
            <div class="card-body">
              <h5 class="card-title text-center">Sign In</h5>
              <form class="form-signin" action="login.inc.php" method="POST">
                <div class="form-label-group">
                  <input type="text" name= "uidEmail" id="inputusername" class="form-control" placeholder="Email/Username" required autofocus>
                  <label for="inputUsername"></label>
                </div>  
                <div class="form-label-group">
                  <input type="password" name= "pwdUsers" id="inputPassword" class="form-control" placeholder="Password" required>
                  <label for="inputPassword"></label>
                </div>
                <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" name="login-submit">Login</button>
                <div class="justify-content-between">
                    <div>
                    <a class="text-right float-right mt-2 small" href="bizsignup.php">Need An Account?<br> Register Here</a>
                    </div>
                    <div>
                    <a class= "text-left mt-2 small" href="resetpwd.php">Forgot Password?</a>
                    </div>
                </div>
              </form>
            </div>
          </div>
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
<?php
  require "footer.php";
?>