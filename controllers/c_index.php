<?php

class index_controller extends base_controller {

	public function __construct() {
		parent::__construct();
		
	} 
	//-------------------------------------------------------------------------------------------------
	public function main(){
		$this->template->content = View::instance('v_main');
		
		$this->template->title = "Main";
		
		$client_files = Array(	
						"../css/main.css",		
						"../javascripts/main.js");
						
		$this->template->client_files = Utils::load_client_files($client_files);   
	      	
		echo $this->template;
	}//end of main
	
	
	public function regOrlog() {
		
		#re-route the user if he/she has already logged in
		if(isset($_COOKIE['token'])){
			Router::redirect("/users/profile");
		}else{
		
		# Any method that loads a view will commonly start with this
		# First, set the content of the template with a view file
			$this->template->content = View::instance('v_index_regOrlog');
			
		# Now set the <title> tag
			$this->template->title = "Log on/Registrer";
		
		# If this view needs any JS or CSS files, add their paths to this array so they will get loaded in the head
			$client_files = Array(	
						"../css/home.css",		
						"../javascripts/formHandling.js"
						);
	    
	    	$this->template->client_files = Utils::load_client_files($client_files);   
	      	$this->template->menu = "";	
		# Render the view
			echo $this->template;
		}	
	}
	
	public function contact(){
			# Any method that loads a view will commonly start with this
		# First, set the content of the template with a view file
			$this->template->content = View::instance('v_contact');
			
		# Now set the <title> tag
			$this->template->title = "Contact";
	
		# If this view needs any JS or CSS files, add their paths to this array so they will get loaded in the head
			$client_files = Array(	
						"../css/contact.css"
	                    );
	    
	    	$this->template->client_files = Utils::load_client_files($client_files);   
	      		
		# Render the view
			echo $this->template;
	}
	
	public function viewAllUsers(){
		$this->template->content = View::instance('v_allMembers');
			
		# Now set the <title> tag
		$this->template->title = "Member List";
	
		# If this view needs any JS or CSS files, add their paths to this array so they will get loaded in the head
		$client_files = Array("../css/viewAllmembers.css");
	    
	    $this->template->client_files = Utils::load_client_files($client_files);   
		$this->template->content->members = $this->getMembers();
		
		echo $this->template;	
	}//end of viewAllUsers
	
	//query the database and look up all members
	private function getMembers(){
		
		$per_page = 10;
		
		$count = DB::instance(DB_NAME)->select_field("SELECT COUNT(`user_id`) FROM `users`");
		$pages = ceil($count / $per_page);
		
		$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
		$start = ($page - 1) * $per_page;
		
		$members = DB::instance(DB_NAME)->query("SELECT `user_id`, `name`, `headshot`, `created`
												   FROM `users`
											   ORDER BY `name`
											   LIMIT $start, $per_page");
		$code = "<ul><h4>Member List</h4>";
		
		while($tableRow = mysql_fetch_assoc($members)){
			
			$code .= "<li>";
			$code .= "<a href=\"/index/viewProfile?userID=".$tableRow['user_id']."\"><img src=\"".$tableRow['headshot']."\" alt=\"member\"> ".$tableRow['name'];
			$code .= "<label>Join Since: ".date('Y-m-d', $tableRow['created'])."</label></a></li>";
			
		}//end of while
		
		$code .= "</ul><div id=\"pages\">";
		
		//pagination
		if($pages >= 1 && $page <= $pages){
		
			$code .= ($pages >= 2 && $page != 1) ? '<a href="?page='.($page - 1).'"><<</a></strong> ' : "";
				
				for($x = 1; $x<= $pages; $x++){ 
				
					//Bold face the currect selected page
					$code .= ($x == $page) ? '<strong><a href="?page='.$x.'"> '.$x.'</a></strong> ' : '<a href="?page='.$x.'"> '.$x.'</a>'; 
				}
				
				$code .= ($pages >= 2 && $page != $pages) ? '<a href="?page='.($page + 1).'">>></a></strong> ' : "";
			
		}//end of if
		
		$code .= "</div>";
		
		return $code;
		
	}//end of getMembers
	
	/////////////////////////////////////////////////////////////
	//This function handles viewing of user's profile
	/////////////////////////////////////////////////////////////
	
	public function viewProfile(){
	
		//testing the user
		if(!$this->user){
			//complete stranger
			$this->nonFriend('v_stranger');
		}else if($this->user->user_id == $_GET['userID']){
			//logged on
			header('Location: /users/profile');		
		}else{
			$me = $this->user->user_id;
			$you = $_GET['userID'];
			$type = DB::instance(DB_NAME)->select_field("SELECT `kind` FROM `relationships` WHERE `me` = $me AND `you` = $you");
			
			switch($type){
				//friendz
				case 1: $this->friendVisit();
					break;
				
				//friendship pending
				case 2: $this->nonFriend('v_friending');
					break;
					
				//following
				case 3:	$this->nonFriend('v_follower');
					break;
				
				//user is not related anyone
				default: $this->nonFriend('v_somedude');	
					break;
			}//end of switch
			
		}//end of else
	}//end of view profile
	
	//this function is not used for external call
	private function renderUserProfile($targetName, $js, $css, $optionsForVisitor, $insertWall, $fnd){
		//setup view
		$this->template->content = View::instance('v_otheruser');
		$this->template->title   = $_GET['user']."'s Profile";
		$this->template->content->user_name = $_GET['user'];
		
		$client_files = Array(	
						"../css/profile.css",
						$js,
						$css
	                    );
	    
	    $this->template->client_files = Utils::load_client_files($client_files);
		
		//get user headshot for DB
		$this->template->content->face = DB::instance(DB_NAME)->select_field("SELECT `headshot` FROM `users` WHERE `name` = '$targetName' ");
		$this->template->content->otherFriends = $fnd;
		$this->template->content->optionsForVisitor = $optionsForVisitor;
		$this->template->content->insertWall = $insertWall;
		
		# Render template
		echo $this->template;
		
	}//end of renderUserProfile
	
	////////////////////////////////////////////////////////////
	// switch 
	///////////////////////////////////////////////////////////
	
	private function friendVisit(){
		$this->template->content = View::instance('v_otheruser');
		$client_files = Array("../css/profile.css",
							  "../javascripts/otheruser.js",
							  "../javascripts/friendRequest.js");
		$q = DB::instance(DB_NAME)->select_row("SELECT `name`, `headshot` FROM `users` WHERE `user_id`=".$_GET['userID']);
		
	    $this->template->client_files = Utils::load_client_files($client_files);
		$this->template->title = $q['name']."'s Profile";
		$this->template->content->name = $q['name'];
		$this->template->content->headshot = $q['headshot'];
		
		echo $this->template;
	}//end of friend visit
	
	private function nonFriend($template){
		$this->template->content = View::instance($template);
		$client_files = Array("../css/profile.css",
							  "../javascripts/friendRequest.js");
	    
		$q = DB::instance(DB_NAME)->select_row("SELECT `name`, `headshot` FROM `users` WHERE `user_id`=".$_GET['userID']);
		
	    $this->template->client_files = Utils::load_client_files($client_files);
		$this->template->title = $q['name']."'s Profile";
		$this->template->content->name = $q['name'];
		$this->template->content->headshot = $q['headshot'];
		
		echo $this->template;
	}//end of nonFriend
		
	//SOCIAL CIRCLE stuffs
	///////////////////////////////////////////////////////////////
	//This function processes friend request from an ajax call
	///////////////////////////////////////////////////////////////
	public function sendFriendRequest(){
		//build a friendship request
		$target = $_GET['userid'];
		$me = $this->user->user_id;
		//2 is friend requesting
		$q = "INSERT INTO `relationships`(`me`, `you`, `kind`) VALUES($me, $target, 2) ON DUPLICATE KEY UPDATE `kind` = 2";
		DB::instance(DB_NAME)->query($q);
	}//end of sendFriendRequest
	
	public function followUser(){
		//build a follow request
		$target = $_GET['userid'];
		$me = $this->user->user_id;
		//3 is following
		$q = "INSERT INTO `relationships`(`me`, `you`, `kind`) VALUES($me, $target, 3)";
		DB::instance(DB_NAME)->query($q);
	}//end of followUser
	
	public function getOtherLife(){
		
		$user = $_GET['userid'];
		
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
	
	/* ------------------------------------------------------------
	-- INSTANT SEARCH
	--------------------------------------------------------------*/
	public function instantSearch(){
		$segment = $_GET['segment']."%";
		$results = DB::instance(DB_NAME)->query("SELECT `thread_id`, `name` 
										FROM `threads`
									   WHERE LOWER(`name`) LIKE LOWER('$segment')
									   LIMIT 0, 5 ");
		
		$temp = null;
		while($tableRow = mysql_fetch_assoc($results)){
			$temp[] = array("threadID" => $tableRow['thread_id'],
							    "name" => $tableRow['name']);
		}//end of while loop
		
		//echo json_encode($temp);
		echo json_encode($temp);
	}//end of instantSearch
	
} // end class
