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
					<ul>
						<li>Friendship Pending</li>
						<li><a id='kr'>Delete Friend Request</a></li>
					</ul>	
				</div>
				
			</div>
			
			<div id="content">
				<h4><?=$name;?>'s Wall</h4>
				<span>I am still thinking. I will get back to you later.</span>
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