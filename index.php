<?php
include_once("bootstrap_admin.php");
$page_var = "dash";
if(isset($u)){$pages = z::select('* FROM mgr_pages ORDER BY sort_order');}
 
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Dashboard</title>
	
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
					page_var: "<?=$page_var;?>",	
					p_id:	row_id,
					title:	$("#"+row_id+"_title").val(),
					nav:	$("#"+row_id+"_nav_placement").val(),
					mobile:	$("#"+row_id+"_show_mobile").val(),
					active:	$("#"+row_id+"_active").val()
				}
			
			})
			.done(function( msg ) { 
				console.log(msg); 
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
		<h1 class="data-title">Manage Pages</h1>
		<table class="data-table">
		<!-- headings -->
		<thead>
		<tr>
			<th class="data-cell heading">Sort</th>
			<th class="data-cell heading">Title</th>
			<th class="data-cell heading">URL</th>
			<th class="data-cell heading">Nav</th>
			<th class="data-cell heading">Show Mobile</th>
			<th class="data-cell heading">Active</th>
			<th class="data-cell heading">Update</th>
		</tr>	
		</thead>
		<!-- eof headings -->
		<tbody>
		
		<?php
		foreach($pages->records as $p){ 
		echo '<tr>
		<td class="data-cell half-cell"><input type="text" value="'.$p->sort_order.'" id="'.$p->p_id.'_sort" size="2" /></td>
		<td class="data-cell"><input type="text" value="'.$p->page_title.'" id="'.$p->p_id.'_title" /></td>
		<td class="data-cell">'.$p->url_alias.'</td>
		<td class="data-cell">
			<select name="'.$p->p_id.'_nav_placement" id="'.$p->p_id.'_nav_placement">
		';	
		if($p->top_nav==1){ echo '<option value="top" selected>Top Nav</option>'; }
			else {echo '<option value="top">Top Nav</option>';}
		if($p->side_nav==1){ echo '<option value="side" selected>Side Nav</option>'; }
			else {echo '<option value="side">Side Nav</option>';}
		if($p->main_nav==1){ echo '<option value="main" selected>Main Nav</option>'; }
			else {echo '<option value="main">Main Nav</option>';}
		echo '		
			</select>
		</td>
		<td class="data-cell">
			<select name="'.$p->p_id.'_show_mobile" id="'.$p->p_id.'_show_mobile">
		';
		if($p->mobile_nav==1){ echo '<option value="1" selected>Mobile Nav</option>'; }
			else {echo '<option value="1">Mobile Nav</option>';}
		if($p->mobile_nav==0){ echo '<option value="0" selected>No Mobile Nav</option>'; }
			else {echo '<option value="0">No Mobile Nav</option>';}		
		echo '		
			</select>
		</td>
		<td class="data-cell">
			<select name="'.$p->p_id.'_active" id="'.$p->p_id.'_active">
		';
		if($p->active==1){ echo '<option value="1" selected>Active</option>'; }
			else {echo '<option value="1">Active</option>';}
		if($p->active==0){ echo '<option value="0" selected>Non-Active</option>'; }
			else {echo '<option value="0">Non-Active</option>';}		
		echo '	
			</select>
		</td>
		<td class="data-cell half-cell update-cell">
			<button class="update-btn" id="'.$p->p_id.'"><span class="icon" data-icon="&#xe022;"></span></button>
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