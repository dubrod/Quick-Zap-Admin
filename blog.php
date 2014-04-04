<?php
include_once("bootstrap_admin.php");
$page_var = "blog";
$page = z::select('* FROM mgr_blog ORDER BY blog_id DESC');
$blog_title = $page->records[0]->blog_title;
$post_id = $page->records[0]->blog_id;
 
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Blog Post Admin</title>
	
	<?php include_once("inc/head.php"); ?>
	
	<script>
	$("document").ready( function(){
		
		/*update page data*/
		$(".update-btn").click( function(){
			var row_id = this.id;
			
			$.ajax({
			type: "POST",
				url: "zap.update.php",
				data: {
					page_var:	"<?=$page_var;?>",	
					p_id:		row_id,
					title:		$("#"+row_id+"_title").val(),
					update_url:	$("input#"+row_id+"_update_url:checked").val(),
					active:		$("#"+row_id+"_active").val()
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
	
	<a class="green-btn" href="new_post"><span class="icon" data-icon="&#xe035;"></span> WRITE NEW BLOG POST</a>
	
	<div class="data-row blog-row">
		<h1 class="data-title">Manage Posts</h1>
		
		<table class="data-table">
		<!-- headings -->
		<thead>
		<tr>
			<th class="data-cell heading">Date</th>
			<th class="data-cell heading">Title</th>
			<th class="data-cell heading">URL</th>
			<th class="data-cell heading">Active</th>
			<th class="data-cell heading">Update</th>
			<th class="data-cell heading">Edit</th>
		</tr>	
		</thead>
		<!-- eof headings -->
		<tbody>
		
			<?php
			foreach($page->records as $p){ 
				echo '<tr>
				<td class="data-cell"> '.$p->blog_date.' </td>
				<td class="data-cell"><input type="text" value="'.$p->blog_title.'" id="'.$p->blog_id.'_title" /></td>
				<td class="data-cell">
					<a href="http://162.249.6.169/posts/'.$p->blog_url.'" target="_blank">'.$p->blog_url.'</a>
					<br>
					<input type="checkbox" name="'.$p->blog_id.'_update_url" id="'.$p->blog_id.'_update_url" /> <small>Update URL with Title Change</small>
				</td>
				<td class="data-cell">
					<select name="'.$p->blog_id.'_active" id="'.$p->blog_id.'_active">
					';
					if($p->blog_active==1){ echo '<option value="1" selected>Active</option>'; }
						else {echo '<option value="1">Active</option>';}
					if($p->blog_active==0){ echo '<option value="0" selected>Non-Active</option>'; }
						else {echo '<option value="0">Non-Active</option>';}		
					echo '	
					</select>
				</td>
				<td class="data-cell half-cell update-cell">
					<button class="update-btn" id="'.$p->blog_id.'"><span class="icon" data-icon="&#xe022;"></span></button>
				</td>
				<td class="data-cell half-cell update-cell">
					<a class="edit-btn" href="edit_post?id='.$p->blog_id.'"><span class="icon" data-icon="&#xe036;"></span></a>
				</td>
				</tr>
				';
			} 
			?>
		</tbody>
		</table>
			
			<div id="form_error"></div>
			
	</div><!-- eof data-row -->
	
	
		
	</div><!-- eof container -->
	</section>
</div>

<?php

include_once("admin_sidebar.php"); 

} else { include("login_form.php"); }

?>
	
</body>
</html>