$(document).ready(function(){
	var num = $('#background').children().size();
	var c = num;
	//background carousel
	 setInterval(function(){
		(c == 1) ? c = num : c = c - 1;
		var s = $('#background img:nth-child(' + c + ')').attr('src');
		$('#mainwrapper').animate({opacity:0}, 2500,function(){ $(this)
															.css('background-image', 'url(' + s +')')
															.animate({opacity:1}, 2500)});
	
	  },'10000'); //end of setInterval 
});//end of document ready

