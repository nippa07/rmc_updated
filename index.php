<?php
session_start();
// Include configuration file
require_once './gLogin/config.php';

// Include User library file
require_once './gLogin/User.class.php';

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
    $output = '<a style="width: 30em;" class="btn-google" href="' . filter_var($authUrl, FILTER_SANITIZE_URL) . '">Google</a>';
}
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
    <link rel="shortcut icon" href="./resources/favicon.ico" type="image/x-icon">
    <link rel="icon" href="./resources/favicon.ico" type="image/x-icon">
    <title>Rate My Contractor | Find the Best Contractor</title>

    <!-- CSS here -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.css">
    <link rel="stylesheet" href="assets/css/slick.css">
    <link rel="stylesheet" href="assets/css/nice-select.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <!-- Including our scripting file. -->
    <script type="text/javascript" src="script.js"></script>


</head>

<body>
    <?php include "menu.php"; ?>

    <main>

        <!-- Hero Area Start-->
        <div class="slider-area hero-overly">
            <div class="single-slider hero-overly  slider-height d-flex align-items-center">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-8 col-lg-9">
                            <!-- Hero Caption -->
                            <div class="hero__caption">
                                <h1>Discover the Best Contractor</h1>
                            </div>
                            <!--Hero form -->
                            <form action="search/find.php" method="POST" class="search-box">
                                <div class="input-form">
                                    <input name="bname" type="text" placeholder="e.g. Painting, Plumbing, Handyman...">
                                </div>
                                <div class="input-form-b">
                                    <input name="zip" type="text" placeholder="Zip Code or City">
                                </div>
                                <!-- <div class="select-form">
                                    <div class="select-itms">
                                        <select name="select" id="select1">
                                            <option value="">Zip Code or City</option>
                                            <option value="">100112</option>
                                            <option value="">100143</option>
                                            <option value="">100532</option>
                                            <option value="">100222</option>
                                        </select>
                                    </div>
                                </div> -->
                                <div class="search-form">
                                    <button style="width: 100%; height: 60px; background: #E4DB1F; font-size: 20px; line-height: 1; text-align: center; color: #000;display: block; padding: 15px; border-radius: 0px; text-transform: capitalize; font-family: 'Poppins', sans-serif !important; line-height: 31px; font-size: 15px" type="submit">Search</button>
                                </div>
                            </form>
                            <div class="hero__sub__caption">
                                <h3>Hire the Best, Forget the Rest</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero-overly">
                <div class="sub-hero-overly">
                    <div class="container sub-hero-section">
                        <div class="row">
                            <div class="col-md-4 custom-margin">
                                <div class="sub-hero-a">
                                    <h4 class="sub-hero-heading">Find a Pro</h4>
                                    <p class="sub-hero-details">Click below to find the top rated contactors in your area</p>
                                    <div class="pt-20">
                                        <a href="search.php" class="btn">Search Pros</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 custom-margin">
                                <div class="sub-hero-b">
                                    <h4 class="sub-hero-heading">Rate and Review</h4>
                                    <p class="sub-hero-details">Click below to instantly rate any professional</p>
                                    <div class="pt-20">
                                        <!-- <a href="" class="btn myBtn" data-toggle="modal" data-target="#myModal">Write Review</a> -->
                                        <a href="reviews.php" class="btn myBtn">Write Review</a>
                                        <!-- <a href="orgreviews.php">Review</a> -->
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-4 custom-margin">
                                <div class="sub-hero-c">
                                    <h4 class="sub-hero-heading">Are you a Contractor?</h4>
                                    <p class="sub-hero-details">Click below to sign up and start managing your own profile to attract clients</p>
                                    <div class="pt-20">
                                        <a href="/biz/bizsignup.php" class="btn">Join the Team</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Hero Area End-->

    </main>

    <!-- Trigger/Open The Modal -->
    <!-- <button id="myBtn">Open Modal</button> -->

    <!-- Popup Modal Start -->
    <div id="myModal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <span class="close" data-dismiss="modal">&times;</span>
            <div class="wrapper">
                <div style="width: 625px;" class="sign-panels sign-panels-review">

                    <div class="review">
                        <div class="title-review">
                            <span>Review Contractor</span>
                        </div>

                        <div class="wrap">
                            <div class="search">
                                <input name="bname" type="text" class="searchTerm" placeholder="Business name..." style="box-shadow: 0 0 5px rgb(0 0 0 / 0.16);">
                                <button type="submit" class="searchButton" style="box-shadow: 0 0 5px rgb(0 0 0 / 0.16);">
                                    Search
                                </button>
                            </div>
                        </div>
                        <br>

                        <form action="reviews_submit.php" method="POST">
                            <div class="flex">
                                <div class="align-left">
                                    <label for="" style="font-weight: 600;">Professionalism</label>
                                </div>
                                <div class="container p-0">
                                    <fieldset id='demo1' class="rating">
                                        <input class="stars" type="radio" id="1star_a-5" name="1rating" value="5" />
                                        <label class="full" for="1star_a-5" title="Awesome - 5 stars"></label>
                                        <input class="stars" type="radio" id="1star_a_5-half" name="1rating" value="4" />
                                        <label class="half" for="1star_a_5-half" title="Pretty good - 4.5 stars"></label>
                                        <input class="stars" type="radio" id="1star_a-4" name="1rating" value="3" />
                                        <label class="full" for="1star_a-4" title="Pretty good - 4 stars"></label>
                                        <input class="stars" type="radio" id="1star_a_4-half" name="1rating" value="2" />
                                        <label class="half" for="1star_a_4-half" title="Meh - 3.5 stars"></label>
                                        <input class="stars" type="radio" id="1star_a-3" name="1rating" value="1" />
                                        <label class="full" for="1star_a-3" title="Meh - 3 stars"></label>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="align-left">
                                    <label for="" style="font-weight: 600;">Quality</label>
                                </div>
                                <div class="container p-0">
                                    <fieldset id='demo2' class="rating">
                                        <input class="stars" type="radio" id="2star_a-5" name="2rating" value="5" />
                                        <label class="full" for="2star_a-5" title="Awesome - 5 stars"></label>
                                        <input class="stars" type="radio" id="2star_a_5-half" name="2rating" value="4" />
                                        <label class="half" for="2star_a_5-half" title="Pretty good - 4.5 stars"></label>
                                        <input class="stars" type="radio" id="2star_a-4" name="2rating" value="3" />
                                        <label class="full" for="2star_a-4" title="Pretty good - 4 stars"></label>
                                        <input class="stars" type="radio" id="2star_a_4-half" name="2rating" value="2" />
                                        <label class="half" for="2star_a_4-half" title="Meh - 3.5 stars"></label>
                                        <input class="stars" type="radio" id="2star_a-3" name="2rating" value="1" />
                                        <label class="full" for="2star_a-3" title="Meh - 3 stars"></label>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="align-left">
                                    <label for="" style="font-weight: 600;">Communication</label>
                                </div>
                                <div class="container p-0">
                                    <fieldset id='demo3' class="rating">
                                        <input class="stars" type="radio" id="3star_a-5" name="3rating" value="5" />
                                        <label class="full" for="3star_a-5" title="Awesome - 5 stars"></label>
                                        <input class="stars" type="radio" id="3star_a_5-half" name="3rating" value="4" />
                                        <label class="half" for="3star_a_5-half" title="Pretty good - 4.5 stars"></label>
                                        <input class="stars" type="radio" id="3star_a-4" name="3rating" value="3" />
                                        <label class="full" for="3star_a-4" title="Pretty good - 4 stars"></label>
                                        <input class="stars" type="radio" id="3star_a_4-half" name="3rating" value="2" />
                                        <label class="half" for="3star_a_4-half" title="Meh - 3.5 stars"></label>
                                        <input class="stars" type="radio" id="3star_a-3" name="3rating" value="1" />
                                        <label class="full" for="3star_a-3" title="Meh - 3 stars"></label>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="align-left">
                                    <label for="" style="font-weight: 600;">Cleanliness</label>
                                </div>
                                <div class="container p-0">
                                    <fieldset id='demo4' class="rating">
                                        <input class="stars" type="radio" id="4star_a-5" name="4rating" value="5" />
                                        <label class="full" for="4star_a-5" title="Awesome - 5 stars"></label>
                                        <input class="stars" type="radio" id="4star_a_5-half" name="4rating" value="4" />
                                        <label class="half" for="4star_a_5-half" title="Pretty good - 4.5 stars"></label>
                                        <input class="stars" type="radio" id="4star_a-4" name="4rating" value="3" />
                                        <label class="full" for="4star_a-4" title="Pretty good - 4 stars"></label>
                                        <input class="stars" type="radio" id="4star_a_4-half" name="4rating" value="2" />
                                        <label class="half" for="4star_a_4-half" title="Meh - 3.5 stars"></label>
                                        <input class="stars" type="radio" id="4star_a-3" name="4rating" value="1" />
                                        <label class="full" for="4star_a-3" title="Meh - 3 stars"></label>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="align-left">
                                    <label for="" style="font-weight: 600;">Price</label>
                                </div>
                                <div class="container p-0">
                                    <fieldset id='demo4' class="rating">
                                        <input class="stars" type="radio" id="5star_a-5" name="5rating" value="5" />
                                        <label class="full" for="5star_a-5" title="Awesome - 5 stars"></label>
                                        <input class="stars" type="radio" id="5star_a_5-half" name="5rating" value="4" />
                                        <label class="half" for="5star_a_5-half" title="Pretty good - 4.5 stars"></label>
                                        <input class="stars" type="radio" id="5star_a-4" name="5rating" value="3" />
                                        <label class="full" for="5star_a-4" title="Pretty good - 4 stars"></label>
                                        <input class="stars" type="radio" id="5star_a_4-half" name="5rating" value="2" />
                                        <label class="half" for="5star_a_4-half" title="Meh - 3.5 stars"></label>
                                        <input class="stars" type="radio" id="5star_a-3" name="5rating" value="1" />
                                        <label class="full" for="5star_a-3" title="Meh - 3 stars"></label>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="align-left">
                                    <label for="" style="font-weight: 600;">Total Cost <span style="color: #707070">(Optional)</span></label>
                                </div>
                                <div class="" style="width: 57.5%;">
                                    <input type="text" name="cost" id="" class="total-cost-input">
                                </div>
                            </div>
                            <div class="">
                                <div class="align-left">
                                    <label for="" style="font-weight: 600;">Please leave your review</label>
                                </div>
                                <input type="text" name="review" id="" class="leave-review-input">
                            </div>

                            <a href="javascript:void(0)" class="btn-signin btn-login btn-submit ">Submit</a>

                        </form>
                    </div>

                    <div class="signup">
                        <div class="title">
                            <span>Sign Up</span>
                            <br>
                            <p>Create a new account, or sign up with google or facebook account</p>
                            <br>
                        </div>

                        <div>
                            <?php echo $output; ?>
                            <!-- <a href="#" class="btn-face"><i class="fa fa-facebook" aria-hidden="true"></i> Facebook</a> -->
                        </div>

                        <div class="or"><span>OR</span></div>

                        <form action="clients/register.php" method="POST">
                            <input name="uidClient" type="text" placeholder="Username">
                            <input name="emailclient" type="email" placeholder="Email Address">
                            <input name="pwdClient" type="password" placeholder="Password">
                            <input name="pwdrepeat" type="password" placeholder="Repeat Password">

                            <button type="submit" class="btn-signin">Sign Up</button>
                            <a href="javascript:void(0)" class="btn-login btn-login-signin btn-fade">Already have an account? Sign In <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                        </form>
                    </div>

                    <div class="login">
                        <div class="title">
                            <span>Log in</span>
                            <br>
                            <p>Log in here using your username and password</p>
                            <br>
                        </div>

                        <div>
                            <?php echo $output; ?>
                            <!-- <a href="#" class="btn-face"><i class="fa fa-facebook" aria-hidden="true"></i> Facebook</a> -->
                        </div>

                        <div class="or"><span>OR</span></div>

                        <form action="clients/authenticate.php" method="POST">
                            <input name="uidEmail" type="text" placeholder="Username">
                            <input name="pwdClient" type="password" placeholder="Password">
                            <button type="submit" class="btn-signin">Sign In</button>

                            <a href="javascript:void(0)" class="btn-member btn-fade">Not a member yet? <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                            <a href="javascript:void(0)" class="btn-reset btn-fade">Recover your password <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>

                        </form>
                    </div>

                    <div class="recover-password">
                        <div class="title">
                            <span>Recover Password</span>
                            <p>Enter in the username or email associated with your account</p>
                        </div>

                        <form action="resetpwd.inc.php" method="POST">
                            <input name="email" type="email" placeholder="Username/Email Address" id="resetPassword" required>
                            <span class="error"></span>
                            <button class="btn-signin btn-password">Submit Reset</button>
                        </form>
                        <a href="javascript:void(0)" class="btn-login btn-fade"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Cancel
                            and go back to Login page </a>

                        <div class="notification">
                            <p>Good job. An email containing information on how to reset your passworld was sent to
                                <span class="reset-mail"></span>. Please follow the instruction in that email to
                                reset your password. Thanks!
                            </p>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
    <!-- Popup Modal Ends -->


    <?php include "footer.php"; ?>