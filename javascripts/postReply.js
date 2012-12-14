$(document).ready(function(){
	
	 var options = {width: 780,height: 200};
     var editor = $("#userReply").cleditor(options)[0];
	  $(document).pasteImage($('iframe')); //plugin from Derrick
	 //button event handler
	 $('#replybtn').click(function(){ 
		$('#error').html("Processing...");
		if($('#userReply').val() == "") {
			$('#error').html("Your reply is blank!");
			return;}
	 
		var results = splitTextImage($('#userReply').val());
		var threadID =  gup('threadID');
	
		$.post('replyThread',{threadID: threadID, textdata : results.textdata, imgdata : results.imgdata, counter : results.counter}, function(data){
			//re-route to posted page
			window.location.href = data; 
		});//end of post
	});
	 
	 
	 $('#cleanbtn').click(function(){editor.clear();});//end of clean button
});//end of document ready

function splitTextImage(data){
	
	var textArr = ""; //return string
	var imgArr = []; // return image data array
	
	//split data into an array
	data = data.split('<img src="data:image/png;base64,');
	textArr += data[0]; //store initial data string
	
	for(var i = 1; i < data.length; i++){
		var local = data[i].split('" width="600px;" height="400px;">');
		imgArr.push(local[0]); //first half is image data
		textArr += '<img src=#PLACEHOLDER(' + i + ')>' + local[1]; //store place holder with what is left	
	}//end of for loop
	
	return {textdata: textArr, imgdata: imgArr, counter:(data.length - 1)};
}//end of splitTextImage

function gup( name ){
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");  
	var regexS = "[\\?&]"+name+"=([^&#]*)";  
	var regex = new RegExp( regexS );  
	var results = regex.exec( window.location.href ); 
	if( results == null ) return "";  
	else    return results[1];
}//end of gup





