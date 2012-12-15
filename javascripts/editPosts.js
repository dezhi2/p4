$(document).ready(function(){
	$('li tr').live('click',function(){
		
		var choice = $(this).attr('alt');
		
		if(choice == 'update'){
		
			var threadID = $(this).attr('threadID');
			var postID = $(this).attr('postID');
			
			//switch to the other view
			$(this).parent().find('tr').toggle();
			
			//grab the content div as global variable
			var Obj = $($(this).parent().parent().parent().parent().children()[1]);
			var content = Obj.html();
			
			//write to hidden div
			$($(this).parent().parent().parent().parent().children()[3]).html(content);
			Obj.html("<textarea>" + content + "</textarea>");
			
			//append whatsoever into textarea
			var editObj = Obj.find('textarea')
			//editObj.html(content);
			
			var options = {width: 780,height: 350};
			var editor = editObj.cleditor(options)[0];
			$(document).pasteImage(Obj.find('iframe')); //plugin from Derrick
		
		}else if(choice == 'check'){
		         obj = $(this).parent().parent().parent().parent();
			var data = obj.find('textarea').val();
			$(obj.children()[1]).html("<div class='loader'><img src='/images/ajax-loader.gif'></div>"); // ajax loader
			if(data == ""){
				alert('You are kidding me right? Put something in!');
				return;
			}else{
				
				//split the data into texts and images
				var threadID = $(this).attr('threadid');
				var postID = $(this).attr('postid');
				var results = splitTextImage(data);
				
				$.post('editPost', {
					  threadID : threadID,
						postID : postID,
						  text : results.textdata, 
					   imgdata : results.imgdata, 
					   counter : results.counter
				}, function(response){
					$(obj.children()[1]).html(response);
					obj.find('tr').toggle();
				}).error(function(){
					alert('uh-oh something is wrong with the server');
				});//end of post
			}//end of else
			
			
		}else if(choice == 'cancel'){
			//revert the change
			var olddata = $(this).parent().parent().parent().parent().find('#notes').html();
			$($(this).parent().parent().parent().parent().children()[1]).html(olddata);
			
			//switch back to the other view
			$(this).parent().find('tr').toggle();
		}else{
			//delete post/thread
			obj = $(this).parent().parent().parent().parent();
			$(obj.children()[1]).html("<div class='loader'><img src='/images/ajax-loader.gif'></div>"); // ajax loader
			var threadID = $(this).attr('threadid');
			var postID = $(this).attr('postid');
			
			//determine whether the user is trying to delete a post or the entire thread
			if(postID == 1){
			$("<p>Are you sure that you wanted to delete the entire thread?</p>"
				).dialog({
					title: "Delete Thread",
					draggable: false,
					resizable: false,
					modal:true,
					buttons:{
						"Delete Thread": function(){
							$.get('deleteThread',{threadID: threadID},function(response){
								window.location = response;
							});//end of get 
							$(this).dialog("close");
						},//end of delete thread function
						"Cancel": function(){
							$(this).dialog("close");
						}//end of cancel
					}//end of button
				});//end of dialog
			
			}else{
			$("<p>Are you sure that you wanted to remove your reply?</p>"
				).dialog({
					title: "Delete Post",
					draggable: false,
					resizable: false,
					modal:true,
					buttons:{
						"Delete Post": function(){
						$.get('deletePost', {threadID: threadID, postID: postID},function(){
							obj.remove();
						});//end of get
							$(this).dialog("close");
						},//end of delete post function
						"Cancel": function(){
							$(this).dialog("close");
						}//end of cancel
					}//end of button
				});//end of dialog
			}//end of else
			
		}//end of ifelse
		
	});//end of click event handler 
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