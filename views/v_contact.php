<div id="main_wrapper">
		
		<div id="loginBackground" class="logInWrapper"></div>
		
		<!--All Content go into this div -->
		<div id="content" class="logInWrapper">
			<div id="formwrapper">
				<form id="contactMe">
				
					<fieldset>
						<h4>Contact Me</h4>
						<label class="labelone" for="name">Name: </label>
						<input id="name" name="name"/>
						<span id="noName" >You don't have a name?</span>
						
						<label for="email">Email: </label>
						<input id="email" name="email">
						<span id="noEmail">How can we reply?</span>
					
						<label for="comments">Comments: </label>
						<textarea id="msg" name="comments"></textarea>
						<span id="noMsg">It's blank! -.-</span>
					</fieldset>
				
					<fieldset>
						<input id="submit" class="btn" type="button" value="Send Email"/>
						<input id="rst" class="btn" type="reset" value="Reset Form"/>
					</fieldset>
					<div id="feedback"></div>
				</form>	
			</div>		
		</div>
	</div>