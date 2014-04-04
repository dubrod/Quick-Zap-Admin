<?php

include_once("bootstrap_admin.php");
$page_class = "dynamic";
$page_var = "helping";
$page = z::select('* FROM mgr_helping');
$page_title = $page->records[0]->helping_title;
$page_post = $page->records[0]->helping_post;
 
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Helping TR Page Editing</title>
	
	<?php include_once("inc/head.php"); ?>
	
	<script>
	$("document").ready( function(){
	
				
		/*update page data*/
		$("#<?=$page_var;?>_id").click( function(){
			var row_id = this.id;
			
			$.ajax({
			type: "POST",
				url: "zap.update.php",
				data: {	
					page_class:	"<?=$page_class;?>",
					page_var:	"<?=$page_var;?>",
					page_title:		$("#page_title").val(),
					page_post:		$("#page_post").val()
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
		<h1 class="data-title">Editing the Helping TR Page</h1>
		
		<div class="default-form">
			
			<div class="gutter">
				<label>Title</label>
				<input type="text" id="page_title" name="page_title" value="<?=$page_title;?>" />
			</div>
			
			<div style="width:65%; float: left;">
				<div class="gutter">
					<label>Post</label>
					<br>
					<textarea id="page_post" name="page_post"><?=$page_post;?></textarea>
				</div>
			</div>
			
			<div style="width:30%; float: right;">
				<div class="gutter markdown-help">
					<?php include_once("markdown_syntax.html"); ?>
				</div>
			</div>
			<div class="clear"></div>	
			
			<div class="gutter">
				<button class="green-btn" id="<?=$page_var;?>_id"><span class="icon" data-icon="&#xe022;"></span> UPDATE</button>
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