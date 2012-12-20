<?php
	/*
	if ( !defined('IN_PLOT') )
	{
		die("Hacking attempt");
	}
	*/
	
	
	define('IN_PLOT', true); // to avoid inc hack
	
	
	include_once('file_browser.class.php');
	include_once('plotseme.inc.php');
	include_once('config.inc.php');
	
	
	$file_browser = new file_browser();
	
	
	if (isset($_REQUEST['path'])){
		$path = $_REQUEST['path'];
	}
	
	// send file browser content
	echo $file_browser->browse_dir($path,'name');

?>