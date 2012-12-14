<?php
	
class forum_controller extends base_controller {

	public function __construct() {
		parent::__construct();
	} 
	
	public function mainPage(){
		$this->template->content = View::instance('v_forum');
		
		$client_files = Array("../css/forumMain.css");
	
		$this->template->title = "Angstronic Forum";
		
		$this->template->client_files = Utils::load_client_files($client_files);   

		//$this->template->content->blogs = $this->renderPost();
		
		echo $this->template; 
		
		}//end of viewPosts
	
	public function thread(){
		//test the url input
		switch($_GET['type']){
			case 1: $title = "General"; 
					break;
			case 2: $title = "Modeling & Sim."; 
					break;
			case 3: $title = "Micro + Nano Fab."; 
					break;
			case 4: $title = "Knowledge Bank"; 
					break;
			case 5: $title = "Hobbies"; 
					break;
			default: break;
		}
		if(!isset($title)){
			echo "You are messing around with the url. Aren't you?";
		}else{
			$this->template->content = View::instance('v_getThread');
			$client_files = Array("../../css/forum.css");
			$this->template->title = $title;
			$this->template->client_files = Utils::load_client_files($client_files);
			
			$s = "<a href=\"/index/regOrlog\">Login or Register</a>"; // default display

			//allow post for those who have logged in
			if($this->user){
				$s 	= "<a href='createPost?type=".$_GET['type']."'>";
				$s .= "<span>add new thread</span>";
				$s .= "</a>";
			}//end of if
			
			$this->template->content->addPost = $s;
			$this->template->content->heading = $title;
			$this->template->content->per_page = 10;
			
			echo $this->template;
		}//end of else
		
	}//end of seeTopic()
	
	/////////////////////////////////////////////////
	//Handle the viewing of individual thread
	////////////////////////////////////////////////
	public function viewThread(){
		//user logged in show reply box
		$this->template->content = View::instance('v_thread');
		
		$threadID = mysql_real_escape_string($_GET['threadID']);
		
		$q = DB::instance(DB_NAME)->select_row("SELECT `thread_id`, `name`, `total` FROM `threads` WHERE `thread_id`=".$threadID, 'assoc');
		
		if(empty($q)){
			echo "You are tampering the url. Aren't you.";
		}else{
			
			$client_files = Array(
						"../css/thread.css",
						"http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css");
						
			$this->template->title 				= $q['name'];
			$this->template->content->blogTitle = $q['name'];
			$this->template->content->total = $q['total'];
			$this->template->content->per_page = 5;
			
			$s = "";					
		
		//if the user has logged in, show him/her the reply box
		if($this->user){
			array_push($client_files, "../javascripts/jquery.cleditor.min.js");
			array_push($client_files, "../javascripts/jquery.cleditor.css");
			array_push($client_files, "../javascripts/pasteImage.js");
			array_push($client_files, "../javascripts/postReply.js");
			array_push($client_files, "../javascripts/editPosts.js");
				$s   =	"<div id=\"reply\">";
				$s	.=	"<h5>Reply</h5>";
				$s	.=	"<textarea id=\"userReply\"></textarea>";
				$s	.=	"<input id=\"replybtn\" type=\"button\" value=\"Reply\">";
				$s	.=	"<input id=\"cleanbtn\" type=\"button\" value=\"Cancel\">";
				$s	.=	"<span id=\"error\"></span>";
				$s  .=	"</div>";
		}else{
				$s   =	"<div id=\"reply\">";
				$s  .= "<span>You need to <a href='/index/regOrlog'>";
				$s  .= "Log in Or Sign Up</a> to post reply.</span>";
				$s  .=	"</div>";
		}//end else
		
			$this->template->content->replyPost = $s;
			$this->template->client_files = Utils::load_client_files($client_files);
		
			echo $this->template;
		}//end of else
	}//end of viewThread
	
	/////////////////////////////////////////////////
	//Handle the creation of posts 
	////////////////////////////////////////////////
	
	public function createPost(){		
		
		$this->isUser();
		
		$this->template->content = View::instance('v_createPost');
		
		$client_files = Array(
						"../css/postBlog.css",
						"http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css",
						"../javascripts/jquery.cleditor.min.js",
						"../javascripts/jquery.cleditor.css",
						"../javascripts/pasteImage.js",
						"../javascripts/postBlog.js"
						);
	
		$this->template->title = "New Thread";
		
		$this->template->client_files = Utils::load_client_files($client_files);
		
		echo $this->template;
	}//end of createPost
	
	private function saveImage($textdata, $imgdata, $counter, $threadID, $postid){
		
		$user = $this->user->user_id; //get name or get re-directed
		
		if($counter > 0){
			$q = "INSERT INTO `images`(`thread_id`, `post_id`, `poster`) VALUES ";
			//loop image data into the database
			for($i = 0; $i < $counter; $i++){
				if(($counter < 2) || ($i == ($counter - 1))){
					$q .= "(".$threadID.", ".$postid.", ".$user.")";  
				}else{
					$q .= "(".$threadID.", ".$postid.", ".$user."),";  
				}//end of else
			}//end of for
			
			DB::instance(DB_NAME)->query($q);
			$index = mysql_insert_id();
			
			//save the image(s)
			for($i = 0; $i < $counter; $i++){
				$loc = "/images/forum/fm".($index - $counter + $i).".jpg";
				$old = "#PLACEHOLDER(".($i + 1).")";
				$filename = APP_PATH.$loc;
				file_put_contents($filename, base64_decode($imgdata[$i])); //store file
				$textdata = str_replace($old, $loc." width=800px; height=500px;", $textdata); //re-insert data
			}//end of for
		}//end of if loop
		
		return mysql_real_escape_string($textdata);
		//return $textdata;
	}//end of saveImage
	
	public function insertPost(){
		$user = $this->user->user_id; //get name or get re-directed
		$title = $_POST['title'];
		$type = $_POST['type'];
		$timestamp = Time::now();
		
		//start the thread
		DB::instance(DB_NAME)->query("INSERT INTO `threads` SET `name` = '$title', `type` = $type, `date` = $timestamp, `starter` = $user");
		$threadID = mysql_insert_id();
		$imgdata = (empty($_POST['imgdata'])) ? array() : $_POST['imgdata'];
		$textdata = $this->saveImage($_POST['text'], $imgdata, $_POST['counter'], $threadID, 1); //postid = 1 b/c it is the first post
		
		//push the data into the post table
		DB::instance(DB_NAME)->query("INSERT INTO `posts` SET `thread_id` = $threadID, `time_stamp`=$timestamp, `user_id`=$user, `content` = '$textdata'");
		
		echo "viewThread?threadID=".$threadID;
	}//end of insertPost 
	
	public function replyThread(){
		$user = $this->user->user_id; 
		$timestamp = Time::now();
		$threadID = $_POST['threadID'];
		$total = DB::instance(DB_NAME)->select_field("SELECT `total` FROM `threads` WHERE `thread_id` = $threadID");
		$imgdata = (empty($_POST['imgdata'])) ? array() : $_POST['imgdata'];
		$textdata = $this->saveImage($_POST['textdata'], $imgdata, $_POST['counter'], $threadID, $total + 1); //postid = 0 b/c it is unknown at this point
		
		DB::instance(DB_NAME)->query("INSERT INTO `posts` SET `thread_id` = $threadID, `time_stamp` = $timestamp, `user_id` = $user, `content` = '$textdata'");
					
		echo "viewThread?threadID=".$threadID."&page=".ceil(($total + 1) / 5);
	}//end of replyThread
	
		//save a draft
	public function saveDraft(){
		$draft =$_POST['draft'];
		$user = $this->user->user_id;
		DB::instance(DB_NAME)->query("INSERT INTO `cottons`(`user`, `data`) VALUES($user, '$draft') ON DUPLICATE KEY UPDATE `data` = '$draft'");
	}//end of saveDraft
	
	//get draft from database
	public function getDraft(){
		$user = $this->user->user_id;
		echo DB::instance(DB_NAME)->select_field("SELECT `data` FROM `cottons` WHERE `user` = $user");
	}//end of getDraft
	
	///////////////////////////
	//editing of thread + post... including updating and deleting
	///////////////////////////
	public function editPost(){
		$userID = $this->user->user_id;
		$imgdata = (empty($_POST['imgdata'])) ? array() : $_POST['imgdata'];
		$timestamp = Time::now();
		$threadID = $_POST['threadID'];
		$postID = $_POST['postID'];

		//update old images to not used
		$textdata = $this->saveImage($_POST['text'], $imgdata, $_POST['counter'], $_POST['threadID'], $_POST['postID']); 
		DB::instance(DB_NAME)->query("UPDATE `posts` SET `time_stamp` = $timestamp, `content` = '$textdata' WHERE `thread_id` = $threadID AND `post_id` = $postID AND `user_id` = $userID");
		echo DB::instance(DB_NAME)->select_field("SELECT `content` FROM `posts` WHERE `thread_id` = $threadID AND `post_id` = $postID");		
	}//end of editpost
	
	public function deleteThread(){
		$threadID = $_GET['threadID'];
		$q = DB::instance(DB_NAME)->select_field("SELECT `type` FROM `threads` WHERE `thread_id`=$threadID");
		$user = $this->user->user_id;
		
		//delete thread
		DB::instance(DB_NAME)->query("DELETE FROM `threads` WHERE `thread_id` = $threadID AND `starter`= $user");
		
		echo "thread?type=".$q;
	}//end of deleteThread
	
	
	public function deletePost(){
		$threadID = $_GET['threadID'];
		$postID = $_GET['postID'];
		$user = $this->user->user_id;
		DB::instance(DB_NAME)->query("DELETE FROM `posts` WHERE `thread_id` = $threadID AND `post_id` = $postID AND `user_id` = $user");
	}//end delete post
	
}//end of forum control class

?>