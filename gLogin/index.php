<?php
session_start();
// Include configuration file
require_once 'config.php';

// Include User library file
require_once 'User.class.php';

if (isset($_GET['code'])) {
  $gClient->authenticate($_GET['code']);
  $_SESSION['token'] = $gClient->getAccessToken();
  header('Location: ' . filter_var(GOOGLE_REDIRECT_URL, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
  $gClient->setAccessToken($_SESSION['token']);
}

if ($gClient->getAccessToken()) {
  // Get user profile data from google
  $gpUserProfile = $google_oauthV2->userinfo->get();

  // Initialize User class
  $user = new User();

  // Getting user profile info
  $gpUserData = array();
  $gpUserData['oauth_uid']  = !empty($gpUserProfile['id']) ? $gpUserProfile['id'] : '';
  $gpUserData['first_name'] = !empty($gpUserProfile['given_name']) ? $gpUserProfile['given_name'] : '';
  $gpUserData['last_name']  = !empty($gpUserProfile['family_name']) ? $gpUserProfile['family_name'] : '';
  $gpUserData['email']     = !empty($gpUserProfile['email']) ? $gpUserProfile['email'] : '';
  $gpUserData['gender']     = !empty($gpUserProfile['gender']) ? $gpUserProfile['gender'] : '';
  $gpUserData['locale']     = !empty($gpUserProfile['locale']) ? $gpUserProfile['locale'] : '';
  $gpUserData['picture']     = !empty($gpUserProfile['picture']) ? $gpUserProfile['picture'] : '';
  $gpUserData['link']     = !empty($gpUserProfile['link']) ? $gpUserProfile['link'] : '';

  // Insert or update user data to the database
  $gpUserData['oauth_provider'] = 'google';
  $userData = $user->checkUser($gpUserData);

  // Storing user data in the session
  $_SESSION['userData'] = $userData;

  // Render user profile data
  if (!empty($userData)) {
    $output   = '<h2>Google Account Details</h2>';
    $output .= '<div class="ac-data">';
    $output .= '<img src="' . $userData['picture'] . '">';
    $output .= '<p><b>Google ID:</b> ' . $userData['oauth_uid'] . '</p>';
    $output .= '<p><b>Name:</b> ' . $userData['first_name'] . ' ' . $userData['last_name'] . '</p>';
    $output .= '<p><b>Email:</b> ' . $userData['email'] . '</p>';
    $output .= '<p><b>Gender:</b> ' . $userData['gender'] . '</p>';
    $output .= '<p><b>Locale:</b> ' . $userData['locale'] . '</p>';
    $output .= '<p><b>Logged in with:</b> Google</p>';
    $output .= '<p><a href="' . $userData['link'] . '" target="_blank">Click to visit Google+</a></p>';
    $output .= '<p>Logout from <a href="logout.php">Google</a></p>';
    $output .= '</div>';
  } else {
    $output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
  }
} else {
  // Get login url
  $authUrl = $gClient->createAuthUrl();

  // Render google login button
  $output = '<a href="' . filter_var($authUrl, FILTER_SANITIZE_URL) . '"><img src="images/googlebutton.png" alt=""/></a>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
  <link rel="icon" href="../favicon.ico" type="image/x-icon">
  <title>RMC</title>

  <!-- Bootstrap core CSS -->
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../css/modern-business.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>


  <!-- Navigation -->
  <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="../index.html"><img src="../resources/RateMyContractor Logo.bmp"></a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="../about.html">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../services.html">Services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../contact.html">Contact</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../Signup.php"> Register    |</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../Signin.php">  Sign In</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="wrapper">
      <!-- Display login button / Google profile information -->
      <?php echo $output; ?>
    </div>
  </div>

  <!-- Footer -->
  <footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; Rate My Contractor 2019</p>
    </div>
    <!-- /.container -->
  </footer>


  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>