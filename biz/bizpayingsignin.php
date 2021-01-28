<?php
  // To make sure we don't need to create the header section of the website on multiple pages, we instead create the header HTML markup in a separate file which we then attach to the top of every HTML page on our website. In this way if we need to make a small change to our header we just need to do it in one place. This is a VERY cool feature in PHP!
  require "header.php";
?>

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
              <form class="form-signin" action="loginpaying.inc.php" method="POST">
                <div class="form-label-group">
                  <input type="text" name= "uidEmail" id="inputusername" class="form-control" placeholder="Email/Username" required autofocus>
                  <label for="inputUsername">Username</label>
                </div>  
                <div class="form-label-group">
                  <input type="password" name= "pwdUsers" id="inputPassword" class="form-control" placeholder="Password" required>
                  <label for="inputPassword">Password</label>
                </div>
                <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" name="login-submit">Login</button>
                <div class="text-center">
                    <a href="resetpwd.php">Forgot Password?</a>
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