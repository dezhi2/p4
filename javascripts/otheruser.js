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
			$.get('/users/insertWall', {userid:userid, status:status},function(data){
				
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
	
	
});//document ready

//load the wallpost and friend list
$(window).load(function (){
	fetchWallPosts();
	renderSocialLife();
});

function fetchWallPosts(){
	//get current url user id 
	var userid = gup('userID');
	
	$.get('/users/renderWall', {userid:userid}, function(data){
		
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

function renderSocialLife(){
	$.get('getOtherLife', {userid:gup('userID')}, function(data){	
		
		if (data == null) {
			$('#fnd ul').html("No social life yet!");
			return;
		}else{
		var fnd = "";		
			$.each(data, function(index){
			
			if(data[index]['type'] == 1){	
					fnd = fnd + "<li userid=" + data[index]['id'] + "><a href=\"/index/viewProfile?userID=" + data[index]['id'] + "\">";
					fnd = fnd + "<img src=\"" + data[index]['headshot'] + "\" alt=\"friendshot\">";
					fnd = fnd + "" + data[index]['name'] + "</a>";
					fnd = fnd + "</li>";}
			});//end of each
		//render the wall with jquery
		$('#fnd ul').html(fnd);
		}//end of else	
	},'json');//end of get 
}//end of renderSocialLife

//get url data
function gup( name ){
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");  
	var regexS = "[\\?&]"+name+"=([^&#]*)";  
	var regex = new RegExp( regexS );  
	var results = regex.exec( window.location.href ); 
	if( results == null ) return "";  
	else    return results[1];
}//end of gup