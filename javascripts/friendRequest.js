$(document).ready(function(){
	
	//live interaction with the buttons
	$('#control li a').live('click',function(){
		var obj = $(this);
		obj.hide();
		var userid = gup('userID');
		switch(obj.attr('id')){
			case 'fr': friendrequest(obj, userid); break;
			case 'fo': followuser(obj, userid); break;
			default : desistRelationship(obj, userid); break;
		}//end of switch
	});//end of live
	
});//end of document ready

//friend request
function friendrequest(obj, userid){
	$.get('sendFriendRequest', {userid: userid}).success(function(){
		obj.parent().parent().html('<li>Friendship Pending</li><li><a id="kr">Delete Friend Request</a></li>')
	});//end of get
}//end of friendrequest

function followuser(obj, userid){
	$.get('followUser', {userid: userid}).success(function(){
		obj.parent().parent().html('<li><a id="fr">Friend Request</a></li><li><a id="uf">Unfollow</a></li>')
	});//end of get
}//end of friendrequest

function desistRelationship(obj, userid){
	$.get('/users/desistRelationship', {target: userid}).success(function(){
		obj.parent().parent().html('<li><a id="fr">Friend Request</a></li><li><a id="fo">Follow</a></li>')
	});//end of get
}//end of friendrequest

function gup( name ){
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");  
	var regexS = "[\\?&]"+name+"=([^&#]*)";  
	var regex = new RegExp( regexS );  
	var results = regex.exec( window.location.href ); 
	if( results == null ) return "";  
	else    return results[1];
}//end of gup
