<?php
//ini_set("display_errors", "1");
//error_reporting(E_ALL);

require_once 'lib/config.php';
require_once 'lib/Zap/zap.bootstrap.php';
require_once 'lib/markdown.php';

//GLOBAL VARIABLES

if(isset($_POST["page_class"])){
	$class = htmlspecialchars($_POST["page_class"]);
}

$page = htmlspecialchars($_POST["page_var"]);
$p_id = htmlspecialchars($_POST['p_id']); 
$title = htmlspecialchars($_POST['title']);
$date = htmlspecialchars($_POST['date']);
$nav_option = htmlspecialchars($_POST['nav']); 
$mobile_option = htmlspecialchars($_POST['mobile']);
$active = htmlspecialchars($_POST['active']);  
$update_url = $_POST['update_url'];
$url = str_replace(" ", "-", strtolower($title));
$post = preg_replace('/\t/', '', $_POST['page_post']); //take out tabs
//Markdown
$post_cache = Markdown($post);


//START FUNCTIONS

//CLASS "dynamic" update
if(isset($class)){
	if($class == "dynamic"){
		
		$query = "UPDATE mgr_".$page." SET ".$page."_title = '".$title."', ".$page."_post = '".$post."'  WHERE ".$page."_id = '1';";
		z::query($query);
		
		$markd_query = "UPDATE mgr_".$page." SET ".$page."_cached = '".$post_cache."' WHERE ".$page."_id = '1';";
		z::query($markd_query);
		
		echo "<script>location.reload(true);</script>";
	
	}
	//eof dynamic Update
}//


//DASH update
if($page == "dash"){
	
	z::update('mgr_pages SET page_title = {$1}, mobile_nav = {$2}, active = {$3} WHERE p_id = {$4}', $title, $mobile_option, $active, $p_id);
	
	if($nav_option == "top"){
		z::update('mgr_pages SET top_nav = "1", side_nav = "0", main_nav = "0" WHERE p_id = {$1}', $p_id);
	}
	
	if($nav_option == "side"){
		z::update('mgr_pages SET top_nav = 0, side_nav = 1, main_nav = 0 WHERE p_id = {$1}', $p_id);
	}
	
	if($nav_option == "main"){
		z::update('mgr_pages SET top_nav = 0, side_nav = 0, main_nav = 1 WHERE p_id = {$1}', $p_id);
	}
	
	echo "<script>location.reload(true);</script>";

}
//eof DASH Update

//BLOG update
if($page == "blog"){
 	
	//check for page dupe
	$ti_check = z::select('blog_title FROM mgr_blog WHERE blog_title = "'.$title.'" AND blog_id != "'.$p_id.'"');
	
	if($ti_check->records[0]){
	
		echo '
			<div class="ui-widget">
				<div class="ui-state-error ui-corner-all" style="margin:1%;">
					<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
					<strong>Alert:</strong> That page name is already taken.</p>
				</div>
			</div>
		';
		exit();
	
	} else {
		
		z::update('mgr_blog SET blog_title = {$1}, blog_active = {$2} WHERE blog_id = {$3}', $title, $active, $p_id);
		
		if($update_url=="on"){
			z::update('mgr_blog SET blog_url = {$1} WHERE blog_id = {$2}', $url, $p_id);
		}
		
		echo "<script>location.reload(true);</script>";
	}	

}
//eof BLOG Update

//NEW POST update
if($page == "post"){
	
	//check for page dupe
	$ti_check = z::select('blog_title FROM mgr_blog WHERE blog_title = "'.$title.'"');
	
	if($ti_check->records[0]){
		echo '
			<div class="ui-widget">
				<div class="ui-state-error ui-corner-all" style="margin:1%;">
					<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
					<strong>Alert:</strong> That page name is already taken.</p>
				</div>
			</div>
		';
		exit();
	} else {
	
		z::insert('mgr_blog (blog_title, blog_date, blog_post, blog_url, blog_active) VALUES ({$1}, {$2}, {$3}, {$4}, {$5})', $title, $date, $post, $url, $active);
		
		$markd_query = "UPDATE mgr_blog SET blog_cached = '".$post_cache."' WHERE blog_title = '".$title."';";
		z::query($markd_query);
		
		echo "<script>window.location.replace(\"".ADMIN_BASE_URL."blog\");</script>";
	}

}
//eof NEW POST Update

//EDIT POST update
if($page == "edit-post"){
	
	//check for page dupe
	$ti_check = z::select('blog_title FROM mgr_blog WHERE blog_title = "'.$title.'" AND blog_id != "'.$p_id.'"');
	
	if($ti_check->records[0]){
		echo '
			<div class="ui-widget">
				<div class="ui-state-error ui-corner-all" style="margin:1%;">
					<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
					<strong>Alert:</strong> That page name is already taken.</p>
				</div>
			</div>
		';
		exit();
	} else {
	
		z::update('mgr_blog SET blog_title = {$1}, blog_active = {$2}, blog_date = {$3}, blog_post = {$4} WHERE blog_id = {$5}', $title, $active, $date, $post, $p_id);
		
		if($update_url=="on"){
			z::update('mgr_blog SET blog_url = {$1} WHERE blog_id = {$2}', $url, $p_id);
		}
		
		z::update('mgr_blog SET blog_cached = {$1} WHERE blog_id = {$2}', $post_cache, $p_id);
		
		echo "<script>window.location.replace(\"".ADMIN_BASE_URL."blog\");</script>";
	}


}
//eof EDIT POST Update
?>