(function ($)
  { "use strict"
  
/* slick Nav */
// mobile_menu
    var menu = $('ul#navigation');
    if(menu.length){
      menu.slicknav({
        prependTo: ".mobile_menu",
        closedSymbol: '+',
        openedSymbol:'-'
      });
    };


/* Nice Selectorp  */
  var nice_Select = $('select');
    if(nice_Select.length){
      nice_Select.niceSelect();
    }


})(jQuery);



// Overlay Start
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementsByClassName("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
btn[0].onclick = function() {
  modal.style.display = "block";
};

btn[1].onclick = function() {
  modal.style.display = "block";
};

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
};

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
};

// Overlay End


// Sign in, Sign up, Confirmation Popup Start 

// $('.login').hide();
$('.login').hide();
$('.signup').hide();
$('.recover-password').hide();

$('.btn-reset').click(function () {
    $('.login').hide();
    $('.recover-password').fadeIn(300);
});

$('.btn-member').click(function () {
    $('.login').hide();
    $('.signup').fadeIn(300);
});

$('.btn-login').click(function () {
    $('.review').hide();
    $('.signup').fadeIn(300);
    $('.login').hide();
    $('.recover-password').hide();
    

});

$('.btn-login-signin').click(function () {
    $('.signup').hide();
    $('.login').fadeIn(300);

});




$('.notification').hide();

$('.btn-password').click(function () {

    if($('#resetPassword').val()==0) {
        // $('#resetPassword').after('<span class="error">Email not valid.</span>')
        $('.error').text('Email not valid.')
    }

   else {
        $('.reset-mail').text($('#resetPassword').val());
        $('.recover-password form').hide();
        $('.notification').fadeIn(300);
    }
});

// Sign in, Sign up, Confirmation Popup End


$(document).ready(function(){
  $(".close").click(function(){
      location.reload(true);
  });
});


$(function() {
  $('#myModal').modal({
    show: false,
    keyboard: false,
    backdrop: 'static'
  });
});
