<?php

class base_controller {
	
	public $user;
	public $userObj;
	public $template;
	public $email_template;

	/*-------------------------------------------------------------------------------------------------
	
	-------------------------------------------------------------------------------------------------*/
	public function __construct() {
						
		# Instantiate User obj
			$this->userObj = new User();
			
		# Authenticate / load user
			$this->user = $this->userObj->authenticate();					
						
		# Set up templates
			$this->template 	  = View::instance('_v_template'); // grab the library of this section
			$this->email_template = View::instance('_v_email');			
							
		# So we can use $user in views			
			$this->template->set_global('user', $this->user);
			$this->template->login = "";
		if(!$this->user){
		#menu by default
			$this->template->login = "<script src='../javascrpts/login.js'></script>";
			$this->template->menu = "<li><a id='login' href='#'>Sign In</a></li>";
		}else{
			$this->template->menu = "<li><a href=\"/index/viewAllUsers\">Member List</li>";
			$this->template->menu .= "<li><a href=\"/users/profile\">".$this->user->name."'s Profile</a></li>"."<li><a href=\"/users/logout\">Sign Out</a></li>";}
		
	}//end of constructor
	
	//check whether the user exists or not if not re-direct it to login or signup page
	public function isUser(){
		if(!$this->user) header('Location: /index');
		else return $this->user->name;
	}//end of isUser
	
} # eoc
