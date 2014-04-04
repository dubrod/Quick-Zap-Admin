<?php
//photo upload
	//include_once('process/add-photo.php');
	//$Photo_Section = new Photo_Section;
	//$filesuccess = $Photo_Section->upload_file($_POST, $_POST['uploadphoto']);
	
	/**
	 * @category       PHP5.4 Progress Bar
	 * @author         Pierre-Henry Soria <ph7software@gmail.com>
	 * @copyright      (c) 2012, Pierre-Henry Soria. All Rights Reserved.
	 * @license        CC-BY License - http://creativecommons.org/licenses/by/3.0/
	 * @version        1.0.0
	 */
	
	/**
	 * Check the version of PHP
	 */
	if (version_compare(phpversion(), '5.4.0', '<'))
	    exit('ERROR: Your PHP version is ' . phpversion() . ' but this script requires PHP 5.4.0 or higher.');
	
	/**
	 * Check if "session upload progress" is enabled
	 */
	if (!intval(ini_get('session.upload_progress.enabled')))
	    exit('session.upload_progress.enabled is not enabled, please activate it in your PHP config file to use this script.');
	
	require_once 'Upload.class.php';
	
?>	

<link rel="stylesheet" href="file_uploader/file_uploader.css" media="all" />

<div class="data-row">
		<h1 class="data-title">Upload a Photo</h1>
		
		<div class="gutter">
		
		<div id="quickUpload">

			<!-- Debug Mod --> 
			<!-- <form action="file_uploader/upload.php?show_transfer=on" method="post" id="upload_form" enctype="multipart/form-data" target="result_frame"> -->
			<form action="file_uploader/upload.php" method="post" id="upload_form" enctype="multipart/form-data" target="result_frame">
			      <fieldset>
			      	  
			          <input type="hidden" name="<?php echo ini_get('session.upload_progress.name');?>" value="<?php Upload::UPLOAD_PROGRESS_PREFIX ?>" />
			          <input type="file" name="files[]" id="file" multiple="multiple" accept="image/*" required="required" />
			          <br>
			          <!--<small><em>You can select multiple files at once by clicking multiple files while holding down the "CTRL" key.</em></small>-->
			          <button type="submit" id="upload" class="green-btn"><span class="icon" data-icon="&#xe081;"></span> UPLOAD</button>
			          <!--<button type="reset" id="cancel">Cancel Upload</button>-->
			
			      <!-- Progress bar here -->
			      <div id="upload_progress" class="hidden center progress">
			          <div class="bar"></div>
			      </div>
			
			      </fieldset>
			  </form>
			
			  <iframe id="result_frame" name="result_frame" src="about:blank"></iframe>
			
			
		</div>
		
		</div>
</div><!-- eof data-row -->


<script src="file_uploader/ProgressBar.class.js"></script>
  <script>
  $('#upload').click(function() { (new UploadBar).upload(); });
  $('#cancel').click(function() { (new UploadBar).cancel(); });
  </script>