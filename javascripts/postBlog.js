$(document).ready(function(){
	 //load the draft if it exist
	 
	 //make tabs
	 $('#userwrapper').tabs();
	 
	 var options = {width: 780,height: 400};
     var editor = $("#blog").cleditor(options)[0];
	 $(document).pasteImage($('#userPost iframe')); //plugin from Derrick
	 var draftEditor = $("#draftBlog").cleditor(options)[0];
	 $(document).pasteImage($('#draftPost iframe'));
	 
	 //access the content of the blog
	 $('#PostBlog').click(function(){
		
		$('#statusBar').html(def_status);
		
		var title = $('#blogTitle').val();
		var results = splitTextImage($('#blog').val());
		var type = gup('type');
		
		//error checking
		if((title == "")){ 
			$('#statusBar').html("You are missing a title");return;}
		
		if((blog == "")){ 
			$('#statusBar').html("You are post is blank!");return;}

		//wipe the content
		$('#blogTitle').val("");
		editor.clear();
		
		$.post('insertPost', {title: title, type : type, text : results.textdata, imgdata : results.imgdata, counter : results.counter},function(data){
			window.location = data;
		});//end of post
		
	 });//end of postBlog
	 
	//save draft button
	$('#saveBlog').click(function(){
		$('#statusBar').html("Saving Draft " + def_status);
		var blog = $('#blog').val();
		if((blog == "")){ 
			$('#statusBar').html("You are post is blank!");return;}
			
		$.post('saveDraft', {draft:blog}, function(data){
			$('#statusBar').html("Draft Saved");
		});//end of post
	 });//end of save draft button
	 
	 //post draft Button
	 $('#PostDraft').click(function(){
		$('#statusBar').html(def_status);
		
		var title = $('#blogTitle').val();
		var results = splitTextImage($('#draftBlog').val());
		var type = gup('type');
		//error checking
		if((title == "")){ $('#statusBar').html("You are missing a title");return;}
		
		if((blog == "")){ $('#statusBar').html("You are post is blank!");return;}
		
		//wipe the content
		$('#blogTitle').val("");
		editor.clear();
		draftEditor.clear();
	
		$.post('insertPost', {title:title, type:type, text:results.textdata, imgdata:results.imgdata, counter:results.counter},function(data){
			window.location = data;
		});//end of post
	 });//end of click
	 
	 //draft button clicked
	 $('#draft').click(function(){
		$('#statusBar').html("Please wait. Retrieving draft from database...");
	
		$.get('getDraft', {}, function(data){
			//set value to draftBlog
			$('#draftBlog').val(data).blur();
			$('#statusBar').html("");
		});//end of post
	}); //end of draft click
	
	//cancel Button - direct the user back to forum
	 $('.cbut').click(function(){
 		$('#blogTitle').val("");
 		editor.clear();
		draftEditor.clear();
 		window.location = '/forum/thread?type=' + gup('type');
 	 });//end of cancel
	
	 //default status 
	 var def_status = "Processing <img src=\"../images/ajax-cat.gif\" alt=\"loader\"/>";
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

//get url data
function gup( name ){
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");  
	var regexS = "[\\?&]"+name+"=([^&#]*)";  
	var regex = new RegExp( regexS );  
	var results = regex.exec( window.location.href ); 
	if( results == null ) return "";  
	else    return results[1];
}//end of gup