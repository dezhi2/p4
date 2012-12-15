$(document).ready(function(){
	  //get geolocation data
	  geoloc(success, fail);
});//end of document ready

function geoloc(success, fail){
    var is_echo = false;
    if(navigator && navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        function(pos) {
          if (is_echo){ return; }
          is_echo = true;
          success(pos.coords.latitude,pos.coords.longitude);
        }, 
        function() {
          if (is_echo){ return; }
          is_echo = true;
          fail();
        }
      );
    } else {
      fail();
	}
}//end of geoloc

function success(lat, lng){
	var latlon= lat+","+lng;
	var img_url="http://maps.googleapis.com/maps/api/staticmap?center="+latlon+"&zoom=14&size=400x300&sensor=true";
	$("#weather").html("<img src='"+img_url+"'>");
	getWeather(lat, lng);
	}//end of success
  
function fail(){
  alert("Some problem with your browser");}//end of fail
	
function getWeather(lat, lng){
	var s = 'http://www.google.com/ig/api?weather=,,,'+ lat +','+lng;
	$.get(s,'json',function(data){
			   alert('done');
	})//end of get
}//end of get weather

