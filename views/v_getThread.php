<div id="main_wrapper">
		<div id="holder">		
			<h4><?=$heading?></h4>
			<!-- Render content -->
			<?php 
				$threadType = $_GET['type'];
				$count = DB::instance(DB_NAME)->select_field("SELECT `total` FROM `types` WHERE `type_id` = ".$_GET['type']);
				
				$pages = ceil($count / $per_page);
				$current = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
				$start = ($current - 1) * $per_page;
				
				$threads = DB::instance(DB_NAME)->query("SELECT th.`thread_id`, th.`name` as threadname, th.`date`, th.`starter`, us.`name`											   
														   FROM `threads` th INNER JOIN `users` us ON th.`starter` = us.`user_id` 														  
														  WHERE th.`type` = $threadType
														  ORDER BY th.`date` DESC	
														  LIMIT	$start, $per_page");
				$code = "<ul>";
				while($tableRow = mysql_fetch_assoc($threads)){
					$code .= "<li>";
					$code .=  "<a href='viewThread?threadID=".$tableRow['thread_id']."' class=\"title\">".$tableRow['threadname']."</a>";
					$code .=  "<div class=\"meta\">";
					$code .=  "<span> Author: <a href=\"/index/viewProfile?userID=".$tableRow['starter']."\">".$tableRow['name']."</a></span>";
					$code .=  "<span> Posted On ".date('m-d-y, g:i a',$tableRow['date'])."</span>";
					$code .=  "</div>";
					$code .=  "</li>";
				}//end of while
				
				//close the unorder list and start pagination
				$code = $code."</ul><div id=\"pages\">";

		if($pages >= 1 && $current <= $pages){
				
				$code .= ($pages >= 2 && $current != 1) ? '<a href="?type='.$threadType.'&page='.($current - 1).'"><<</a></strong> ' : "";
				
				for($x = 1; $x<= $pages; $x++){ 
					//Bold face the currect selected page
					$code .= ($x == $current) ? '<strong><a href="?type='.$threadType.'&page='.$x.'"> '.$x.'</a></strong> ' : '<a href="?type='.$threadType.'&page='.$x.'"> '.$x.'</a>'; 
				}
				
				$code .= ($pages >= 2 && $current != $pages) ? '<a href="?type='.$threadType.'&page='.($current + 1).'">>></a></strong> ' : "";
			}//end of if
		
			//done pagination
			$code .= "</div>";
			
			echo $code;
			?>
		</div>
		
		<div id="addPost">
			<?=$addPost?>
		</div>
		
</div>