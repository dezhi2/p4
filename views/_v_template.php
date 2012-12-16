<!DOCTYPE html>
<html>
<head>
	<title><?=@$title;?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
	
	<!--The icon-->
	<link rel="icon" type="image/ico" href="../images/symbol.png">
	<link rel="shortcut icon" href="../images/symbol.png">
	
	<!--Basic CSS-->
	<link rel="stylesheet" type="text/css" href="../css/template.css" />
	<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
		
	<!-- JS -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
	<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
	<script src='../javascripts/login.js'></script>
	<script>
		$(document).ready(function(){
			
			var timer, timeout  = 250;
			//timer to detect if the user is done typing
			$('#searchbar').keyup(function(){
				clearTimeout( timer );
				timer = setTimeout(queryDB, timeout ); //set off the timer				
			});
			
			//route the user to this thread
			$('#searchres td').live('click', function(){
				window.location.href = '/forum/viewThread?threadID=' + $(this).attr('id');
			}); 
		});// end of document ready
		function queryDB(){
				var content = $('#searchbar').val();
				
				if(content == ""){
					$('#searchres').css('display','none');
				}else{
					$('#searchres').css('display','inline');
					$.get('/index/instantSearch', {segment:content}, function(data){
						var obj = $.parseJSON(data);
						var len = obj.length;
						var s = "";	
							for(var i = 0; i < len; i++){						
								 s += "<tr><td id=" + obj[i].threadID + ">" + obj[i].name + "</td></tr>";
							}
						$('#searchres').html(s);
					});//end of get
				}//end of else
		}//end of queryDB
		
	</script>
	<style>
		#searchbar{
			margin-top: 20px;
			margin-bottom: 0px;
		}
		#searchres{
			min-width: 270px;
			background: white;
			margin: 0px 0px 0px 123px;
			position: absolute;
			z-index: 1000;
			padding: 0px;
		}
		
		#searchres td:hover{
			cursor: pointer;
			background: skyblue;
			color: white;
		}
	</style>
	<!-- Controller Specific JS/CSS -->
	<?=@$client_files;?>
	<?=$login?>

</head>

	<div id="mbar">
		<div id="menu">
		<a href="/index.php"><img src ="/images/sign.png" alt="logo" style="width: 120px; float: left;"></a>
		
			<ul>
				<li><a href="/forum/mainPage">Forum</a></li>			
				<?=$menu?>
			</ul>
			<input id='searchbar' type="text" maxlength="30" size="40" placeholder="Search threads here ..." >
			<table id='searchres'></table>
		</div>
	</div>
	
	<?=$content;?> 	
</body>
</html>