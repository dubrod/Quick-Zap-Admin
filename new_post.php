<?php

include_once("bootstrap_admin.php");
$page_var = "post";

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>About Page Editing</title>
	
	<?php include_once("inc/head.php"); ?>
	
	<script>
	$("document").ready( function(){
		
		$( "#datepicker" ).datepicker({
			inline: true
		});
			
		/*update page data*/
		$("#submit_new").click( function(){
			
			$.ajax({
			type: "POST",
				url: "zap.update.php",
				data: {
					page_var:		"<?=$page_var;?>",	
					title:			$("#page_title").val(),
					date:			$("#datepicker").val(),
					page_post:		$("#page_post").val(),
					active:			$("#page_active").val()
				}
			})
			.done(function( msg ) { 
				//console.log(msg); 
				$("#form_error").html(msg);
			});

		});
		
	});
	</script>	
	
</head>
<body>
<?php
if(isset($u)) {
//content
?>

<div id="sb-site">
	<?php include_once("inc/topbar.php"); ?>
	
	<section>
	
	<div class="container">
	
	<div class="data-row">
		<h1 class="data-title">Create Your New Post</h1>
		
		<div class="default-form">
			
			<div class="gutter">
			
				<label>Title</label>
				<input type="text" id="page_title" name="page_title" value="" />
				<br><br>
				<label>Date</label><br>
				<div id="datepicker"></div>
				<br><br>
				<label>Active</label>
				<select id="page_active">
					<option value="1">Yes</option>
					<option value="0">No</option>
				</select>
				
				<hr>
			</div>
			
			<div style="width:65%; float: left;">
				<div class="gutter">
					<label>Post</label>
					<br>
					<textarea id="page_post" name="page_post"></textarea>
				</div>
			</div>
			
			<div style="width:30%; float: right;">
				<div class="gutter markdown-help">
					<?php include_once("markdown_syntax.html"); ?>
				</div>
			</div>
			<div class="clear"></div>	
			
			
			<div class="gutter">
				<button class="green-btn" id="submit_new"><span class="icon" data-icon="&#xe081;"></span> POST</button>
			</div>
			
			<div id="form_error"></div>
				
		</div><!-- eof default-form -->
	
	</div><!-- eof data-row -->
	
	<?php include_once("file_uploader/form.php"); ?>
	
	<?php include_once("inc/show_uploads.php"); ?>
	
	</div><!-- eof container -->
	</section>
</div>

<?php

include_once("admin_sidebar.php"); 

} else { include("login_form.php"); }

?>
	
</body>
</html>