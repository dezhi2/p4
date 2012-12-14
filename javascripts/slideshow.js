/*
Hello, My name is De Zhi Huang, aka. Derrick. This is the 
plugin that I built partially to fulfill the project 3 assignment
for Dynamic Web Applications (E-75). My first plugin seems a little
skimpy on the code size. So I built this. The goal of plugin 
is create slideshow directly from a div of images.

*see my website for how to use.

*/
(function($){	
	
	$.fn.slideshow = function( options ){
		//default options
			var settings = $.extend({
					width: 400,
					height: 300,
				timedelay: 7000,
				slidetoggle: 2000
				}, options);
			
			this.css('width', settings['width']);
			this.css('height', settings['height']);
			this.css('position', 'absolute');
			
			//css for captions
			var sty  = "width: 100%;";
				sty += "height: 10%;";
				sty += "bottom:0;";
			
			//create a class on the fly 
			var	cc  = "background: black;";
				cc += "z-index: 1000;";
				cc += "position: absolute;"; 
				cc += "background: rgba(0, 0, 0, 0.5);";
				cc += "filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);";
				cc += "-ms-filter: \"progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)\";";
			
			var cc2 = cc + "padding: 8px; width: 30px; height: 30xp; color: white; top: 50%; text-align: center;";
			
			$('<style>').text( ".slideshowPlugin{"+ cc + "} .slideshowplugin2{" + cc2 + "} .slideshowplugin2:hover{ cursor:pointer;}").appendTo('head');
			
			//prepend the caption & controller
			this.append('<div class=\"slideshowPlugin\" style=\"' + sty + '\"><span style="color: white; padding: 2px;"></span></div>');
			this.append('<div id="ssleft" class="slideshowplugin2"> << </div>');
			this.append('<div id="ssright" class="slideshowplugin2" style="right: 0%;"> >> </div>');
			
			
			//local variable slides*
			slides = this.find('img');
			
			slides.css('width', settings['width']);
			slides.css('height', settings['height']);
			slides.css('position', 'absolute');
			slides.css('display', 'none');
			
			captions = this.find('div').find('span');
			
			//kick off the program
			slides.first().show();
			captions.html(slides.first().attr('alt'));
			
			//loop through the images
			slidesShow = setInterval(function(){
				var cur = slides.filter(':visible');
		
				if(cur.next().is('img')) { 
					cur.next().fadeIn(settings['slidetoggle']); 
					captions.html(cur.next().attr('alt')); 
				}else{
					slides.first().fadeIn(settings['slidetoggle']); 
					captions.html(slides.first().attr('alt'));
				};		
					cur.fadeOut(settings['slidetoggle']);
			},settings['timedelay']); 
			
			//Event handling for controller
			$('#ssleft').click(function(){
				var cur = slides.filter(':visible');
					
				if(cur.prev().is('img')) { 
					cur.prev().fadeIn(settings['slidetoggle'] / 2); 
					captions.html(cur.prev().attr('alt')); 
				}else{
					slides.last().fadeIn(settings['slidetoggle'] / 2); 
					captions.html(slides.last().attr('alt'));
				};
				cur.fadeOut(settings['slidetoggle'] / 2);	
			});
			
			$('#ssright').click(function(){
				var cur = slides.filter(':visible');
					
				if(cur.next().is('img')) { 
					cur.next().fadeIn(settings['slidetoggle'] / 2); 
					captions.html(cur.next().attr('alt')); 
				}else{
					slides.first().fadeIn(settings['slidetoggle'] / 2); 
					captions.html(slides.first().attr('alt'));
				};
				cur.fadeOut(settings['slidetoggle'] / 2);
			});
	};//end of function
})(jQuery);



