<?php
# R3Login Lib - Logout.php
# Version 2.0
# March 15 2014 - Year of our Lord
   		
	unset($user);
	session_start();
	session_unset();
	session_destroy();
	
	header('Location: /intx-admin');	//logout and send back to index 
       	
?>       	