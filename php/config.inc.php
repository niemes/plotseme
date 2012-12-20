<?php
	if ( !defined('IN_PLOT') )
	{
		die("Hacking attempt");
	}
	
	// defines
	
	
	define("SQL_HOST", "localhost");					// The web server MySql ("localhost" or "sql.xxx.com"...)
	define("SQL_USER", "mysqllogin");						// MySql login
	define("SQL_PASS", "mysqlpassword");					// MySql password
	define("SQL_DATABASE", "databasename");						// MySql database name
	define("SQL_TABLE_PAGES", "plot_pages");			// MySql pages table name
	define("SQL_TABLE_SESSIONS", "plot_sessions"); 		// Mysql sessions table name
	define("SQL_TABLE_USERS", "plot_users"); 			// Mysql sessions table name
	define("SQL_TABLE_BACK_LINKS", "plot_back_links");
	
	define("FILES_FOLDER_NAME", "files");
	define("SHOW_INVISBLE_PAGES", false); 				// show invisible pages to non-admin users ?
	define("DEBUG", false); 								// debug ?
	define("SITE_TITLE", "plotseme"); 					// web site title
	
	define("ADMIN_USER_NAME", "plot"); 					// master user name
	define("ADMIN_USER_PASSWORD", "plot");				// master user password
	
	define("SITE_DESCRIPTION","plotseme web site");
	define("SITE_KEYWORDS","plotseme, plot");
	
	define("SITE_INDEX","Index");					// site page index name
	define("DEFAULT_TEMPLATE",".default_template"); // default template page name


	// do not change anything bellow if you don't know what you'r doing
	
	
	if (get_magic_quotes_gpc()) {
    	function stripslashes_gpc(&$value)
    	{
        	$value = stripslashes($value);
    	}
		array_walk_recursive($_GET, 'stripslashes_gpc');
		array_walk_recursive($_POST, 'stripslashes_gpc');
		array_walk_recursive($_COOKIE, 'stripslashes_gpc');
		array_walk_recursive($_REQUEST, 'stripslashes_gpc');
	}
	
	/* Set internal character encoding to UTF-8 */
	mb_internal_encoding("UTF-8");
	
	/* Display current internal character encoding */
	// echo mb_internal_encoding();
	
	$path_parts = pathinfo($_SERVER['SCRIPT_FILENAME']);
	define("FILE_BROWSER_ROOT_PATH",$path_parts['dirname'].'/'.FILES_FOLDER_NAME);
	
	error_reporting (E_ALL ^ E_NOTICE);
	
	define('MODREWRITE', true);
?>