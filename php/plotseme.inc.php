<?php
	$log_message = array();
	
	if ( !defined('IN_PLOT') )
	{
		die("Hacking attempt");
	}
	
	function get_login_form($page_refer,$admin){
		global $user;
		
		$return = "<form id='login_form' method='post' action='javascript:loginFunction();'>\n
		<input id='refer' type='hidden' value=\"".($page_refer)."\" />\n";
		if ($admin==true){
			log_err('from admin');
			$return .= "<input id='admin' type='hidden' value=\"".($page_refer)."\" />\n";
		}
		$return .= "<table><tr><td>Login</td><td><input type='text' size='25' id='user' value='$user->user_name' /></td></tr>\n
		<tr><td>Password</td><td><input type='password' size='25' id='password' value='' /></td></tr>\n
		<tr><td><input type='submit' value='Login' /></td></tr></table>\n
		</form>\n";
		
		return $return;
	}
	
	function get_admin_form($page_refer){
				
			// users list
			$admin_html = "<form id='user_list_form' method='post'>\n
			<input type='hidden' name='list_user' />
			<table width='100%'>\n
			<caption align='top'><b>User list</b></caption>\n
			<tr bgcolor='#999999'>\n
			<th>&bull;</th><th>Name</th><th>E-mail</th>\n
			<th>Description</th>\n
			<th>Login</th>\n
			<th>Privilege</th>\n
			</tr>";
			
			$user_query = "SELECT * FROM ".SQL_TABLE_USERS." ORDER by privilege DESC,name;";
			$user_result = mysql_query($user_query);
			
			if (!$user_result) {
				log_err($user_query);
				log_err( "user list, Erreur de lecture MySql");
				log_err( mysql_error());
			} else { 
				
				while ($a_user=mysql_fetch_assoc($user_result)) {
					
					$admin_html .= "<tr bgcolor='#DDDDDD'><td width='20'><input type='checkbox' name='user_id' id='user_id' value=\"".$a_user["id"]."\" /></td>";
					$admin_html .= "<td>".$a_user['name']."</td>";
					$admin_html .= "<td>".$a_user['email']."</td>";
					$admin_html .= "<td>".$a_user['description']."</td>";
					$admin_html .= "<td>".$a_user['login']."</td>";
					switch ($a_user['privilege']) {
						case 0:
							$admin_html .= "<td>Guest</td></tr>";
							break;
						case 1:
							$admin_html .= "<td>Simple User</td></tr>";
							break;
						case 2:
							$admin_html .= "<td>Administrator</td></tr>";
							break;
						default :
							$admin_html .= "<td>Unknow</td></tr>";
					}
				}
				
			}
			
			
			$admin_html .= "<tr><td colspan=\"6\"><input type=\"button\" value=\"Delete selected\" name=\"deleteuser\" onmouseup='javascript:delUserFunction();'/></td></tr></table></form>";
			
			$admin_html .= "<form id='new_user_form' method='post'>
			<input name='newuser' type='hidden' />
			<table><caption align='top'><b>User creation</b></caption>
			<tr><td>Name</td><td colspan='3'><input id='user_name' type='text' size='40' maxlength='255' /></td></tr>
			<tr><td>E-mail</td><td colspan='3'><input id='user_email' type='text' size='40' maxlength='255' /></td></tr>
			<tr><td valign='top'>Description</td><td colspan='3'><textarea id='user_description' rows='5' cols='37'></textarea></td></tr>
			<tr><td>Login</td><td colspan='3'><input id='user_login' type='text' size='40' maxlength='255' /></td></tr>
			<tr><td>Password</td><td colspan='3'><input id='user_password' type='text' size='40' maxlength='255' /></td></tr>
			<tr><td>Confirm password</td><td colspan='3'><input id='user_password_confirm' type='text' size='40' maxlength='255' /></td></tr>
			<tr><td>Privilege</td><td><input name='user_privilege' id='user_privilege' type='radio' value='0' checked='checked' /> Guest</td><td>
			<input name='user_privilege' id='user_privilege' type='radio' value='1' /> Simple User</td><td>
			<input name='user_privilege' id='user_privilege' type='radio' value='2' /> Administrator</td></tr>
			<tr><td colspan='4'><input type='button' value='Create user' onmouseup='javascript:newUserFunction();'/></td></tr>
			</table>
			<input type=\"button\" value=\"Dump DB\" name=\"dumpdb\" onmouseup='javascript:dumpDBFunction();'/>
			</form>";
			
			
			return $admin_html;		
		
	}
	
	
	function agregate_callback($matches) {
		global $user;
		 
		// build the query string
		$query = "SELECT name,keywords,modification_date FROM ".SQL_TABLE_PAGES;
		$query .= " WHERE(TRIM(IFNULL(keywords,'')) <> '')"; // only select pages with keywords
		
		if (!(SHOW_INVISBLE_PAGES or ($user->user_privilege>1))) {
			$query .= " AND (name NOT LIKE '.%')"; // don't show hidden pages
		}
		
		$query .= " ORDER BY modification_date DESC;"; 
		
		// run the query
		$result= mysql_query($query);
		//var_dump($result);
		$pageCount = mysql_num_rows($result);
		
		// buil array of keywords from query string. eg {foo, barr}
		$pagesArray = array();
		$queryKeywords = preg_split("/[,]+/", $matches[1]);
		
		// clean up the array (remove empty values).
		foreach($queryKeywords as $key => $value) {
			if(empty($value)) unset($queryKeywords[$key]);
		}
		log_err('{} keywords:'.$queryKeywords. '->'.count($queryKeywords));
		
		if (($pageCount>0)&&(count($queryKeywords)>0)) {

			
			while ($row = mysql_fetch_assoc($result)) {
				$pageKeywords = preg_split("/[,]+/", $row['keywords']);
				for ($pageKeywordsIndex=0; $pageKeywordsIndex < count($pageKeywords); $pageKeywordsIndex++) { 
					$aKeyword = stripslashes(html_entity_decode(trim($pageKeywords[$pageKeywordsIndex]),ENT_QUOTES,"UTF-8"));
					for ($queryKeywordsIndex=0; $queryKeywordsIndex < count($queryKeywords); $queryKeywordsIndex++) {
						$aQueryKeyword = stripslashes(html_entity_decode(trim($queryKeywords[$queryKeywordsIndex]),ENT_QUOTES,"UTF-8"));
						if (strcasecmp($aQueryKeyword,$aKeyword)==0)
							$pagesArray[]=trim($row['name']);	
					}
				}
			}
			
			$pagesArray = array_unique($pagesArray);
		} elseif (($pageCount>0)&&(count($queryKeywords)==0)) { // list all pages if query string is empty : {}
			while ($row = mysql_fetch_assoc($result)) {
				$pagesArray[]=trim($row['name']);
			}
		}
		
		
		
		foreach ($pagesArray as $pname) {
    		$text .= "<li>[".htmlentities($pname, ENT_QUOTES, "UTF-8")."]</li>";
		}

			
		log_err($query);
		
		mysql_free_result($result);
		
		return $text;
	}
	
	function reserve_callback($matches){
		global $reserve;
	
		$reserve[] = html_entity_decode(str_replace("\n","",$matches[1]));
		return '%RESERVE%';
	}
	
	
	function reserve_back_callback($matches){
		global $reserve_back_count;
		global $reserve;
			
		$reserve_back_count ++;
		//return html_entity_decode(str_replace("\n","",$reserve[$reserve_back_count-1]));	
		return html_entity_decode($reserve[$reserve_back_count-1]);		
	}

	
	function get_search_form() { // return search form
		return "<input type='search' id='search_text' placeholder='recherche' results='5' autosave='".SITE_TITLE.".search_history' accesskey= 's' onkeypress=\"var key=event.keyCode || event.which; if (key==13) {javascript:searchFunction();}\"/>";
	}
	
	
	function get_search_result($search_string) {
		global $user;
		
		$keywords = preg_split("/[\s,]+/", mysql_escape_string(trim($search_string)));
		
		if (count($keywords) > 0){
			//print_r($keywords);
			$query = "SELECT name FROM ".SQL_TABLE_PAGES." where (";
			
			if ( trim($keywords != "")) { // valid word ?
				$or = "";
				foreach ($keywords as $word) { // loop all words
					$query .= "$or(text LIKE'%$word%') OR ";
					$query .= "(name LIKE'%$word%') OR ";
					$query .= "(author LIKE'%$word%') OR ";
					$query .= "(keywords LIKE'%$word%')";
					$or = " OR ";
				}	
			}
		}
		
		if (!(SHOW_INVISBLE_PAGES or ($user->user_privilege>1))) {
			$query .= ") AND (name NOT LIKE'.%')"; // don't show hidden pages
		} else {
			$query .= ") ";
		}
		
		$query .= " ORDER BY modification_date DESC";
		
		//echo $user->user_privilege>1;
		$result=mysql_query($query);
		$i = mysql_num_rows($result);
		
		if ($i>0) {
			if ($i == 1) {
				$text .= $i." occurrence de [b] $search_string [/b]trouv&#xE9;e.";
			} else {
				$text .= $i." occurrences de[b] $search_string [/b]trouv&#xE9;es.";
			}
			
			$text .= "[list]";
			while ($row = mysql_fetch_assoc($result)) {
				$text .= "[".htmlentities($row['name'], ENT_QUOTES, "UTF-8")."]\n";
			}
			$text .= "[/list]";
		} else {
			$text .= "[b]D&#xE9;sol&#xE9;, aucune occurrence de $search_string n'a &#xE9;t&#xE9; trouv&#xE9;e.[/b]\n";
		}
		mysql_free_result($result);
		
		return $text;
	}
	
	function count_hit() {
		//return(0);
		
		// set the default timezone to use. Available since PHP 5.1
		if (version_compare(PHP_VERSION, '5.1.0') >= 0) date_default_timezone_set('UTC');

		$count_file = "counter.txt";
		$log_file = "log.txt";
		$today = date("d.m.y");
		$r_ip = $_SERVER['REMOTE_ADDR'];
		
		if ( !file_exists($count_file)){ // get count file
			touch ($count_file);
			$count_file_handle = fopen ($count_file, 'r+'); // Let's open for read and write
			$count = 0;
		} else {
			$count_file_handle = fopen ($count_file, 'r+'); // Let's open for read and write
			$count = fread ($count_file_handle, @filesize ($count_file));
			settype ($count,"integer");
		}
		
		if ( !file_exists($log_file)){ // get ip and date file
			touch ($log_file);
			$log_file_handle = fopen ($log_file, 'r+'); // Let's open for read and write
			$log = "[".$today.":".$r_ip."]";
		} else {
			$log_file_handle = fopen ($log_file, 'r+'); // Let's open for read and write
			$log = fread($log_file_handle, @filesize ($log_file));
		}
		
		// get last day log
		$in_pos = strpos($log,'[');
		$out_pos = strpos($log,']');
		$last_log = substr($log,$in_pos+1,($out_pos-$in_pos)-1);
		
		$sub_pos = strpos($last_log,':');
		$last_day = substr($last_log,0,$sub_pos); // get last day date
		$allready_seen = false;
		
		if ( !($last_day == $today)){ // new day log
			$last_log = "[".$today.":".$r_ip."]";
			$log = $last_log."\n".$log;
			rewind ($count_file_handle); // Go back to the beginning
			fwrite ($count_file_handle, ++$count); // Increment the counter
			rewind ($log_file_handle); // Go back to the beginning
			fwrite ($log_file_handle, $log); // Write the remote IP 
			
		} else {
			$last_ips = (explode(",",substr($last_log,$sub_pos+1))); // get last day IPs array
			foreach($last_ips as $ip) { // loop thrue IPs
				if ($r_ip == $ip) {
					$allready_seen = true;
					break;
				}
			}
			
			if ( !($allready_seen == true)) { // new unic IP hit today
				
				@rewind ($count_file_handle); // Go back to the beginning
				@fwrite ($count_file_handle, ++$count); // Increment the counter
				
				//array_push($last_ip,$r_ip);
				$last_log = ($last_day.":".implode(",", $last_ips));
				//echo $last_log."<br>";
				$log = substr_replace($log,"[".$last_log.",".$r_ip."]",0,$out_pos+1);
				//echo $log."<br>";
				//fseek ($log_file_handle,$out_pos); // Go to last IPs array element in log file
				@rewind ($log_file_handle); // Go back to the beginning
				@fwrite ($log_file_handle, $log); // Write the remote IP 
			} else {
				//echo "allready seen<br>";
			}
		}
		
		
		@fclose ($count_file_handle); // Done 
		@fclose ($log_file_handle); // Done
		return count($last_ips)."/".$count;
	}
	
	function get_request($request){
		
		if (isset($_REQUEST[$request]) )
			return	htmlentities(urldecode(trim($_REQUEST[$request])),ENT_QUOTES,"UTF-8");
			//return	urldecode(htmlentities(trim($_REQUEST[$request]),ENT_QUOTES,"UTF-8"));

		else 
			return null;
		
	}
	
	function get_post($post){
		return	htmlentities(urldecode(trim($_POST[$post])),ENT_QUOTES,"UTF-8");

		//return	urldecode(htmlentities(trim($_POST[$post]),ENT_QUOTES,"UTF-8"));
		
	}
	
	function get_long_ip() {
		if (getenv('HTTP_X_FORWARDED_FOR')){ 
			$ip=getenv('HTTP_X_FORWARDED_FOR');
		} else {
			$ip=getenv('REMOTE_ADDR');
		} 
		return $ip;
	}

	function log_err($err){
		global $log_message;
		array_push($log_message, $err);
	}
	
		
?>
