/*
Hello, My name is De Zhi Huang, aka. Derrick. This is the 
plugin that I built partially to fulfill the project 3 assignment
for Dynamic Web Applications (E-75). The goal of plugin is to extend
the functionality of pre-existing rich textbox plugin. With this 
plugin, user can transfer their screenshot directly from their 
clipboard on to a textarea.

Limitation:  
	This plugin only works for Chrome browser. It also works in Firefox but 
	it is not part of the code. Firefox blocked access to clipboard for security.
	However, they let you paste data into a div that is defined as contenteditable.
Use:
	* Developer must supply the correct iframe containing in the editor
	how to use - simply call $(document).pasteImage('iframe'); 

update 11/16/2012 - User may now even embed youtube video in their textarea. (Not applicable to Firefox) 
 */
 (function($){	
	$.fn.pasteImage = function(iframe){
		
		//jquery style
		jQuery.event.props.push('clipboardData');
		iframe.contents().find('body').bind('paste', function(event){
			
			textString = event.clipboardData.getData('text');
			
			if(event.clipboardData.types == "Files"){
				var items = event.clipboardData.items;
				//console.log(JSON.stringify(items)); // will give you the mime types
				var blob = items[0].getAsFile();
				var reader = new FileReader();
				reader.readAsDataURL(blob);
				obj = $(this);
				reader.onload = function(event){
					obj.append("<img src=\""+ event.target.result + "\"width=600px; height=400px;>");
				}; // data url!
			}else if( textString.indexOf('youtube') >= 0){
				$(this).append(textString);
				event.preventDefault();
			}
		});//end of bind event
	};//end of function
})(jQuery);