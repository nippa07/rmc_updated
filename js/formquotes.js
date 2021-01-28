$(document).ready(function(){  
    var form_count = 1, form_count_form, next_form, total_forms;
    total_forms = $("fieldset").length;  
    $(".next").click(function(){
      form_count_form = $(this).parent();
      next_form = $(this).parent().next();
      next_form.show();
      form_count_form.hide();
      setProgressBar(++form_count);
    });  
    $(".previous").click(function(){
      form_count_form = $(this).parent();
      next_form = $(this).parent().prev();
      next_form.show();
      form_count_form.hide();
      setProgressBar(--form_count);
    });
    setProgressBar(form_count);  
    function setProgressBar(curStep){
      var percent = parseFloat(100 / total_forms) * curStep;
      percent = percent.toFixed();
      $(".progress-bar")
        .css("width",percent+"%")
        .html(percent+"%");   
    } 
  
    $(document).ready(function() {
      $('#user_form').keydown(function() {
      var key = e.which;
        if (key == 13) {
      
  
    $('#user_form').submit(function(event){
      $.post("./getquotes/leads.php", $("user_form").serialize());
    });
  };
      }
    
      )})});