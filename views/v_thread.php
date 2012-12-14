<div id="main_wrapper">
	
	<h4><?=$blogTitle;?></h4>
	<!-- Render content -->
			<?php 
				$threadID = $_GET['threadID'];
				//begin pagination set current page
				$pages = ceil($total / $per_page); // both parameters coming from controller
				$current = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
				$start = ($current - 1) * $per_page;
				
				//get posts
				$posts = DB::instance(DB_NAME)->query("SELECT u.`user_id`, u.`name`, u.`headshot`, u.`created`, u.`modified`, p.`content`, p.`time_stamp` 
														 FROM `posts` p INNER JOIN `users` u ON p.`user_id` = u.`user_id`
														WHERE p.`thread_id` = $threadID
													 ORDER BY p.post_id ASC
													    LIMIT $start, $per_page");
				
				//$user is a global variable - see c_base.php 
				/*if($user) echo $user->user_id;
				*/
				$code = "<ul id=\"piece\">";
				$counter = 1;
				while($tableRow = mysql_fetch_assoc($posts)){
					
					$code .= "<li>";
					$code .= "<div id=\"userinfo\"><div><a href=\"/index/viewProfile?user=".$tableRow['user_id']."\">";
					$code .= "<img width=70px height=50px src='".$tableRow['headshot']."'><br>".$tableRow['name']."</a></div>";
					$code .= "<span>Member Since: ".date('m/d/y', $tableRow['created'])."</span><br>";
					$code .= "<span>last login: ".date('m-d-y, g:i a', $tableRow['modified'])."</span><br>";
					
					if($user &&($user->user_id == $tableRow['user_id'])){
						$code .= "<table>";
						$code .= "<tr alt='update' threadID=".$_GET['threadID']." postID=".$counter.">";
						$code .= "<td><span class='ui-icon ui-icon-pencil'></span><span><b>Edit</b></span></td></tr>";
						$code .= "<tr alt='delete' threadID=".$_GET['threadID']." postID=".$counter.">";
						$code .= "<td><span class='ui-icon ui-icon-trash'></span><span><b>Remove</b></span></td></tr>";
						$code .= "<tr alt='check' threadID=".$_GET['threadID']." postID=".$counter." style='display:none;'>";
						$code .= "<td><span class='ui-icon ui-icon-check'></span><span><b>Done</b></span></td></tr>";
						$code .= "<tr alt='cancel' style='display:none;'><td><span class='ui-icon ui-icon-close'></span><span><b>Cancel</b></span></td></tr>";
						$code .= "</table>";
					}//end of if
					
					$code .= "</div><div class=\"userContent\">".$tableRow['content']."</div>";
					$code .= "<div class=\"dateinfo\">Posted on ".date('m-d-y, g:i a', $tableRow['time_stamp'])."</div>";	
					$code .= "<div id='notes' style='display:none;'></div>";
					$code .= "</li>";
					
					$counter = $counter + 1;
				}//end of while loop
				
			$code .= "</ul><div id=\"pagination\">";
				
			if($pages >= 1 && $current <= $pages){
			
			$code .= ($pages >= 2 && $current != 1) ? '<a href="?threadID='.$threadID.'&page='.($current - 1).'"><<</a>' : '' ; 
			
			for($x = 1; $x<= $pages; $x++){
			
				//Bold face the currect selected page
				$code .= ($x == $current) ? '<strong><a href="?threadID='.$threadID.'&page='.$x.'">'.$x.'</a></strong> ' : '<a href="?threadID='.$threadID.'&page='.$x.'">'.$x.'</a>'; 
			
			}//end of for loop		
			
				$code .= ($pages >= 2 && $current != $pages) ? '<a href="?threadID='.$threadID.'&page='.($current + 1).'">>></a>' : '' ; 	
			
			}//end of if statement
			
			$code .= "</div>";
			
			echo $code;
			?>
			<?=$replyPost?>
</div>