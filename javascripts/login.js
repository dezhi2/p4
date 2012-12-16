$(document).ready(function(){
	var s = '<form id="lnform" title="Log on">';
	   s += '<fieldset >';
	   s += '<label for="email" >Email*:</label>';
	   s += '<input type="text" name="email" id="email"  maxlength="30" />';
	   s += '<label for="password" >Password*:</label>';
	   s += '<input type="password" name="password" id="password" maxlength="30" />';
	   s += '<span id="error"></span>';
	   s += '</fieldset>';
	   s += '<a href="#">1. Crap! I forgot my password :-(</a><br>';
	   s += '<a href="/index/regOrlog">2. Not yet a member? Register Now :-)</a>';
	   s += '</form>';
	   
	$('body').append(s);
	
	$('#login').live('click',function(){
		$('#lnform').dialog({
		  draggable: false,
			   show:"fade",
			   hide:"explode",
			  modal: true,
		  resizable: false,
			buttons: {
				"Log on": function() {
					var obj2 = $(this).find('span');
					obj2.html('Now Loading... <img src="../images/signalloader.gif" alt="loader">');
					processLogin(this, obj2);
                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                }
			},
			open: function(){
				$(this).keypress(function(e){
					(e.keyCode == 13) ? $(':button:contains("Log on")').click() : {};
				});//end of key press
			}
		});//end of dialog
	});//end of login
});//end of document ready

$(window).load(function(){
	$('head').append('<link rel="stylesheet" href="../css/login.css" type=""type/css>');
});

function processLogin(obj, obj2){
	var email = $(obj).find('input[name*=email]').val();
	var password = $(obj).find('input[name*=password]').val();
	
	if((email == "") || (password == "")){
		obj2.html('**Please supply a username & password');
		return;
	}else if(!isValidEmailAddress(email)){
		obj2.html('**Your email is invalid');
		return;
	}else{
		$.post('/users/login',{email:email, password:password}, function(data){
			if($.trim(data) == 'okay'){
				location.reload();
			}else{
				obj2.html(data);
			}
		});//end of post
	}
}//end of form processing


function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};