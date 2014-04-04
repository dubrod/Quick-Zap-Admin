<?php

include_once("bootstrap_admin.php");
$page_var = "edit-post";

if(isset($_GET["id"])){
	$post_id = $_GET["id"];
	$post_records = z::select('* FROM mgr_blog WHERE blog_id = "'.$post_id.'"');
	$post = $post_records->records[0];
}       
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
		$("#submit_edit").click( function(){
			
			$.ajax({
			type: "POST",
				url: "zap.update.php",
				data: {	
					page_var:		"<?=$page_var;?>",
					p_id:			<?=$post->blog_id;?>,
					title:			$("#page_title").val(),
					update_url:		$("input#update_url:checked").val(),
					date:			$("#page_date").val(),
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
	
	<?php if(!$post_id){ ?>
	
	<div class="data-row">
		<h1 class="data-title">No Post Selected</h1>
		<div class="gutter">Please go back and select a post.</div>
	</div>
		
	<?php
	
	} else {
		
	?>
	
	<div class="data-row">
		<h1 class="data-title">Edit Post: <?=$post->blog_title;?></h1>
		
		<div class="default-form">
			
			<div class="gutter">
			
				<label>Title</label>
				<input type="text" id="page_title" name="page_title" value="<?=$post->blog_title;?>" />
				<br><br>
				<input type="checkbox" name="update_url" id="update_url" /> Update URL with Title Change
				<br><br>
				<label>Date</label>
				<input type="text" id="page_date" name="page_date" value="<?=$post->blog_date;?>" />
				<br><br>
				<label>Active</label>
				<select id="page_active">
				<?php	
					if($post->blog_active==1){ echo '<option value="1" selected>Yes</option>'; }
						else {echo '<option value="1">Yes</option>';}
					if($post->blog_active==0){ echo '<option value="0" selected>No</option>'; }
						else {echo '<option value="0">No</option>';}
				?>
					
				</select>
				
				<hr>
			</div>
			
			<div style="width:65%; float: left;">
				<div class="gutter">
					<label>Post</label>
					<br>
					<textarea id="page_post" name="page_post"><?=$post->blog_post;?></textarea>
				</div>
			</div>
			
			<div style="width:30%; float: right;">
				<div class="gutter markdown-help">
					<?php include_once("markdown_syntax.html"); ?>
				</div>
			</div>
			<div class="clear"></div>	
			
			
			<div class="gutter">
				<button class="green-btn" id="submit_edit"><span class="icon" data-icon="&#xe081;"></span> UPDATE</button>
			</div>
			
			<div id="form_error"></div>
				
		</div><!-- eof default-form -->
	
	</div><!-- eof data-row -->
	
	<?php include_once("file_uploader/form.php"); ?>
	
	<?php include_once("inc/show_uploads.php"); ?>
	
	<?php } // eof if $post_id ?>
	
	
	</div><!-- eof container -->
	</section>
</div>

<?php

include_once("admin_sidebar.php"); 

} else { include("login_form.php"); }

?>
	
</body>
</html>