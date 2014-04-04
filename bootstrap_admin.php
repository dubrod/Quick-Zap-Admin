<?php
//phpinfo();
//ini_set("display_errors", "1");
//error_reporting(E_ALL);

session_start();

require_once 'lib/config.php'; 	
require_once 'lib/Zap/zap.bootstrap.php'; 

if(isset($_SESSION['userid'])) {
	$user = z::select('* FROM admin WHERE id = {$1} LIMIT 1', $_SESSION['userid']);
	$u = $user->records[0];
}
?>