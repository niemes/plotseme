<?php
	if ( !defined('IN_PLOT') )
	{
		die("Hacking attempt");
	}
	
	class file_browser {
		var $html;
		var $root_dir;
		var $browse_dir;
		var $files;
		var $dirs;
		var $page_name;
		
		// Constructor
		function file_browser(){
			$this->files = array(); //array of files
			$this->dirs = array(); //array of directories
			$this->root_dir = FILE_BROWSER_ROOT_PATH; //root path
			
			$this->html = '';
		}
		
		// Functions
		function set_root_dir($path){
			$this->root_dir = $path;
		}
		
		function set_browse_dir($path){
			$this->browse_dir = $path;
		}
		
		function set_page_name($name){
			$this->page_name = $name;
		}
		
		// Prevent bad people from trying to view directories up
		function path_up($path){
							
	 		if (substr_count($path, "/") == 0) {
    			return '/';
 			} else {
     			return substr($path, 0, strrpos(substr($path, 0, -1), "/")).'/';
 			}

		}
		
		// get file size
		function file_size($size) { 
			if ($size > 1099511627800) {
				return $re_sized = sprintf("%01.2f", $size / 1099511627800) . " Gb"; 
			} elseif ($size > 1048576) { // literal.float 
				return $re_sized = sprintf("%01.2f", $size / 1048576) . " Mb"; 
			} elseif ($size > 1024) { 
				return $re_sized = sprintf("%01.2f", $size / 1024) . " Kb"; 
			} else { 
				return $re_sized = $size . " bytes"; 
			} 
		} 
		
		// get file count
		function file_count($dir_path) { 
			$file_count = 0; 
			if ($directory_handle = opendir($dir_path)){ // dir exist
				while (($file = readdir($directory_handle)) !== false) {
					if (substr($file,0,1) != "." ){	// show visible files only
						$file_count++;
					}
				}
				
				closedir($directory_handle);
			} else {
				log_err("file browser error");
			}
			return $file_count;
		} 
		
		function create_dir($path,$dir_name){
			$long_path = realpath($this->root_dir."/".$path).'/'; // build absolute path
			
			// avoid ../ in path !!
			if ($long_path !== ($this->root_dir."/".$path)) {
				
				log_err("file browser error; .. in path ".$this->root_dir.$path);
				echo("file browser error; .. in path ".$this->root_dir."/".$path." not ".$long_path);
				
				return "file browser error; .. in path";
			}
			
			$no_err = @mkdir($long_path.basename($dir_name), 0755);
			
			if ($no_err == true) {
				log_err("directory created");
				echo("directory created");
			} else {
				log_err("failed to create directory");
				echo("failed to create directory");
				
			}
		}
		
		function delete_dir($path,$dir_name){
			$good_path = $path."/";
			$long_path = realpath($this->root_dir.$path).'/'; // build absolute path
			
			// avoid ../ in path !!
			if ($long_path !== ($this->root_dir.$good_path)) {
				log_err("file browser error; .. in path ".$this->root_dir.$good_path);
				return "file browser error; .. in path";
			}
			
			$no_err = @rmdir($long_path.basename($dir_name));
			
			if ($no_err == true) {
				log_err("directory deleted");
			}
			else {
				log_err("failed to delete directory");
			}
			
		}
		
		function delete_file($path,$file_name) {
			
			
			$long_path = realpath($this->root_dir.'/'.$path); // build absolute path
			
			
			//if (is_dir($long_path.basename($file_name))){
			//	$no_err = rmdir($long_path.basename($file_name));
			//} else {
				
			 	@chmod($long_path.'/'.basename($file_name), 0666);
				$no_err = unlink ($long_path.'/'.basename($file_name));
			//}
		
			if ($no_err == true) {
				log_err($file_name." file deleted");
				echo 'delete '. $long_path.'/'.basename($file_name);
			} else {
				log_err($file_name." failed to delete file");
				echo 'failed to delete '. $long_path.'/'.basename($file_name);
			}
			
		}
		
			
		function browse_dir($path,$sort_by = "name"){
			
			if (substr($path,0,1)=='/') $path = substr($path,1); // remove leading /
			
			$path_up = $this->path_up($path); // get path up
			$long_path = realpath($this->root_dir.'/'.$path); // build absolute path

			if ($directory_handle = opendir($long_path)){ // dir exist
				while (($file = readdir($directory_handle)) !== false) {
					if (substr($file,0,1) != "." ){	// show visible files only
						
						if (is_file($long_path.'/'.$file)){ // it's a file
							if ($sort_by == "name"){
								$key = $file;
							} elseif ($sort_by == "date"){
								$key = filemtime($long_path.'/'.$file).$file;
							} elseif ($sort_by == "size"){
								$key = filesize($long_path.'/'.$file).$file;
							}
							$this->files[$key] = array(
													   "type" => "file",
													   "name" => $file,
													   "path" => $path,
													   "size" => $this->file_size(filesize($long_path.'/'.$file)),
													   "date" => filemtime($long_path.'/'.$file)
													   );
													   
						} elseif (is_dir($long_path.'/'.$file)){ // it's a dir
							if ($sort_by == "name"){
								$key = $file;
							} elseif ($sort_by == "date"){
								$key = filemtime($long_path.'/'.$file).$file;
							} elseif ($sort_by == "size"){
								$key = $file_count.$file;
							}
							$file_count = $this->file_count($long_path.'/'.$file);
							
							if ($file_count>1){
								$s = 's';
							}
							
							$this->files[$key] = array(
													   "type" => "directory",
													   "name" => $file,
													   "path" => $path,
													   "size" => "&lt;".$file_count." file".$s."&gt;",
													   "date" => filemtime($long_path.'/'.$file)
													   );
						}
					}
				}
				
				closedir($directory_handle);
			} else {
				return("file browser error");
			}
			
			// Sort the array according to indexes
			ksort($this->files);
			
			// generate html
			//$this->html = '<form method="post" id="file_browser_form"><table id="fb" width="100%"><tr><td colspan="4">files';
			$this->html = '<table id="fb" width="100%"><tr><td colspan="4">';
			$this->html .='current directory is: '.$path;
			$this->html .='path up is: '.$path_up;
			$this->html .='</td></tr><tr bgcolor="#999999"><th>Name<a href="javascript:fileBrowseFunction(\''.
			($path).'\',\'name\')">#</a></th><th>Size<a href="javascript:fileBrowseFunction(\''.
			($path).'\',\'size\')">#</a></th><th>Date<a href="javascript:fileBrowseFunction(\''.
			($path).'\',\'date\')">#</a></th><th></th></tr>';
			
			// path up
			$this->html .= '<tr bgcolor="#BBBBBB"><td align="left"><b><a href="javascript:fileBrowseFunction(\''.
			($path_up).'\',\''.$sort_by.'\')">[..]</a></b></td><td></td><td></td><td></td></tr>';
			
			
			foreach ($this->files as $key => $value) { // build directory files table
				
				if ($value["type"] == "directory"){
					$this->html .= '<tr bgcolor="#CCCCCC">';
					$this->html .= '<td align="left"><b><a href="javascript:fileBrowseFunction(\''.($path.$value["name"]).'/\',\''.$sort_by.'\');">['.$value["name"].']</a></b></td>'; // bold for directories
					$this->html .= '<td width="80" align="right">'.$value["size"].'</td>';
				} else {
					$this->html .= '<tr bgcolor="#DDDDDD">';
					$this->html .= '<td align="left"><a href="'.(FILES_FOLDER_NAME.'/'.$path.$value["name"]).'" target = "blank">'.$value["name"].'</a> ('.FILES_FOLDER_NAME.'/'.$path.$value["name"].')</td>'; // a file
					$this->html .= '<td width="80" align="right">'.$value["size"].'</td>';
				}
				
				$this->html .='<td width="120" align="right">'.date('m-d-y H:m:s',$value["date"]).'</td>';
				$this->html .='<td width="40" align="right"><input type="submit" value="–" onmouseup="javascript:fileDeleteFunction(\''.$path.'\',\''.addslashes($value["name"]).'\',\''.$sort_by.'\');" title="Delete file"></td></tr>';
			}
			
			$this->html .='<tr><td colspan="4"></td></tr></table>';
			
	
			require_once("AjaxFileUploader.inc.php");
			$ajaxFileUploader = new AjaxFileuploader($uploadDirectory="../".FILES_FOLDER_NAME);	
			$html = $ajaxFileUploader->showFileUploader('id1');
			
			$html .= "<div id=\"process_upload_animation\"></div>\r";
			
			$this->html .= $html;

			//$this->html .='<div><form id="new_folder_form" method="post" action="'.PAGE_INDEX.'#fb" title="New Folder">
			$this->html .= 'Nouveau dossier: <input type="text" id="new_folder_name" size="20" />
			<input type="submit" id="new_folder_submit" value="Ok" onmouseup="var newFolderName = document.getElementById(\'new_folder_name\').value; 
			javascript:fileNewFolderFunction(\''.$path.'\',newFolderName,\''.$sort_by.'\');">
			</div>';
			
			return $this->html;
		}
	}
?>