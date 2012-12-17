$(document).ready(function(){

	//render the wall
	$('#updateStatus').click(function(){
		var status = $('#insertComments').val();
		$('#errorStatus').html("Processing ...")
		//insert wall post
		if(status == ""){
			$('#errorStatus').html("Please put in your status!");
			return;
		}else{
			
			//get current url user id 
			var userid = gup('userID');
			
			var s = "";
			
			//ajax call to insert status
			$.get('insertWall', {userid:userid, status:status},function(data){
				
				//clean out the status box
				$('#insertComments').val("");
				
				$.each(data, function(index){
					//create an update to the wall instantly
					s = "<div class=\"walldiv\"><h5>";
					s += "<a href=\"/viewProfile.php?userID=" + data[index]['posterid'] + "\">" + data[index]['name'] + "</a>";
					s += "<span>" + data[index]['time'] + "</span></h5>";
					s += "<div id=\"msg\">" + status + "</div></div>";
				});
				
				$('#statusWrapper div:first').before(s);
				$('#errorStatus').html("");
			}, 'json');//end of get
			
		}//end of else
	});// end of updateStatus
	
	/////////////////////////////////////////////////////////////////
	//handle inbox control
	////////////////////////////////////////////////////////////////
	//need live event handler to handle in vivo interaction
	$('#inbox ul li span').live('click',function(){
		var requestor = $(this).parent().find('a').attr('userid');
		 
		switch($(this).attr('alt')){
			case "accept": 
				processFriend(requestor, "accept");
				$(this).parent().remove();				
			break;
			
			case "deny": 
				processFriend(requestor, "reject");
				$(this).parent().remove();
			break;
			
			case "delete": alert('delete message but has yet to be implemented!');
			break;
			
		}//end of switch statement
		
	});//end of inbox control
	
	/////////////////////////////////////////////////////////////////
	//handle social circle
	////////////////////////////////////////////////////////////////
	//need live event handler to handle in vivo interaction
	$('#friends ul li span').live('click',function(){
	
		var target = $(this).parent().attr("userid");
		var type = $(this).parent().attr("alt");
		$(this).parent().text("Processing...");
		
		$.get('desistRelationship', {target:target, type:type}, function(data){
		}).success(function(){
			renderSocialLife();
		});//end of get
	});//end of social circle control
	
	//////////////////////////////
	// change password window
	//////////////////////////////
	$('#cdpw').click(function(){
		var markup = "<form id='cdpwf' title='Change Password'>";
			markup += "<lable for='password1'>New Password</label><input type='password' name='password1' id='password1'  maxlength='30' />";
			markup += "<lable for='password2'>Retype Password</label><input type='password' name='password2' id='password2'  maxlength='30' />";
			markup += "<br><span id='errorcp' style='color: red; font-size: 10px;'></span></form>";
		
		$(markup).dialog({
			draggable: false,
				 show: "explode",
				 hide: "explode",
				modal: true,
			resizable: false,
			  buttons:{
				"Change": function(){
					var pw1 = $('input[name*=password1]').val();
					var pw2 = $('input[name*=password2]').val();
					var obj = this;
					if((pw1 == "") || (pw2 == "")){ 
						$('#errorcp').html('one of the field is blank!'); return;
					}else if(pw1 != pw2){
						$('#errorcp').html('passwords are mismatch!'); return;
					}else{
						$('#cdpwf').html('System Processing...');
						$.post('/users/changePass',{password: pw1}, function(data){
							$(obj).dialog("close");
						});//end of post
					}//end of else
				},
				Cancel: function(){
					$(this).dialog("close");
				}
			  }//buttons
		});//end of dialog
		
	});//end of change password
});//end of document ready

//trigger everytime whenever the window loads
$(window).load(function (){
	
	//render wall
	//getTotalWP(); 
	fetchWallPosts();
	
	//render the inbox
	renderInbox();
	
	//render friends
	renderSocialLife();
	
	//fill the recent activities
	getActs();
	
});

///////////////////////////
//detect scroll of window - This is the facebook style of loading wall posts
///////////////////////////	
//This function fetches the wall post for the corresponding user
function fetchWallPosts(){
	//get current url user id 
	var userid = gup('userID');
	
	$.get('renderWall', {userid:userid}, function(data){
		
		if(data == null){
			$('#statusWrapper div:first').after("Feel free to write your wall!");
			$('#moreWallPosts').html("");
			return;
		}else{
			
			var s = "";
			//access and loop through each json object
			$.each(data, function(index){
				//alert(data[index]['username']);
				s += "<div class=\"walldiv\"><h5>";
				s += "<a href=\"/viewProfile.php?userID=" + data[index]['posterID'] + "\">" + data[index]['name'] + "</a>";
				s += "<span>" + data[index]['time'] + "</span></h5>";
			    s += "<div id=\"msg\">" + data[index]['content'] + "</div></div>";					
			});//end of each 
			$('#statusWrapper div').after(s);
		}//end of else
	}, 'json');//end of get
}//end of fetchWallPost 

////////////////////////////////////////////////////////////////////
//This function render the activity of the ones you follow and your friends
////////////////////////////////////////////////////////////////////
function getActs(){
	
	$.get('renderActivities', {}, function(data){
		
		if(data == null){
			$('#recentActivity ul').html("<li>No New Activity</li>");
		}else{
			var s = "";
			
			$.each(data, function(index){
				s += "<li>(" + determinekind(data[index]['kind']) + ") ";
				s += "<a href=\"/index/viewProfile?userID=" + data[index]['posterID'] + "\">" + data[index]['name'] + "</a>";
				s += " posted <a href=\"/forum/viewThread?threadID=" + data[index]['threadID'] + "\">" + data[index]['threadname'] + "</a>";
				s += " on " + data[index]['date'];
				s += "</li>";
			});
			
			$('#recentActivity ul').html(s);
		}//end of else
		
	}, 'json');//end of get call
	
}//end of getActs

function determinekind(kind){
	switch(kind){
		case '1': return 'friend'; break;
		case '2': return 'friendship pending'; break;
		case '3': return 'following'; break;
	}//end of switch
}//end of determinekind
////////////////////////////////////////////////////////////////////
//this function will refresh the inbox everytime when it is called
////////////////////////////////////////////////////////////////////

function renderInbox (){
	$.get('checkInbox', {}, function(data){
			
		var fndReq = "<li><h5>Friend Request</h5></li>";
		
		//abort of the data is non-exist
		if (data == null) {
			$('#inbox ul').html("You have no mail");
			return;
		}else{
		//access and loop through each json object
			$.each(data, function(index){
				fndReq = fndReq + "<li><a href=\"/index/viewProfile?userID=" + data[index]['id'] + "\" userid = " + data[index]['id'] + ">" + data[index]['name'] + "</a>";
				fndReq = fndReq + "<span alt=\"deny\" class=\"ui-icon ui-icon-close\" ></span><span alt=\"accept\" class=\"ui-icon ui-icon-check\"></span></li>";
			});		
			//render the wall with jquery
			$('#inbox ul').html(fndReq);
		}//end of else
	},'json'); 
	return false;

}//end of renderInbox

////////////////////////////////////////////////////////////////////
//this function will refresh the inbox everytime when it is called
////////////////////////////////////////////////////////////////////
function renderSocialLife(){

	$.get('getLife', {}, function(data){	
		
		if (data == null) {
			$('#fnd ul').html("No social life yet!");
			return;
		}else{
		
		var fnd = "<p>Friends</p>";
		var fol = "<p>Follows</p>";
		var fndReq = "<li><h5>Friend Request</h5></li>";
		
			$.each(data, function(index){
			
			if(data[index]['type'] == 1){	
					fnd = fnd + "<li userid=" + data[index]['id'] + "><a href=\"/index/viewProfile?userID=" + data[index]['id'] + "\">";
					fnd = fnd + "<img src=\"" + data[index]['headshot'] + "\" alt=\"friendshot\">";
					fnd = fnd + "" + data[index]['name'] + "</a> <span class=\"ui-icon ui-icon-trash\"></span>";
					fnd = fnd + "</li>";
			}else{
					fol = fol + "<li userid=" + data[index]['id'] + "><a href=\"/index/viewProfile?userID=" + data[index]['id'] + "\">";
					fol = fol + "<img src=\"" + data[index]['headshot'] + "\" alt=\"friendshot\">";
					fol = fol + "" + data[index]['name'] + "</a> <span class=\"ui-icon ui-icon-trash\"></span>";
					fol = fol + "</li>";}
			});//end of each
		//render the wall with jquery
		$('#fnd ul').html(fnd + fol);
		}//end of else	
	},'json');//end of get 
}//end of renderSocialLife


//This function is used for taking care of accepting friendship
function processFriend(requestor, choice){
	$.get('processFriend', {requestor:requestor, choice:choice}, function(data){
	}).success(function(){
		renderSocialLife();
	}); 
}//end of accept friendship

//get url data
function gup( name ){
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");  
	var regexS = "[\\?&]"+name+"=([^&#]*)";  
	var regex = new RegExp( regexS );  
	var results = regex.exec( window.location.href ); 
	if( results == null ) return "";  
	else    return results[1];
}//end of gup