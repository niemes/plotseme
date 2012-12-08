<?php
	define('IN_PLOT', true); // to avoid inc hack
		
	// globals definitions
	global $user;
	global $parser;
	global $page;
	global $count_hit;
	global $ajax;
	global $page_name;
	
	// get includes
	require_once('php/config.inc.php');
	require_once('php/plotseme.inc.php');
	require_once('php/db.inc.php');
	
	// get classes
	require_once('php/user.class.php');
	require_once('php/page.class.php');
	require_once('php/parser.class.php');
	
	$count_hit=count_hit();
	
	// set user session from cookies
	
	// new parser class
	$parser = new parser();
	$parser->register_plugins();
	
	// log user
	log_err( 'user:'.get_request('user').', pass:'.get_request('password'));
		
	$ajax =isset($_REQUEST['ajax']); // is it an ajax request ?
	log_err('ajax request:'.$ajax);
	
	
	// ------------------ LOGIN ------------------
	if (isset($_REQUEST['user']) && isset($_REQUEST['password'])){ // log in user
		
		sleep(2);// slow down hackers
		
		$user = new user(get_request('user'),md5(get_request('password')));
		
		// set the cookies
		setcookie('plotseme[u]', get_request('user'), time()+3600);  /* user name cookie, expire in 1 hour */
		setcookie('plotseme[p]', md5(get_request('password')), time()+3600);  /* user pass (ashed), expire in 1 hour */
		
	} else {
		
		$user = new user($_COOKIE['plotseme']['u'],$_COOKIE['plotseme']['p']);
		
		// re-set the cookies
		setcookie('plotseme[u]', $_COOKIE['plotseme']['u'], time()+3600);  /* user name cookie, expire in 1 hour */
		setcookie('plotseme[p]', $_COOKIE['plotseme']['p'], time()+3600);  /* user pass (ashed), expire in 1 hour */
	
	}
	
	
	
	// ------------------ ACTION REQUEST ------------------
	if (isset($_GET['exist'])) { // is the file uploaded ?
		if ($user->user_privilege>1){ // ok to browse

			$file = basename(get_request('exist'));
			
			include_once('php/file_browser.class.php');
	
			$path = get_request('path');
			
			$file_browser = new file_browser();
			
			if ($file_browser->file_exist($path,$file)==true){
				echo 'ok';
			} else {
				echo 'nope';
			}
		} else {
			echo 'file upload access denied';
		}
		die();
	}

	// delete a file
	if (isset($_REQUEST['file_delete'])){ // delete a file
		if ($user->user_privilege>1){ // ok to browse
			
			include_once('php/file_browser.class.php');
			
			$file_browser_dir = get_request('file_browse');
			$file_name = get_request('file_delete');
			$file_browser = new file_browser();
			$file_browser->delete_file($file_browser_dir, $file_name);
			
			echo $file_browser->browse_dir($file_browser_dir,$_REQUEST['sort_by']);
			
			die();
		} else {
			echo 'file delete access denied';
		}
	}
	
	// new folder
	if (isset($_REQUEST['file_new_folder'])){ // create a new folder
		if ($user->user_privilege>1){ // ok to create new folder
			
			include_once('php/file_browser.class.php');
			
			$file_browser_dir = get_request('file_browse');
			$dir_name = get_request('file_new_folder');
			$file_browser = new file_browser();
			$file_browser->create_dir($file_browser_dir, $dir_name);
			
			echo $file_browser->browse_dir($file_browser_dir,$_REQUEST['sort_by']);
			
			die();
		} else {
			echo 'file create folder access denied';
		}
	}
	
	
	// new file_browser
	if (isset($_REQUEST['file_browse'])){ // browse file
		if ($user->user_privilege>1){ // ok to browse
		
			include_once('php/file_browser.class.php');
	
			$file_browser_dir = get_request('file_browse');
			$file_browser = new file_browser();
			
			echo $file_browser->browse_dir($file_browser_dir,$_REQUEST['sort_by']);
			
			die();
		} else {
			echo 'file browse access denied';
		}
	}
	
	if (isset($_REQUEST['upload'])) { //  upload the user file
		if ($user->user_privilege>1){ // ok to browse
		
			$destination_path = getcwd().DIRECTORY_SEPARATOR;

			$result = 0;
   
			$target_path = $destination_path . "files/".basename( $_FILES['myfile']['name']);
			log_err($target_path);
			
			if(move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) {
				$result = 1;
			}
   
			sleep(1);
	   
	   
			//include_once('php/file_browser.inc.php');
	
			log_err("uploading...");
			//$source_file=$_FILES['user_file']['tmp_name'];
			//$file_name = $_FILES['user_file'][name];
			//$file_browser->upload_file($source_file,$path,$file_name);
			
			//die();
		} else {
			die('file upload access denied');
		}
	}
	
	if (isset($_REQUEST['save'])){ // save post
	
		log_err('save request: '.$_REQUEST['save']);
		
		$page_name = html_entity_decode(get_request('save'),ENT_QUOTES,"UTF-8");
		
		log_err('save name: '.$page_name);

		if ($user->user_privilege>1){ // ok to save
			$content = get_request('content');
			$author = get_request('author');
			$keywords = get_request('keywords');
			$template = get_request('template');
			
			page_set_content($page_name,$content,$author,$keywords,$template);
			echo("saved"); // echo save statut
		} else {
			log_err('Save : acces denied');
			echo("error saving !");
		}
		
		return;
		
	} else if (isset($_REQUEST['saveandclose'])){ // save post and close edition field
		log_err('save request: '.$_REQUEST['saveandclose']);
		
		$page_name = html_entity_decode(get_request('saveandclose'),ENT_QUOTES,"UTF-8");
		
		log_err('save name: '.$page_name);

		if ($user->user_privilege>1){ // ok to save
			$content = get_request('content');
			$author = get_request('author');
			$keywords = get_request('keywords');
			$template = get_request('template');
			
			page_set_content($page_name,$content,$author,$keywords,$template);
		} else {
			log_err('Save : acces denied');
		}
		
		$page = new page($page_name);
		
	} elseif (isset($_GET['browse'])){ // browse get
		
		$page_name = get_request('browse');
		log_err("browse name:".$page_name);
		
		$page = new page($page_name);	
		
	} elseif (isset($_GET['edit'])){ // edit get
    
      
		$page_name = get_request('edit');
		$page = new page($page_name);
		
        log_err("edit request:".$page_name);

		if ($user->user_privilege>1){ // ok to edit
			
			$edit = true;

			// new file_browser
			include_once('php/file_browser.class.php');
			$file_browser = new file_browser();
			$file_browser_content = $file_browser->browse_dir($file_browser_dir,"name");
			
			log_err('Edit: access ok');
			
		} else { // go to login page
			//$edit = false;
			
			log_err('Edit: access denied');
			log_err('privilege = '.$user->user_privilege);
			
            
            $edit = false;
            unset($edit);
			$login = true;
			
		}
		
	} elseif (isset($_REQUEST['admin'])){ // admin page
		$page_name = get_request('admin');
		$page = new page($page_name);
		
		if ($user->user_privilege>1){ // ok to admin
			
			$admin = true;
			
			if (isset($_REQUEST['new_user'])){ // create user
				log_err('Admin: new user creation');
				$user->user_create($_REQUEST['user_login'], $_REQUEST['user_pass'], $_REQUEST['user_name'], $_REQUEST['user_description'], $_REQUEST['user_email'], $_REQUEST['user_privilege']);
				
			}
			
			if (isset($_REQUEST['del_user'])){ // del user
				log_err('Admin: delete users: '.$_REQUEST['user_id']);
				$user->user_delete(split(',',$_REQUEST['user_id']));
				
			}
			
			if (isset($_REQUEST['dump_db'])){ // dump database
				log_err('Admin: dump database: ');
				db_dump();
				
			}

		} else {
			$admin=false;
			$log_to_admin = true;
			
			log_err('Admin: access denied');
			log_err('privilege = '.$user->user_privilege);
			
			$login = true;
			
		}
		
	} elseif (isset($_GET['search'])){ // search request
		$page_name = get_request('search');
		
		$page = new page($page_name);
		$search = true;
		
	} elseif (isset($_GET['login'])){ // search request
		$log_to_browse == true;
		
		$page_name = get_request('login');
		$page = new page($page_name);
		$login = true;
		
	} elseif (isset($_REQUEST['logout'])){ //
		$user = new user('','');
		// un-set the cookies
		setcookie('plotseme[u]', '', time());  /* user name cookie, expire nowr */
		setcookie('plotseme[p]', '', time());  /* user pass (ashed), expire now */
		
		$page_name = get_request('logout');
		log_err("log out to :".$page_name);
		
		$page = new page($page_name);	
		

	} else { // catch all default -> browse?index
		$page_name = SITE_INDEX;
		$page = new page($page_name);	
	}
	
	if (!isset($edit)){
        log_err("edit not set");

		if ($admin==true){
			log_err('Admin: get admin form');
			
			$page->set_editable(false);
			$content =	"<div id='content'>\n".get_admin_form($page_name)."</div>\n";
			$page->title = "Administration of ".SITE_TITLE;

		} elseif ($login==true){
			log_err('Edit: get login form');
			
			$page->set_editable(false);
			$content =	"<div id='content'>\n".get_login_form($page_name,$log_to_admin)."</div>\n";
			$page->title = "Login to ".SITE_TITLE;
		
		} elseif ($search==true){
			log_err('Search: get search result');
			$page->text = get_search_result($_GET['search']);
			$content = "<div id='content'>\n".($parser->parse($page->text))."</div>";
			$page->title = "Search ".$_GET['search'];
			
		} else {
			
			if (!$page->text){
				$page->set_text("Empty page: " . ($page_name)."\n");
			}
			
			$content ="<div id='content'>\n".($parser->parse($page->text))."</div>\n";
		}
		
		
	} else {
		$page->set_editable(false);
		
		$content ="<div id='content'>
		<form id='edit_form' method='post'>
		<input id='name' type='hidden' value=".urlencode($page->name)." />
		<input type='button' onmouseup='saveFunction();' value='save' />
		<input type='button' onmouseup='saveAndCloseFunction();' value='save and close' />
		<input type='reset' value='reset' />
		<input type='button' onmouseup='cancelFunction();' value='cancel' />
		<p>Attributs <input type='text' size='100' id='keywords_field' value='$page->keywords' /></p>
		<p>".get_keywords()."</p>
		<p>Template <input type='text' size='50' id='template_field' value='$page->template' /></p>
		<p>
		<input type='button' onmouseup='insertTag(\"[\",\"]\");' value='Link' />
		<input type='button' onmouseup='insertTag(\"[alias=\",\"]alias[/alias]\");' value='Alias' />
		<input type='button' onmouseup='insertTag(\"[url=\",\"]link[/url]\");' value='URL' />
		<input type='button' onmouseup='insertTag(\"[img]\",\"[/img]\");' value='Img' />
		&nbsp;&nbsp;
		<b><input type='button' onmouseup='insertTag(\"[b]\",\"[/b]\");' value='B' /></b>
		<i><input type='button' onmouseup='insertTag(\"[i]\",\"[/i]\");' value='I' /></i>
		<u><input type='button' onmouseup='insertTag(\"[u]\",\"[/u]\");' value='U' /></u>
		<strike><input type='button' onmouseup='insertTag(\"[strike]\",\"[/strike]\");' value='S'></strike>
		<input type='button' onmouseup='insertTag(\"[blink]\",\"[/blink]\");' value='Blink' />
		&nbsp;&nbsp;
		<input type='button' onmouseup='insertTag(\"[left]\",\"[/left]\");' value='Left' />
		<input type='button' onmouseup='insertTag(\"[center]\",\"[/center]\");' value='Center' />
		<input type='button' onmouseup='insertTag(\"[right]\",\"[/right]\");' value='Right' />
		&nbsp;&nbsp;
		<input type='button' style='color:red' onmouseup='insertTag(\"[color=red]\",\"[/color]\");' value='Red' />
		<input type='button' style='color:green' onmouseup='insertTag(\"[color=green]\",\"[/color]\");' value='Green' />
		<input type='button' style='color:blue' onmouseup='insertTag(\"[color=blue]\",\"[/color]\");' value='Blue' />
		<input type='button' style='color:yellow' onmouseup='insertTag(\"[color=yellow]\",\"[/color]\");' value='Yellow' />
		</p>
		<p>
		<div id='save_statut'>saved</div>
		<textarea id='edit_field' rows='40' cols='80' onkeypress='document.getElementById(\"save_statut\").innerHTML =\"unsaved\";'>$page->text</textarea>
		<p/>
		<p>Auteur <input type='text' id='author_field' value='$page->author' /></p>
		<input type='button' onmouseup='saveFunction();' value='save' />
		<input type='button' onmouseup='saveAndCloseFunction();' value='save and close' />
		<input type='reset' value='reset'>
		<input type='button' onmouseup='cancelFunction();' value='cancel' />
		</form>
		<div id='file_browser'>".$file_browser_content."</div>
		</div>";
	}
	
	
	$template = get_template($page->template);
	if (($template == null) ||($template =="")) {
		$template = get_template(DEFAULT_TEMPLATE);
		log_err("no template, use :".DEFAULT_TEMPLATE);
	} else log_err('template :' .$page->template);

		
	$html = preg_replace("/\[(('?[^\n^\['])*)\]/e", "page_get_text('\\1')", $template); // parses skeleton
	$html = $parser->parse($html);
	
	if (strpos($html,"#CONTENT")==false){
		$html.=$content;
	} else {
		$html = preg_replace("(#CONTENT)",$content, $html); // pares skeleton
	}
	
	$css = page_get_text('.css');
	
	if ($ajax==true){
		header("Content-type:text/html; charset=utf-8"); // we need this for safari XMLHTTPRequest bug. 
		echo $html;
		if (DEBUG) {print_r($_FILES);echo '<br />'; print_r($log_message);}
		die();
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='fr'>
<head>
<meta http-equiv='content-type' content='text/html; charset=utf-8' />
<meta name="keywords" content="<?php echo SITE_KEYWORDS;?>" />
<meta name="description" content="<?php echo SITE_DESCRIPTION;?>" />
<meta name="viewport" content="width = 480" />
<link rel="stylesheet" href="php/css.php" />
<script type="text/javascript" src="js/plotseme.js" ></script>
<script type="text/javascript" src="js/uploader.js"></script>
<title>
<?php
	echo SITE_TITLE." | ".$page->name;
?>
</title>
</head><body>
<?php
	echo $html;
	if (DEBUG) {
		echo("<p>files :");
		print_r($_FILES);
		echo '<br />logs :';
		print_r($log_message);
		echo("</p>");
	}
?>
</body></html>