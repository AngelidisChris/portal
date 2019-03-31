$(document).ready(function()
{
   $("#showhide").click(function()
   {
      if ($(this).data('val') == "1")
      {
         $("#password").prop('type','text');
         $("#eye").attr("class","glyphicon glyphicon-eye-close");
         $(this).data('val','0');
      }
      else
      {
         $("#password").prop('type', 'password');
         $("#eye").attr("class","glyphicon glyphicon-eye-open");
         $(this).data('val','1');
      }
   });
});

$(document).ready(function()

{
   $("#remove").click(function()
   {
     $("#uname").val('');
   });

});

$(document).ready(function(){

//minimum 6 characters
var bad = /(?=.{6,}).*/;
//Alpha Numeric plus minimum 6
var good = /^(?=\S*?[a-z])(?=\S*?[0-9])\S{6,}$/;
//Must contain at least one upper case letter, one lower case letter and (one number OR one special char).
var better = /^(?=\S*?[A-Z])(?=\S*?[a-z])((?=\S*?[0-9])|(?=\S*?[^\w\*]))\S{6,}$/;
//Must contain at least one upper case letter, one lower case letter and (one number AND one special char).
var best = /^(?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9])(?=\S*?[^\w\*])\S{6,}$/;

$('#password').on('keyup', function () {
    var password = $(this);
    var pass = password.val();
    var passLabel = $('[for="password"]');
    var stength = 'Αδύναμος';
    var pclass = 'danger';
    if (best.test(pass) == true) {
        stength = 'Πολύ δυνατός';
        pclass = 'success';
    } else if (better.test(pass) == true) {
        stength = 'Δυνατός';
        pclass = 'success';
    } else if (good.test(pass) == true) {
        stength = 'Σχεδόν δυνατός';
        pclass = 'warning';
    } else if (bad.test(pass) == true) {
        stength = 'Αδύναμος';
        pclass = 'warning';
    } else {
        stength = 'Πολύ αδύναμος';
    }

    var popover = password.attr('data-content', stength).data('bs.popover');
    popover.setContent();
    popover.$tip.addClass(popover.options.placement).removeClass('danger success info warning primary').addClass(pclass);

});

$('input[data-toggle="popover"]').popover({
    placement: 'top',
    trigger: 'focus'
});

});
