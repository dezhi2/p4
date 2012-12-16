<?php

class users_controller extends base_controller{
	
	public function __construct(){
	
			parent::__construct();
	}
	
	
	public function signup(){
		#check email and name
		$_POST = DB::instance(DB_NAME)->sanitize($_POST);
		
		$email = $_POST['email'];
		$name = $_POST['name'];
		
		$q = "SELECT COUNT(user_id)
				FROM `users` u
			   WHERE u.`name` = '$name' OR u.`email` = '$email'";
			   
		$indicator = mysql_result(DB::instance(DB_NAME)->query($q), 0);
		
		if($indicator){
			echo("Email or Name has been taken.");
			exit();
		}
		
		# Encrypt the password	
		$_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);

		# More data we want stored with the user	
		$_POST['created']  = Time::now();
		$_POST['modified'] = Time::now();
		$_POST['token']    = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string());		
		$_POST['headshot'] = "/images/headshots/default.jpg";
		
		#NOTE curl_init is not defined inside geolocate
		/*
		# Geolocate user 
		$geolocation = Geolocate::locate();	
		
		$GEO_info = array(
						'name' 		   => $name,
						'ip' 		   => $geolocation['ip'],
					    'country'      => $geolocation['country_code'],
						'state'        => $geolocation['state'],
						'city'         => $geolocation['city'],
						'role'		   => 'member'
 					);
		*/
		
		# Insert this user into the database
		DB::instance(DB_NAME)->insert("users", $_POST);
		//DB::instance(DB_NAME)->insert("user_info", $GEO_info);
		
		//send confirmation email
		$content = "Hello ".$name."\n\n";
		$content .= "Thank you for registering at Angstronics.com. You can activate you account by using the below link:\n\n\n";
		$content .= "http://p4.angstronics.com/users/activate?email=".$email."&created=".$_POST['created']."\n\n\n";
		$content .= "Regards,\n";
		$content .= "Derrick\n";
		$content .= "Angstronics Webmaster\n";
		
		mail($email, 'Account Activation', $content, 'From: Angstronics.com ');
		
		# For now, just confirm they've signed up - we can make this fancier later
		echo "Congratz! Please check you email to activate your account.";
	}
	
	public function login(){
		
		$attempt = $this->userObj->login($_POST['email'], $_POST['password']);
		
		# If attempt return false, login failed
		if(!$attempt) {
			echo "incorrect credentials";
		} else if($attempt == 'new' ) {
			echo 'You need to activate your account from your registered email.';
		# Else, login succeeded!
		}else {
			//update db for last login
			$email = $_POST['email'];
			$currentTime = Time::now();
			DB::instance(DB_NAME)->query("UPDATE `users` SET `modified` = $currentTime WHERE `email` = '$email'");
			echo "okay";
		}
	}
	
	public function logout(){
	
		$this->userObj->logout($this->user->email);
		
		//throw the user back to login page		
		header('Location: /index/regOrlog');
	}
	
	public function activate(){	
		$email = $_GET['email'];
		$created = $_GET['created'];
	
		DB::instance(DB_NAME)->query("UPDATE `users` SET `status` = 'active' where `email` = '$email' AND `created` = '$created' ");
		
		header('Location: /index/regOrLog');
		
	}
	
	public function profile(){
			# If user is blank, they're not logged in, throw them back to login!
		if(!$this->user) {
			
			header('Location: /forum/viewPosts');
			
			# Return will force this method to exit here - none of the rest of the code will execute
			return false;
		}
	
		# Setup view
		$this->template->content = View::instance('v_user_profile');
		$this->template->title   = $this->user->name."'s Profile";
		
		$client_files = Array(	
						"../css/profile.css",
						"../javascripts/renderProfile.js");
	    
	    $this->template->client_files 		= Utils::load_client_files($client_files);
		$this->template->content->user_name = $this->user->name;
		$this->template->content->face	    = $this->user->headshot;
		$this->template->content->membersince = date('Y-m-d', $this->user->created);
		$this->template->content->lastlogin = date('Y-m-d g:i a', $this->user->modified);
		
		# Render template
		echo $this->template;
	}
	
	#################################################################
	#The functions below deal with user's wall
	#################################################################
	public function insertWall(){
		
		$wallowner = (empty($_GET['userid'])) ? $this->user->user_id : $_GET['userid'];
		$me = $this->user->user_id;
		$time = Time::now();
		$status = mysql_real_escape_string($_GET["status"]);
		
		$q = "INSERT INTO `walls`(`owner`, `poster`, `time_stamp`, `content`)
							VALUE($wallowner, $me, $time, '$status')";
		
		DB::instance(DB_NAME)->query($q);
		
		//feedback
		$fb[] = array("posterid"=> $me,
					  "name" => $this->user->name,
					  "time"=> date("Y-m-d",$time));
		echo json_encode($fb);
	}//end of insertWall

	//Require: the start point & the wall owner name
	public function renderWall(){
		//wallowner
		$wallowner = (empty($_GET['userid'])) ? $this->user->user_id : $_GET['userid'];
		
		$limit = 10;
		
		//render the first 10 posts from the user's wall
		
		$q = "SELECT u.`user_id`, u.`name`, w.`time_stamp`, w.`content`
			    FROM `walls` w, `users` u
			   WHERE  w.`owner` = $wallowner AND w.`poster` = u.`user_id`  
			ORDER BY `time_stamp` DESC
			   LIMIT 0 , ".$limit;
		
		$result = DB::instance(DB_NAME)->query($q);
		
		//container
		$temp = null;
		
		while($tableRow = mysql_fetch_assoc($result)){
			
			$temp[] = array("posterID" => $tableRow["user_id"],
							"name" => $tableRow["name"],
							"time" => date("Y-m-d",$tableRow["time_stamp"]),
							"content" => $tableRow["content"]
						);
			
		}//end of while loop
		
		echo json_encode($temp);
	}//end of renderWall
	
	#################################################################
	#The functions below deal with user's Profile Control
	#################################################################
	public function changeAvatar(){
		
		if(!$this->user) {
			
			header('Location: /index.php');
			
			# Return will force this method to exit here - none of the rest of the code will execute
			return false;
		}
	
		# Setup view
		$this->template->content = View::instance('v_changeAvatar');
		$this->template->title   = $this->user->name."'s Profile";
		
		# Render template
		echo $this->template;
		
	}//end of change picture
	
	public function processAvatar(){
		if(!$this->user) {
			header('Location: /index.php');	
			# Return will force this method to exit here - none of the rest of the code will execute
			return false;}
		
		$username = $this->user->name;
		
		Upload::upload($_FILES, '/images/headshots/', array('jpg', 'gif', 'png'), $username);
		
		$file_parts = pathinfo($_FILES['Filedata']['name']);

		$pathname = '/images/headshots/'.$username.'.'.$file_parts['extension'];
		
		//update the database
		DB::instance(DB_NAME)->query("UPDATE `users` SET `headshot` = '$pathname' WHERE `name` = '$username'");
		
		//route the user back to his/her profile
		header('Location: /users/profile');
		
	}//end of processAvatar
	
	#################################################################
	//The below code render the user's inbox in the main page
	#################################################################
	public function checkInbox(){
		
		//loop the database for friend requests only
		$user = $this->user->user_id;
		
		$q = "SELECT u.`user_id`, u.`name`
				FROM `relationships` rs, `users` u
			   WHERE rs.`you` = $user AND rs.`kind` = 2 AND rs.`me` = u.`user_id`";
		
		$result = DB::instance(DB_NAME)->query($q);
		
		$temp = null;
		
		while($tableRow = mysql_fetch_assoc($result)){
			
			$temp[] = array(
							"id" => $tableRow['user_id'],
							"name" => $tableRow['name']);
			
		}//end of while loop
		
		echo json_encode($temp);
		
	}//end of checkInbox
	
	#################################################################
	//The below code render the user's friendship stuffs in his/her profile page
	#################################################################
	public function getLife(){
		
		$user = $this->user->user_id;
		
		$q = "SELECT u.`user_id`, u.`name`, u.`headshot`, rs.`kind`
				FROM `relationships` rs, `users` u
			   WHERE rs.`me` = $user AND rs.`you` = u.`user_id`";
		
		$result = DB::instance(DB_NAME)->query($q);
		
		$temp = null;
		
		while($tableRow = mysql_fetch_assoc($result)){
			
			$temp[] = array("id" => $tableRow["user_id"],
							"name" => $tableRow["name"],
							"headshot" => $tableRow["headshot"],
							"type" => $tableRow['kind']);
		}//end of while loop
		
		echo json_encode($temp); // return looped data
		
	}//end of getFriends
	
	public function processFriend(){
		$me = $this->user->user_id;
		$you = $_GET['requestor'];   
		$choice = $_GET['choice'];

		//abort if the user choose reject a friendship
		if($choice == "reject"){ 
			$q = "DELETE FROM `relationships` WHERE `me` = $you AND `you` = $me"; 
			DB::instance(DB_NAME)->query($q);
		}else{
			//process friendship
			$q = "INSERT INTO `relationships`(`me`, `you`, `kind`) VALUES($me, $you, 1), ($you, $me, 1) ON DUPLICATE KEY UPDATE `kind` = 1"; 
			DB::instance(DB_NAME)->query($q);
		}//end of else
		
	}//end of processFriend
	
	///////////////////////
	//This function desist your relationship with the target person
	public function desistRelationship(){
		$you = $_GET['target'];
		$me = $this->user->user_id;
		
		$q = "DELETE FROM `relationships` WHERE (`me` = '$me' AND `you` = '$you') OR (`me` = '$you' AND `you` = '$me') ";
		DB::instance(DB_NAME)->query($q);
		
	}//end of overloaded processFriend

	//this function renders your friends and follows activities
	public function renderActivities(){

		$user = $this->user->user_id;
		
		//get data from the past 3 day data only
		$date = strtotime('-3 days' , Time::now() );
		
		$q = "SELECT th.`starter`, u.`name`, rs.`kind`, th.`thread_id`, th.`name` as 'threadname', th.`date`   
				FROM `relationships` rs, `users` u, `threads` th
			   WHERE rs.`me` = $user AND rs.`you` = u.`user_id` AND th.`starter` = u.`user_id` AND (th.`date` > $date) LIMIT 0, 10";
		
		$result = DB::instance(DB_NAME)->query($q);
		
		$temp = null;
		
		while($tableRow = mysql_fetch_assoc($result)){
			
			$temp[] = array(
							"posterID" => $tableRow["starter"],
							"name" => $tableRow["name"],
							"kind" => $tableRow["kind"],
							"threadID" => $tableRow['thread_id'],
							"threadname" => $tableRow['threadname'],
							"date" => date("Y-m-d",$tableRow['date'])
							);
			
		}//end of while loop
		
		echo json_encode($temp); // return looped data
		
	}//end of renderActivities
	
}//end of the class

?>