<?php
# R3Login Lib - login.php
# Version 2.0
# 2 Step Checker
# still using zap : http://www.shayanderson.com/projects/zap.htm
# March 15 2014 - Year of our Lord
//ini_set("display_errors", "1");
//error_reporting(E_ALL);

define('GLOBAL_SALT', 'INTXCS.2014!');

require_once 'lib/config.php';
require_once 'lib/Zap/zap.bootstrap.php';

$location = htmlspecialchars($_POST['location']);
$username = htmlspecialchars($_POST['user']);
$password = htmlspecialchars($_POST['pass']);
	
	// HASH up the password
	$ENCpassword = hash_hmac('sha256', $password, GLOBAL_SALT);
	$l = z::select('* FROM admin WHERE username = {$1} AND password = {$2} LIMIT 1', $username, $ENCpassword);

		if($l->has_rows){
		 	
		 	$a = $l->records[0]->active;
		 	if($a = 0){
		 	
			 	header('Location: '.$location.'?error='.INACTIVE_USER.'');
		 	
		 	} else {
		 	
			 	// GET USER ID
			 	$i = $l->records[0]->id;
			 	
			 	ini_set("session.gc_maxlifetime", 9000); 
				session_start();
				$_SESSION['userid'] = $i;
				
				// RETURN TO ACCOUNT DASHBOARD
				//echo "Login Successful! ";
				header('Location: '.$location.'');
			
			}
		}
		
		if($l->error) // no rows returned, check for error
		{
		      echo 'Error: ' . z::recordset()->error . '';
		      
		}
		else // no rows returned, no error
		{
		      header('Location: '.$location.'?error='.NO_USER.'');
		      
		}

?>