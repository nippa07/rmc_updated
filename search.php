<?php

if (!isset($_GET['type'])) {
  $type= null;
}else {
  $type= $_GET['type'];
}

$user_ip = getenv('REMOTE_ADDR');
$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
$city = $geo["geoplugin_city"];
?>


<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Rate My Contractor is meant for homeowners who are looking to find reliable contractors to work on their homes. Here they can find contractor ratings on local contractors who will do the work for them. Rate My Contractor is a growing population of homeowner reviews. These local reviews will help owners find a high quality contractor for the job. ">
    <meta name="keywords" content="reliable contractors, contractor ratings, local contractors, contractor near me, local reviews">
    <meta name="author" content="">
    <link rel="shortcut icon" href="./resources/favicon.ico" type="image/x-icon">
    <link rel="icon" href="./resources/favicon.ico" type="image/x-icon">
    <title>Rate My Contractor | Find A Contractor</title>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="colorlib.com">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet" />
    <!-- CSS here -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/slicknav.css">
        <link rel="stylesheet" href="assets/css/slick.css">
        <link rel="stylesheet" href="assets/css/nice-select.css">
        <link rel="stylesheet" href="assets/css/style.css">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="css/main.css" rel="stylesheet" />
  </head>


    <body>

  <?php include "menu.php"; ?>
        
    
<div class="s01">
    <form action="./search/find.php" method="POST">
      <div class="inner-form">
        <div class="input-field first-wrap">
          <input name="bname" id="search" type="text" value= "<?php echo $type ?>" placeholder= "Painting, Tiling, Handyman, Etc.">
        </div>
        <div class="input-field second-wrap">
          <input name="zip" id="location" type="text" value="<?php echo $city ?>" placeholder="Zip Code or City">
        </div>
        <div class="input-field third-wrap">
          <button class="btn-search" type="submit">Search</button>
        </div>
      </div>
    </form>
  </div>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css"></script>


</body>
</html>