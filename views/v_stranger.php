<div id="main_wrapper">
		<div id="user_wrapper">
			<!--All Content go into this div -->	
			<div id="profile">
			<h4>Profile</h4>
				<div id="headshot">
					
					<div><img id="yourface" src="<?=$headshot;?>" alt="headshot"></div>
					<div id="yourname">
						<?=$name;?>
					</div>
				</div>
				
				<div id="control">
					<ul></ul>	
				</div>
				
			</div>
			
			<div id="content">
				<h4><?=$name;?>'s Wall</h4>
				<span>You don't have the permission to see this.</span>
				<a href="/index/regOrlog">Register/login</a>
			</div>
			
			<div id="friends">
				<h4>Social Circle</h4>
				
					<div id="fnd">
						<ul>
						</ul>
					</div>					
			</div>
		</div>
	</div>