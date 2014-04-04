<?php
$sidebar = z::select('* FROM mgr_pages ORDER BY sort_order');
?>
<div class="sb-slidebar sb-left sb-static">
<ul>
	<li><a href="<?=ADMIN_BASE_URL;?>">Dashboard</a></li>
	<?php
	foreach($sidebar->records as $li){
		if($li->active ==1){
			echo '<li><a href="'.$li->url_alias.'">'.$li->page_title.'</a></li>';
		}
	}	
	?>
</ul>
</div><!-- eof slidebar list -->