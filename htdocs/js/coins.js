$(document).ready(function() {

  $('.expandable').expander();

  $('#coinType').change(function() {
   
   var valueId = $(this).val();
   var url = location.protocol + '//' + location.host + "/us-coins/?valueId=" + valueId;
   
   window.location = url;
  });
  
  $(".content").hide();
  $(".content-show").show();

  $(".heading").click(function() {
    $(this).next(".content").slideToggle(500);

    if ($(this).hasClass('down-arrow')) {
      $(this).removeClass('down-arrow');
      $(this).addClass('up-arrow');
    }
    else {
      $(this).removeClass('up-arrow');
      $(this).addClass('down-arrow');
    }
  });
  
  
  $("section .expand-on-load .heading").click();
  
});
