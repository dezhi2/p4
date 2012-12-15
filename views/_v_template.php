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
	
	<!-- JS -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
	<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
		
	<!-- Controller Specific JS/CSS -->
	<?=@$client_files; ?>
	
</head>

	<div id="mbar">
		
		<div id="menu">
		
		<a href="/index.php"><img src ="/images/sign.png" alt="logo" style="width: 120px; float: left;"></a>
			<ul>
				<li><a href="/forum/mainPage">Forum</a></li>			
				<?=$menu?>
			</ul>
		</div>
		
	</div>
	
	<?=$content;?> 
	
</body>
</html>