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
    $output = '<a class="btn-google" href="' . filter_var($authUrl, FILTER_SANITIZE_URL) . '">Google</a>';
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
    <style>
        #business-list li {
            padding: 8px;
            background: #f0f0f0;
            border-bottom: #bbb9b9 1px solid;
            border-radius: 50px;
        }

        .add_business {
            background: #d0d0d0 !important;
        }

        @media (min-width: 992px) {
            .modal-lg {
                max-width: 80%;
                width: 80%;
            }
        }
    </style>
</head>

<body>
    <?php include "menu.php"; ?>

    <main>


        <div class="slider-area hero-overly">
            <div class="single-slider hero-overly  slider-height d-flex align-items-center">
                <div class="container-fluid">
                    <br>
                    <br>

                    <div class="modal-content">

                        <div class="wrapper">
                            <div style="width: 625px;" class="sign-panels sign-panels-review">

                                <div class="review">
                                    <div class="title-review">
                                        <span>Review Contractor</span>
                                    </div>

                                    <div class="wrap">
                                        <div class="search">
                                            <form action="review_submit.php" method="POST" autocomplete="off">
                                                <input type="hidden" id="sessionStatus" value="<?php echo (isset($_SESSION['status_msg'])) ?  $_SESSION['status_msg'] : null; ?>" />
                                                <input autocomplete="false" name="hidden" type="text" style="display:none;">
                                                <input style="width: 30em; color: black; box-shadow: 0 0 5px rgb(0 0 0 / 0.16);" id="bname" name="bname" type="text" class="searchTerm" placeholder="Business name..." required>
                                                <input id="bizID" name="bizID" type="hidden" style="display:none;">
                                                <div id="suggesstion-box"></div>
                                        </div>
                                    </div>
                                    <br>

                                    <!-- <form action="reviews.inc.php" method="POST"> -->
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
                                            <label for="review" style="font-weight: 600;">Please leave your review</label>
                                        </div>
                                        <textarea col=10 rows=5 type="text" name="review" id="review" class="leave-review-input" required></textarea>
                                    </div>

                                    <button class="btn-signin btn-login btn-submit ">Submit</button>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
    </main>
    <!-- Popup Modal Start -->
    <div class="modal" id="businessModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add Business</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="add_business.php" method="POST">
                                <div class="my-4">
                                    <div class="align-left">
                                        <label for="" style="font-weight: 600;">Business Name</label>
                                    </div>
                                    <input class="form-control" type="text" name="bname" required>
                                </div>
                                <div class="my-4">
                                    <div class="align-left">
                                        <label for="" style="font-weight: 600;">Category</label>
                                    </div>
                                    <input class="form-control" type="text" name="type" required>
                                </div>
                                <div class="my-4">
                                    <div class="align-left">
                                        <label for="" style="font-weight: 600;">Phone #</label>
                                    </div>
                                    <input class="form-control" type="text" name="phone" id="PhoneInput" required>
                                    <script type="text/javascript" src="/js/phone-format.js" ></script>
                                </div>
                                <div class="my-4">
                                    <div class="align-left">
                                        <label for="" style="font-weight: 600;">Zip</label>
                                    </div>
                                    <input class="form-control" type="text" name="zip" required>
                                </div>
                                <div class="my-4">
                                    <input hidden class="form-control" type="text" name="userAdd" value="1" required>
                                </div>
                                <div class="my-4 text-center">
                                    <button class="btn-signin btn-login btn-submit" type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Popup Modal Ends -->
    <?php include "footer.php"; ?>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script>
   
        // AJAX call for autocomplete 
        $(document).ready(function() {
            showAlert();

            $("#bname").on('click', function(e) {
                e.preventDefault();
            });
            $("#bname").on('input', function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "clients/getBusinessName.php",
                    data: 'bname=' + $(this).val(),
                    success: function(data) {
                        $("#suggesstion-box").show();
                        $("#suggesstion-box").html(data);
                        $("#bname").css("margin-bottom", "5px");
                        $("#bname").css("background", "#FFF");
                    }
                });
            });
        });
        //To select Business name
        function selectBusiness(val, id) {
            $("#bname").val(val);
            $("#bizID").val(id);
            $("#suggesstion-box").hide();
            $("#bname").css("background", "#f0f0f0");
            $("#bname").css("margin-bottom", "15px");

        }

        function showAlert() {
            if ($("#sessionStatus").val()) {
                alert($("#sessionStatus").val());
            }
        }
    </script>
    <?php
    unset($_SESSION['status_msg']);
    ?>

<script type="text/javascript" src="./js/phone-format.js" ></script>