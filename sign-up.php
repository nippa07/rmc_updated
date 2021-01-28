<?php 
// Include configuration file 
require_once './gLogin/config.php'; 
 
// Include User library file 
require_once './gLogin/User.class.php'; 
 
if(isset($_GET['code'])){ 
    $gClient->authenticate($_GET['code']); 
    $_SESSION['token'] = $gClient->getAccessToken(); 
    header('Location: ' . filter_var(GOOGLE_REDIRECT_URL, FILTER_SANITIZE_URL)); 
} 
 
if(isset($_SESSION['token'])){ 
    $gClient->setAccessToken($_SESSION['token']); 
} 
 
if($gClient->getAccessToken()){ 
    // Get user profile data from google 
    $gpUserProfile = $google_oauthV2->userinfo->get(); 
     
    // Initialize User class 
    $user = new User(); 
     
    // Getting user profile info 
    $gpUserData = array(); 
    $gpUserData['oauth_uid']  = !empty($gpUserProfile['id'])?$gpUserProfile['id']:''; 
    $gpUserData['first_name'] = !empty($gpUserProfile['given_name'])?$gpUserProfile['given_name']:''; 
    $gpUserData['last_name']  = !empty($gpUserProfile['family_name'])?$gpUserProfile['family_name']:''; 
    $gpUserData['email']       = !empty($gpUserProfile['email'])?$gpUserProfile['email']:''; 
    $gpUserData['gender']       = !empty($gpUserProfile['gender'])?$gpUserProfile['gender']:''; 
    $gpUserData['locale']       = !empty($gpUserProfile['locale'])?$gpUserProfile['locale']:''; 
    $gpUserData['picture']       = !empty($gpUserProfile['picture'])?$gpUserProfile['picture']:''; 
     
    // Insert or update user data to the database 
    $gpUserData['oauth_provider'] = 'google'; 
    $userData = $user->checkUser($gpUserData); 
     
    // Storing user data in the session 
    $_SESSION['userData'] = $userData; 
     
    // Render user profile data 
    if(!empty($userData)){ 
        $output     = '<h2>Google Account Details</h2>'; 
        $output .= '<div class="ac-data">'; 
        $output .= '<img src="'.$userData['picture'].'">'; 
        $output .= '<p><b>Google ID:</b> '.$userData['oauth_uid'].'</p>'; 
        $output .= '<p><b>Name:</b> '.$userData['first_name'].' '.$userData['last_name'].'</p>'; 
        $output .= '<p><b>Email:</b> '.$userData['email'].'</p>'; 
        $output .= '<p><b>Gender:</b> '.$userData['gender'].'</p>'; 
        $output .= '<p><b>Locale:</b> '.$userData['locale'].'</p>'; 
        $output .= '<p><b>Logged in with:</b> Google Account</p>'; 
        $output .= '<p>Logout from <a href="logout.php">Google</a></p>'; 
        $output .= '</div>'; 
    }else{ 
        $output = '<h3 style="color:red">Some problem occurred, please try again.</h3>'; 
    } 
}else{ 
    // Get login url 
    $authUrl = $gClient->createAuthUrl(); 
     
    // Render google login button 
  $output = '<a style="width: 30em;" class="btn-google" href="' . filter_var($authUrl, FILTER_SANITIZE_URL) . '">Google</a>';
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Rate My Contactor</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

		<!-- CSS here -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/slicknav.css">
        <link rel="stylesheet" href="assets/css/style.css">

   </head>

   <body>
    <?php include "menu.php";?>

    <main>

        <!-- Hero Area Start-->
        <div class="slider-area hero-overly">
            <div class="single-slider hero-overly  slider-height d-flex align-items-center">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-8 col-lg-9">
                            <div class="wrapper custom-wrapper">
                                <div class="sign-panels custom-padding-sign-panels custom-width-sign-panels">
                                    <div class="signup">
                                        <div class="title">
                                            <span>Sign Up</span>
                                            <br>
                                            <p>Create a new account, or sign up with google or facebook account</p>
                                            <br>
                                        </div>
                            
                                        <div>
                                            <!-- <?php echo $output;?> -->
                                            <!-- <a href="#" class="btn-face"><i class="fa fa-facebook" aria-hidden="true"></i> Facebook </a> -->
                                        </div>
                            
                                        <!-- <div class="or"><span>OR</span></div> -->
                            
                                        <form action="./clients/register.php" method="POST">
                                            <input type="text" name="uidClient" placeholder="Username">
                                            <input type="email" name="emailclient" placeholder="Email Address">
                                            <input type="password" name="pwdClient" placeholder="Password">
                                            <input type="password" name="pwdrepeat" placeholder="Repeat Password">
                            
                                            <button type=submit class="btn-signin">Sign Up</button>
                                            <a href="log-in.php" class="btn-login btn-fade">Already have an account? Log In </a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Hero Area End-->

    </main>

    <?php include "footer.php";?>