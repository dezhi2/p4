$(document).ready(function(){
	
	//this function will switch the sign in form to registration form
	$('#regform').click(function(){	
		$('#msg').html("");	
		
		$('#loginform').hide('fade', 1000, function(){
			$('#register').show('fade',1000);
		});
	});
	
	//this function reverse the above, it returns back to log in form
	$('#rtn').click(function(){
		$('#msg').html("");	
		
		$('#register').hide('fade', 1000, function(){
			$('#loginform').show('fade',1000);
		});
	});
	
	//check login email address
	$('#login_email').blur(function(){
		if(!isValidEmailAddress($(this).val())){
			$('#logIn').attr("disabled", "disabled");
			$('#msg').html("That is not an email address!");
		}else{
			$('#logIn').removeAttr("disabled");   
			$('#msg').html("");	
		}
	});
	
	//log in form clicked submit
	$('#logIn').click(function(){
		
		//loading icon
		$('#ajaxloader').css("display", "block");
		
		//check if the fields are empty
		if(($('#login_email').val().length && $('#login_password').val().length) == 0){
			$('#msg').html("Please type in your log in email address and password.");
			$('#ajaxloader').css("display", "none");
			return; 
		}
		
		//ajax call
		$.post('/users/login', $('#logOn').serialize(), function(data){
			var returndata = $.trim(data);
			if(returndata == 'okay'){
				//route the user to his/her profile	
				window.location.replace("/users/profile");
			}else{
				$('#msg').html(returndata);
				$('#ajaxloader').css("display", "none");
			}
		});
		return false;
	});
	
	//sign up form processing
	$('#toReg').click(function(){
		//loading icon
		$('#ajaxloader').css("display", "block");
		
		if(($('#regEmail').val().length || $('#regName').val().length || $('#testPW').val().length || $('#testPWc').val().length) == 0){
			$('#msg').html("Missing fields");
			$('#ajaxloader').css("display", "none");
			return;
		}else{$('#msg').html();}
		
		//check if the email is in valid format
		if(!isValidEmailAddress($('#regEmail').val())){
			$('#msg').html("Bad Email Address!");
			$('#ajaxloader').css("display", "none");
			return;
			}else{$('#msg').html();}
			
		//check if the password and the confirm password are the same
		if($('#testPW').val() != $('#testPWc').val()){
			$('#msg').html("Passwords mismatch!");
			$('#ajaxloader').css("display", "none");
			return;
			}else{$('#msg').html();}
		
		$.post('/users/signup', {email: $('#regEmail').val(), name: $('#regName').val(), password: $('#testPW').val(), dateofbirth: $('#bday').val()}, function(data){
			var returndata = $.trim(data);
			$('#msg').html(returndata);
			$('#ajaxloader').css("display", "none");
		});return false;
	});//end of sign up validation
	
});//end of document.ready

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};

//handle enter
$(document).keypress(function(e) {
    if(e.which == 13) {
        ($('#loginform').css("display") == 'block') ? $('#logIn').trigger('click') : $('#toReg').trigger('click') ;
    }
});