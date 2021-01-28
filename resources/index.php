<?php
// Require https
if ($_SERVER['HTTPS'] != "on") {
    $url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="RateMyContractor was created with one goal in sight - to help people connect with the best contractor for their project. We are a growing compilation of homeowner’s contractor reviews with local contractors. The people who join Rate My Contractor are not only interested in sharing their experience but are looking for a trustworthy service professional that will perform the high quality work that all homeowners deserve.">
  <meta name="keywords" content="Rate my contractor, Contractor Reviews, Contractor Ratings, Hire the Best, Best Contractor">
  <link rel="shortcut icon" href="./resources/favicon.ico" type="image/x-icon">
  <link rel="icon" href="./resources/favicon.ico" type="image/x-icon">
  <title>Rate My Contractor | California Find the Best Contractor</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/index.css" rel="stylesheet">
  <link href= "https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="text/stylesheet"

</head>

<body>

  <?php include("menu.php"); ?>
<br>
<br>
  <header>
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <!-- Slide One - Set the background image for this slide in the line below -->
        <div class="carousel-item active" style="background-image: url('./resources/2018-0326-find-it-duke-hero_hero.jpg')">
          <div class="carousel-caption d-none d-md-block">
            <h2><strong> Choose Who You Want</strong></h2>
          </div>
        </div>
        <!-- Slide Two - Set the background image for this slide in the line below -->
        <div class="carousel-item" style="background-image: url('./resources/hire the best.jpg')">
          <div class="carousel-caption d-none d-md-block">
            <h2><strong>Hire The Best</strong></h2>
          </div>
        </div>
        <!-- Slide Three - Set the background image for this slide in the line below -->
        <div class="carousel-item" style="background-image: url('./resources/forget the rest.jpg')">
          <div class="carousel-caption d-none d-md-block">
            <h2><strong>Forget The Rest!</strong></h2>
          </div>
        </div>
      </div>
      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </header>

  <!-- Page Content -->
  <div class="container-fluid">
  <div class="s01">
    <form action="./search/find.php" method="POST">
      <div class="inner-form">
        <div class="input-field first-wrap">
          <input name="bname" id="search" type="text" placeholder=" E.g. Painting, Plumbing, Handyman...">
        </div>
        <div class="input-field second-wrap">
          <input name="zip" id="location" type="text" placeholder="Zip Code or City">
        </div>
        <div class="input-field third-wrap">
          <button class="btn-search" type="submit">Search Pros</button>
        </div>
      </div>
    </form>
  </div>
  </div>
    <!-- Marketing Icons Section -->
    <div class="container-fluid">
    <div class="row w-75 mx-auto" style="height: 15rem;">
      <div class="col-lg-4 mb-5">
        <div class="card h-100">
          <h3 class="card-header">Have A Project?</h3>
          <div class="card-body">
            <h4 class="card-text">Click below to find the top rated contractors in your area.</h4>
          </div>
          <div >
            <img class="card-img-bottom" src="https://www.mrsvangelista.com/wp-content/uploads/2018/11/project-management-6.jpg">
          </div>
          <div class="card-footer">
            <a href="search.php" class="btn btn-primary btn-block btn-lg">Search Contractors</a>
          </div>
        </div>
      </div>
      <div class="col-lg-4 mb-5">
        <div class="card h-100">
          <h3 class="card-header">Want to Rate A Contractor?</h3>
          <div class="card-body">
            <h4 class="card-text">Click below and sign in to start rating all your professionals.</h4>
          </div>
          <div>
            <img class="card-img-bottom" src="https://blackstormdesign.com/wp-content/uploads/2017/01/5starreviewer3-2.png">
          </div>
          <div class="card-footer">
            <a href="hosignup.php" class="btn btn-primary btn-block btn-lg">Review Contractors</a>
          </div>
        </div>
      </div>
      <div class="col-lg-4 mb-5">
        <div class="card h-100">
          <h3 class="card-header">Are You A Contractor?</h3>
          <div class="card-body">
            <h4 class="card-text">Click below to sign up and start managing your own profile to attract clients.</h4>
          </div>
          <div>
            <img class="card-img-bottom" src="https://realestateblog.reiclub.com/wp-content/uploads/2e1ax_default_entry_istock_000016132777xsmall.jpg">
          </div>
          <div class="card-footer">
            <a href="pricing.php" class="btn btn-primary btn-block btn-lg">Join The Team</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid mx-0 px-0">
    <div class="row w-100 mx-auto" style="margin-top: 26rem;">
      <div class="col">
        <div class="card" style="border: 0 !important; margin-left: -2rem !important; margin-right: -2rem !important;margin-bottom: -3rem !important;">
          <img class="card-img" style="height: 30rem; width: 100% !important;" src="https://www.99.co/blog/singapore/wp-content/uploads/2017/06/irresponsible-contractor-cover-900x386.jpg">
          <div class="card-img-overlay">
          <h1 id= text1 class="card-title mx-auto" style="margin-top: 6rem; width:50%;">
            A Little About Us
          </h1>
              <h3 id="text" class="card-text mx-auto" style="width: 50%;">
                RateMyContractor was created with one goal in sight - to help people connect with the right contractor for their project. We are a growing compilation of homeowner’s experiences with local contractors. The people who join Rate My Contractor are not only interested in sharing their experience but are looking for a trustworthy service professional that will perform the high quality work that all homeowners deserve.
              </h3>
            </div>
        </div>
      </div>
    </div>
  </div>
<?php include('footer.php'); ?>
</body>
</html>
