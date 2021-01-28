<header>
        <!-- Header Start -->
       <div class="header-area">
            <div class="main-header">
               <div class="header-bottom">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <!-- Logo -->
                            <div class="col-xl-2 col-lg-2 col-md-1">
                                <div class="logo">
                                  <a href="../index.php"><img class="logo-img" src="../assets/img/logo/logo.png" alt=""></a>
                                </div>
                            </div>
                            <div class="col-xl-10 col-lg-10 col-md-8">
                                <!-- Main-menu -->
                                <div class="main-menu f-right d-none d-lg-block">
                                    <nav>
                                        <ul id="navigation">                                                                                                                                     
                                            <!-- <li hidden class="myBtn btn-submit " data-toggle="modal" data-target="#myModal"><a>Write Review</a></li> -->
                                            <!-- <li><a href="reviews.php">Write Review</a></li> -->
                                            <li><a href="home.php" id="">Home</a></li>
                                            <li><a href="profile.php" id="">Profile</a></li>
                                            <li><a href="logout.php">Log out</a></li>
                                            <!-- <li class="btn-nav"><a href="/biz/bizsignup.php">Business Owners</a></li> -->
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <!-- Mobile Menu -->
                            <div class="col-12">
                                <div class="mobile_menu d-block d-lg-none"></div>
                            </div>
                        </div>
                    </div>
               </div>
            </div>
       </div>
        <!-- Header End -->
    </header>

    <body>
    <div class="s01">
    <form action="clientSearch.php" method="POST">
      <div class="inner-form">
        <div class="input-field first-wrap">
          <input name="bname" id="search" type="text" value= "" placeholder= "Painting, Tiling, Handyman, Etc.">
        </div>
        <div class="input-field second-wrap">
          <input name="zip" id="location" type="text" value="" placeholder="Zip Code or City">
        </div>
        <div class="input-field third-wrap">
          <button class="btn-search" type="submit">Search</button>
        </div>
      </div>
    </form>
  </div>
  

  <div class="container-fluid">
      <div class="row">
          <div class="margin">
          <a class="a2" href="getEstimates.php">Request Estimates</a>
          <a class="a1">|</a>
          <a class="a2" href="clientReviews.php">Write Review</a>
          <a class="a1">|</a>
          <a class="a2" href="diy.php">DIY - Ask a Pro</a>
          <a class="a1">|</a>
          <a class="a2" href="trueValue.php">True Value</a>
        </div>
      </div>
  </div>