
<div id="main_wrapper">
		<div id="user_wrapper">
			<!--All Content go into this div -->	
			<div id="profile">
			<h4>Profile</h4>
				<div id="headshot">
					
					<div><img id="yourface" src="<?=$headshot?>" alt="headshot"></div>
					<div id="yourname">
						<?=$name; ?>
					</div>
				</div>
				
				<div id="control">
					<ul>	
						<li><a id="df">Desist Friendship</a></li>
					</ul>	
				</div>
				
			</div>
			
			<div id="content">
				<h4><?=$name?>'s Wall</h4>
				
				<!--Insert Status-->
				<div id="status">
					<h5>Leave a note...</h5>
					<textarea id="insertComments" placeholder="Update your status here ..." ></textarea>
					<div id="errorStatus"></div>
					<div id="wallid" style="display: none;"></div>
					<input id="updateStatus" type="button" value="Update">
				</div>
				
				<!--This is your wall-->
				<div id="statusWrapper">
					<div id="lastWrapper"></div>
				</div>
			</div>
			
			<div id="friends">
				<h4>Social Circle</h4>
				
					<div id="fnd">
						<ul>
							<?//=$otherFriends;?>
						</ul>
					</div>					
			</div>
		</div>
	</div>