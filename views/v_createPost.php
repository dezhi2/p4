<div id="main_wrapper">
		<div id="holder">	
			<h4>New Thread</h4>
			<div id="userwrapper">
				<div id="disTitle">
					<label>Post Title</label>
					<input id="blogTitle" type="text">
				</div>
				
				<!--The tabs-->
				<ul>
					<li><a href="#userPost">New</a></li>
					<li><a id="draft" href="#draftPost">Draft</a></li>
				</ul>
				
				<!--Inside the tab-->
				<div id="userPost"><textarea id="blog" name="blog"></textarea>
					<div id="optionsForUser">
						<input id="saveBlog" type="button" value="Save Draft">
						<input id="PostBlog" type="button" value="Post Discussion">
						<input class="cbut" type="button" value="Cancel">
					</div>
				</div>
				
				<div id="draftPost">
					<textarea id="draftBlog" name="blog"></textarea>
					<!--menu-->
					<div id="optionsForUser">
							<input id="PostDraft" type="button" value="Post Draft">
							<input class="cbut" type="button" value="Cancel">
					</div>

				</div>
				
				<div id="statusBar"></div>
				
				<!--menu-->
				
				
				
			</div>
			
		</div>
		
	</div>
	