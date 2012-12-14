<div id="main_wrapper">
		<div id="loginBackground_base" class="logInWrapper"></div>
		<?php
			
		?>
		<div id="content" class="logInWrapper">
			<div id="rightbox">
				<div id="msg"></div>
			</div>
			
			<!--Both log on and register will be handled by ajax-->
			<div id="loginform" class="formwrapper">
			
				<form id="logOn">
					<ul>
						<li>Email: <input type="text" id="login_email" name="email"></li>
						<li>Password: <input type="password" id="login_password" name="password" maxlength="10"></li>
						<li><input type="button" id="logIn" value="Log In"></li>
						<li><span id="regform" style="font-size: 0.7em; cursor: pointer;">Not yet a Member? Click Here to register</span></li>
					</ul>
				</form>
			</div>
			
			
			<div id="register" class="formwrapper">
				<form id="signUp">
					<ul>
						<li>Email: <input id="regEmail" type="email" name="email"></li>
						<li>Name: <input id="regName" type="text" name="name" maxlength="10"></li>
						<li>Date of Birth: <input id="bday" type="date" name="bday" /></li> 
						<li>Password: <input id="testPW" type="password" name="password" maxlength="10"></li>
						<li>Confirm PW: <input id="testPWc" type="password" name="repassword" maxlength="10"></li>
					</ul>
					<span id="rtn" style="color: white; cursor: pointer;"><< Go Back</span>
					<input type="reset" value="Reset" style="margin-left: 50px;">
					<input id="toReg" type="button" value="Register" style="float:right;">
				</form>
			</div>
			
		</div>
		
		<div id="ajaxloader" class="logInWrapper"><strong><p>Now Loading...</p></strong><img src="../images/ajax-loader.gif" alt="ajax_loader"></div>
</div>
	