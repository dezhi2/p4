
<div id="main_wrapper">
		<div id="user_wrapper">
			<!--All Content go into this div -->	
			<div id="profile">
			<h4>Profile</h4>
				<div id="headshot">
					<div><img id="yourface" src=<?="\"".$face."\""?> alt="headshot"></div>
					<div id="yourname">
						<?=$user_name; ?>
					</div>
					<span>Member Since: <?=$membersince?> </span>
					<span>Last Login: <?=$lastlogin?></span>
				</div>
				
				<div id="control">
					<ul>	
						<li><a href="/users/changeAvatar">Change Profile Picture</a></li>
					</ul>	
				</div>

				<div id="inbox">
					<h4>Inbox</h4>
						<ul>
							
						</ul>
				</div>
				
			</div>
		
			<div id="content">
				<h4>Your Wall</h4>
				
				<div id="recentActivity">
					<h5>Recent Activity from Social Circle</h5>
					<ul>
					</ul>
					
					<div id="more"></div>
				</div>
				
				<!--Insert Status-->
				<div id="status">
					<h5>Update Status</h5>
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
						</ul>
					</div>					
			</div>
		</div>
	</div>