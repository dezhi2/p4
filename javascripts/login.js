$(document).ready(function(){
	var s = '<form id="lnform" title="Log on" style="display: none;">';
	   s += '<fieldset >';
	   s += '<label for="email" >Email*:</label>';
	   s += '<input type="text" name="email" id="email"  maxlength="30" />';
	   s += '<label for="password" >Password*:</label>';
	   s += '<input type="password" name="password" id="password" maxlength="30" />';
	   s += '<span id="error"></span>';
	   s += '</fieldset>';
	   s += '<a id="forgot" href="#">1. Crap! I forgot my password :-(</a><br>';
	   s += '<a href="/index/regOrlog">2. Not yet a member? Register Now :-)</a>';
	   s += '</form>';
	
	var s2 =  '<form id="getPW" title="Retrieve Password" style="display: none;">';
		s2 += '<fieldset ><label for="email" >Email*:</label>';
	    s2 += '<input type="text" name="email" id="email"  maxlength="30" /></fieldset>';
		s2 += '<a id="recall" href="#" style="font-size: 0.7em; text-decoration: none; color: skyblue;">Oh! never mind :-)</a><br>';
		s2 += '<span></span>';
		s2 += '</form>';
		
	$('body').append(s);
	$('body').append(s2);
	
	$('#login').live('click',function(event){
		event.preventDefault();
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
				var obj = this;
				$('#forgot').live('click',function(){$(obj).dialog( "close" );});
			}
		});//end of dialog
	});//end of login
	
	$('#forgot').live('click', function(event){
		event.preventDefault();
		$('#getPW').dialog({
		  draggable: false,
			   show:"fade",
			   hide:"explode",
			  modal: true,
		  resizable: false,
			buttons: {
				"Get New PW": function() {
					var obj2 = $(this).find('span');
					obj2.html('Processing... <img src="../images/signalloader.gif" alt="loader">');
					var email = $(this).find('input[name*=email]').val();
					if((email == "") || (!isValidEmailAddress(email))){
						obj2.html('Please enter a valid email address!');
					}else{
						$.post('/users/resetPW', {email:email},function(data){
							var data = $.trim(data);
							if(data == 'okay'){
								obj2.html('Check your email for the new password');
							}else{
								obj2.html(data);
							}//end of else
						});//end of get
					}//end of else
                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                }
			},
			open: function(){
				var obj = this;	
				$('#recall').live('click',function(){$(obj).dialog( "close" ); $('#login').click();});
			}
		});//end of dialog
	});//end of forgot
	
	$(window).keypress(function(e){
		if(e.keyCode == 13){ e.preventDefault(); }
	});
	
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