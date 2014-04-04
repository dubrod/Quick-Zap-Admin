<div class="data-row">

	<h1 class="data-title">Available Images</h1>
		
	<div class="gutter">
		
		<?php
		$upl_dir    = '../uploads/';
		$photos = scandir($upl_dir);
		$allowedExts = array("gif", "jpeg", "jpg", "png");
		
		foreach($photos as $p){
			
			$p_ext = explode(".", $p);
			$extension = end($p_ext);
			
			if(in_array($extension, $allowedExts)){
				//echo $extension;
				echo '
					<div class="uploaded-cell col-six">
						<img src="../uploads/'.$p.'"><br>
						'.$p.'
					</div>	
				';
				
			}
		}
		
		?>
		
		<div class="clear"></div>
	</div>	
	
</div>	