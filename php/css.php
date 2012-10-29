<?php
	define('IN_PLOT', true); // avoid inc hack
	
	include_once('config.inc.php');
	include_once('db.inc.php');
	   /* tell the browser what we're sending */

    header('Content-type: text/css');
	echo get_css();

	
?>