<?php 
include 'header.php';
?>
<title>Contractor Signup | Rate My Contractor</title>
<meta name="keywords" content="Cheap Leads, Contractor, Business Advertising, Lead Generation, get Referrals">
<meta name="description" content="Web based lead generation for contractors based on homeowner reviews. Get referrals for your contracting business and make money today. Cheap leads and business advertising on the fly setup your account now.">
<link rel="shortcut icon" href="../resources/favicon.ico" type="image/x-icon">
<link rel="icon" href="../resources/favicon.ico" type="image/x-icon">
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
  <script src="https://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
  <script src="../js/jquery.lookupbox.js"></script>
  <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.10.3/themes/ui-lightness/jquery-ui.min.css" />
  <link rel="stylesheet" type="text/css" href="../css/lookupbox.css" />
<script type="text/javascript" src="script/form.js"></script>

<style type="text/css">
  #user_form fieldset:not(:first-of-type) {
    display: none;
  }
</style>
</head>

<body>
  
<?php include './bizmenu.php'; ?> 
  
  <div class="container">
    <div class="row">
      <div id="card2" class="col-lg-10 col-xl-9 mx-auto">
        <div class="card card-signin flex-row my-5">
          <div class="card-img-left d-none d-md-flex">
		  </div>
          <div id="card" class="card-body">
            <h2 class="card-title text-center">Signup</h2>

<div class="container">
	<h5>  </h5>		
	<div class="progress">
	<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
	</div>
	<div class="alert alert-success hide"></div>	
	<form id="user_form" name="user_form" action="form_action_paying.php"  method="post" enctype="multipart/form-data">	
	<fieldset id='one'>
	<h2>Step 1: Add Account Details</h2>
	<div class="form-group">
	<label for="email">*Username</label>
	<input type="text" class="form-control" required id="uid" name="uidUsers" placeholder="Username">
	</div>
	<div class="form-group">
	<label for="password">*Password</label>
	<input type="password" class="form-control" name="pwdUsers" id="pwd" placeholder="Password">
	</div>
	<div class="form-group">
	<label for="password">*Password Repeat</label>
	<input type="password" class="form-control" name="pwd-repeat" id="pwd-repeat" placeholder="Password Repeat">
	</div>
	<input type="button" id="button" class="next btn btn-info" value="Next" />
	</fieldset>	
	<fieldset id="two">
	<h2> Step 2: Add Business Info - <input class="btn btn-primary" type="button" value="Click To Check Database" id="lookup1" /></h2>
	<div class="form-group" hidden>
	<label>*Required</label>
	<input type="text" class="form-control" name="id" id="id">
	</div>
	<script>
    $(document).ready(function () {
      $("#lookup1").lookupbox({
        title: 'Search For Your Info',
        url: 'user.php?chars=',
        imgLoader: 'Loading...',
        width: 700,
        onItemSelected: function(data){
          $('input[name=id]').val(data.id);
          $('input[name=bname]').val(data.bname);
          $('input[name=phone]').val(data.phone);
          $('input[name=city]').val(data.city);
          $('input[name=state]').val(data.state);
          $('input[name=zip]').val(data.zip);
  
        },
		tableHeader: ['ID', 'Name', 'Phone', 'State', 'Zip'],
		hiddenFields: ['city']
      });
    });
    </script>
	<div class="form-group">
	<label>*Required</label>
	<input type="text" class="form-control" name="bname" required id="bname" placeholder="Business Name">
	</div>
	
	<div class="form-group">
	<label>*Required</label>
	<input type="text" class="form-control" name="fname" required id="fname" placeholder="First Name">
	</div>
	<div class="form-group">
	<label>*Required</label>
	<input type="text" class="form-control" name="lname" required id="lname" placeholder="Last Name">
	</div>
	<input type="button" name="previous" class="previous btn btn-default" value="Previous" />
	<input type="button" id="button" name="next" class="next btn btn-info" value="Next" />
	</fieldset>
	
	<fieldset id="three">
	<h2>Step 3: Add Contact Information</h2>
	<div class="form-group">
	<label>*Required</label>
	<input type="text" class="form-control" name="phone" required id="newContractorPhoneInput" placeholder="Mobile Number">
	</div>
	<div class="form-group">
	<label>*Required</label>
	<input type="email" class="form-control" required id="email" name="emailUsers" placeholder="Email">
	</div>
	<div class="form-group">
	<label>Website:</label>
	<input type="text" class="form-control" id="web" name="web" placeholder="http://">
	</div>
	<input type="button" name="previous" class="previous btn btn-default" value="Previous" />
	<input type="button" id="button" name="next" class="next btn btn-info" value="Next" />
	</fieldset>

	<fieldset>
	<h2>Step 4: Add Work Area</h2>
	<div class="form-group">
	<label>*Required</label>
	<input type="text" class="form-control" name="city" required id="city" placeholder="City">
	</div>
	<div class="form-group">
	<label>*Required</label>
	<input type="text" class="form-control" required id="state" name="state" placeholder="State">
	</div>
	<div class="form-group">
	<label>*Required</label>
	<input type="text" class="form-control" required id="zip" name="zip" placeholder="Zip Code">
	</div>
	<input type="button" name="previous" class="previous btn btn-default" value="Previous" />
	<input type="submit" name="submit" id="submit" class="submit btn btn-success" value="Submit" />
	</fieldset>
	</form>
</div>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>
</div>
</div>
</div>
</div>

<?php include 'footer.php';?> 
<script type="text/javascript" src="../modules/review/js/phone-format.js"></script>