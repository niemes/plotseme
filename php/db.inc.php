<?php
	if ( !defined('IN_PLOT') )
	{
		die("Hacking attempt");
	}
	
	// connect to sql server.
	$dbconn = mysql_connect(SQL_HOST, SQL_USER, SQL_PASS);
	
	if (!$dbconn) {
		die('Could not connect to database: ' . mysql_error() . '\n Please check your mySQL log and pass.');
	}
	
	//set sql charset.
	mysql_query("SET NAMES 'utf8'", $dbconn);
	mysql_query("SET CHARACTER SET 'utf8'", $dbconn);
	
	// connet to sql database
	$dblink = mysql_select_db(SQL_DATABASE, $dbconn);
	
	if (!$dblink) { // no db , try to create it.
		log_err('creating sql db');
		mysql_query("CREATE DATABASE `".SQL_DATABASE."` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci", $dbconn);
		log_err(mysql_error());
		mysql_query("USE 1175", $dbconn);
		log_err(mysql_error());
	}
	
	if (!mysql_table_exists(SQL_TABLE_PAGES, SQL_DATABASE)){ // no tables , execute sql file.
		log_err('creating sql tables');
		execute_file('db/db.sql');
		log_err(mysql_error());
	}
	
	
	function db_dump(){
	
		$tableArray = array(SQL_TABLE_PAGES,SQL_TABLE_USERS);
		backup_tables(SQL_HOST,SQL_USER,SQL_PASS,SQL_DATABASE,$tableArray);
			
	}
	
	


/* backup the db OR just a table */
function backup_tables($host,$user,$pass,$name,$tables = '*')
{
  
  $link = mysql_connect($host,$user,$pass);
  mysql_select_db($name,$link);
  
  //get all of the tables
  if($tables == '*')
  {
    $tables = array();
    $result = mysql_query('SHOW TABLES');
    while($row = mysql_fetch_row($result))
    {
      $tables[] = $row[0];
    }
  }
  else
  {
    $tables = is_array($tables) ? $tables : explode(',',$tables);
  }
  
  //cycle through
  foreach($tables as $table)
  {
    $result = mysql_query('SELECT * FROM '.$table);
    $num_fields = mysql_num_fields($result);
    
    $return.= 'DROP TABLE '.$table.';';
    $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
    $return.= "\n\n".$row2[1].";\n\n";
    
    for ($i = 0; $i < $num_fields; $i++) 
    {
      while($row = mysql_fetch_row($result))
      {
        $return.= 'INSERT INTO '.$table.' VALUES(';
        for($j=0; $j<$num_fields; $j++) 
        {
          $row[$j] = addslashes($row[$j]);
          $row[$j] = preg_replace("/\n/","\\n",$row[$j]);
          if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
          if ($j<($num_fields-1)) { $return.= ','; }
        }
        $return.= ");\n";
      }
    }
    $return.="\n\n\n";
  }
  
  //save file
  $backupFile = FILE_BROWSER_ROOT_PATH."/".SQL_DATABASE . date("Y-m-d-H-i-s") . '.sql';
  $handle = fopen($backupFile,'w+');
	//$handle = fopen('db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
  fwrite($handle,$return);
  fclose($handle);
}
	
	function execute_file ($file) {

		// executes the SQL commands from an external file.
		
		if (!file_exists($file)) {
			$last_error = "The file $file does not exist.";
			return false;
		}
		$str = file_get_contents($file);
		
		
		if (!$str) {
			$last_error = "Unable to read the contents of $file.";
			return false;
		}
		
		$trans = array("@SQL_TABLE_PAGES@" => SQL_TABLE_PAGES, "@SQL_TABLE_USERS@" => SQL_TABLE_USERS);
		$str = strtr($str, $trans);
		
		$last_query = $str;
		
		// split all the query's into an array
		
		$sql = preg_split("/(;\s)+/", $str);
		foreach ($sql as $query) {
			if (!empty($query)) {
				$r = mysql_query($query);
		
				if (!$r) {
					$last_error = mysql_error();
					return false;
				}
			}
		}
		return true;
	}
	
	function mysql_table_exists($table,$db){ 
		
		$sql = "SHOW TABLES FROM $db";
		$result = mysql_query($sql);

		if (!$result) {
			echo "DB Error, could not list tables\n";
			echo 'MySQL Error: ' . mysql_error();
			exit;
		}

		while ($row = mysql_fetch_row($result)) {
			if( $row[0]==$table) return 1;
		}

		mysql_free_result($result);

		return 0; 
	}

	// GET PAGE CONTENT
	function page_get_content($page_name){
		
		$query = sprintf("SELECT * FROM ".SQL_TABLE_PAGES." WHERE name = '%s'",
		mysql_real_escape_string(stripslashes(html_entity_decode($page_name,ENT_QUOTES,"UTF-8"))));

		$result = mysql_query($query) or die('Get content, select, could not run query: ' . mysql_error());
		
		if (mysql_num_rows($result) == 0) {
			return false;
		} else {
			
			$rows = mysql_fetch_assoc($result) or die('Get content, fetch_row: ' . mysql_error());

			// send back AJAX string
			return $rows;
		}	
	}
	
	function page_get_text($page_name){
	

		$query = sprintf("SELECT text FROM ".SQL_TABLE_PAGES." WHERE name = '%s'",
		mysql_real_escape_string(stripslashes(html_entity_decode($page_name,ENT_QUOTES,"UTF-8"))));

		$result = mysql_query($query) or die('Get content, select, could not run query: ' . mysql_error());
		
		if (mysql_num_rows($result) == 0) {
			return $page_name;
		} else {
			
			$row = mysql_fetch_row($result) or die('Get content, fetch_row: ' . mysql_error());

			// send back AJAX string
			return ($row[0]);
		}	
	}
	
	// SET PAGE CONTENT
	function page_set_content($name,$text,$author,$keywords, $template){
		log_err('saving :' .($name));
		
		$modification_date = date("Y-m-d H:i:s"); // modification date is now.
		$ip = get_long_ip();
		
		// do the db update
		
		// delete page if content is null
		if ($text ==''){
			$query = sprintf("DELETE FROM ".SQL_TABLE_PAGES." WHERE name = '%s'",
			mysql_real_escape_string(stripslashes(html_entity_decode($name,ENT_QUOTES,"UTF-8"))));
			
			$result = mysql_query($query) or die('Set content, delete, could not run query: ' . mysql_error());
			log_err('page '.$name.' deleted');
			return;
		}
		
		// check for template column existence
		$result = mysql_query("SHOW COLUMNS FROM ".SQL_TABLE_PAGES." LIKE 'template'");
		$exists = (mysql_num_rows($result))?TRUE:FALSE;
		
		if (!$exists) { // update old db format : create template column
			$result = mysql_query("ALTER TABLE ".SQL_TABLE_PAGES." ADD COLUMN 'template' VARCHAR(255) NULL")or die("Create template column, could not run query: " . mysql_error());
			log_err("template table added");
		}
	
		// check if page allready exist
		$query = sprintf("SELECT name FROM ".SQL_TABLE_PAGES." WHERE name = '%s'",
		mysql_real_escape_string(stripslashes(html_entity_decode($name,ENT_QUOTES,"UTF-8"))));

		
		$result = mysql_query($query) or die('Set content, check exist, could not run query: ' . mysql_error());
		
		if (mysql_num_rows($result) == 0) {	// no page with this name, create it.
			$query = sprintf("INSERT INTO ".SQL_TABLE_PAGES." (name, text, keywords, modification_date, creation_date, ip, author, template)
			VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');",
			mysql_real_escape_string(stripslashes(html_entity_decode($name,ENT_QUOTES,"UTF-8"))),
			mysql_real_escape_string(stripslashes(html_entity_decode($text,ENT_QUOTES,"UTF-8"))),
			mysql_real_escape_string(stripslashes(html_entity_decode($keywords,ENT_QUOTES,"UTF-8"))),
			mysql_real_escape_string(stripslashes($modification_date)),
			mysql_real_escape_string(stripslashes($modification_date)),
			mysql_real_escape_string(stripslashes($ip)),
			mysql_real_escape_string(stripslashes(html_entity_decode($author,ENT_QUOTES,"UTF-8"))),
			mysql_real_escape_string(stripslashes(html_entity_decode($template,ENT_QUOTES,"UTF-8"))));
		
		} else {
			$query = sprintf("UPDATE ".SQL_TABLE_PAGES." SET text = '%s', keywords= '%s', author= '%s', modification_date = '%s', ip = '%s', template = '%s' 
			WHERE name = '%s' LIMIT 1;",
			mysql_real_escape_string(stripslashes(html_entity_decode($text,ENT_QUOTES,"UTF-8"))),
			mysql_real_escape_string(stripslashes(html_entity_decode($keywords,ENT_QUOTES,"UTF-8"))),
			mysql_real_escape_string(stripslashes(html_entity_decode($author,ENT_QUOTES,"UTF-8"))),
			mysql_real_escape_string(stripslashes($modification_date)),
			mysql_real_escape_string(stripslashes($ip)),
			mysql_real_escape_string(stripslashes(html_entity_decode($template,ENT_QUOTES,"UTF-8"))),
			mysql_real_escape_string(stripslashes(html_entity_decode($name,ENT_QUOTES,"UTF-8"))));
		}
		
		$result = mysql_query($query) or die('Set content, update, could not run query: ' . mysql_error());

	}
	
	function get_keywords() {
		
		$query = "SELECT keywords FROM ".SQL_TABLE_PAGES;
		$result = mysql_query($query);
		$list = array();
		if ($result) {
			while (list($keyword) = mysql_fetch_row ($result)) {	
				if(trim($keyword) !=""){ // no empty keyword
					$string = preg_split("/[,]+/",($keyword));
					
					$list = array_merge($list, $string);
					
				}
			}
			array_walk($list, 'trim_value');
			
			$list = (array_unique($list));
			foreach ($list as $value) {
				$return_string .= "{<a href=\"javascript:addToKeywordsFieldFunction('".addslashes($value)."')\">".trim($value)."</a>} ";
			}
			//return $return_string;
			return stripslashes($return_string);
		} else {
			return "";
		}
		
	}
	
	function get_css() {
		
		$query = "SELECT text FROM ".SQL_TABLE_PAGES." WHERE name = '.css'";
		
		$result = mysql_query($query) or die('Get css, select, could not run query: ' . mysql_error());
		
		if (mysql_num_rows($result) == 0) {
			return 'no ccs';
		} else {
			
			$row = mysql_fetch_row($result) or die('Get css, fetch_row: ' . mysql_error());
			
			// send back CSS string
			return $row[0];
		}	
		
	}
	
	function get_template($template) {
		
		if (trim($template)=="") return null;
		
		$query = "SELECT text FROM ".SQL_TABLE_PAGES." WHERE name = '".mysql_real_escape_string(stripslashes(html_entity_decode($template,ENT_QUOTES,"UTF-8")))."'";
		
		$result = mysql_query($query) or die('Get skeleton, select, could not run query: ' . mysql_error());
		
		//log_err("num row:".mysql_num_rows($result));
		
		if (mysql_num_rows($result) == 0) {
			log_err('template not found');
			return null;
		} else {
			log_err('template '.$template.' found');
			$row = mysql_fetch_row($result) or die('Get template, fetch_row: ' . mysql_error());
			
			// send back skeleton string
			return preg_replace("'[\n]'", "",$row[0]);
		}	
	}
	
	
	function trim_value(&$value) { 
		$value = trim($value); 
	}
?>