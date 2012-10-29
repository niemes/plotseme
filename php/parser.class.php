<?php
	if ( !defined('IN_PLOT') )
	{
		die("Hacking attempt");
	}
	
	class parser {
		var $tag_name;  // bbcode tags array
		var $tag_search;
		var $tag_replace;
		var $tag_description;

		var $data;
		var $page_name;
		
		// Constructor
		function parser(){
			$this->tag_name = array();
			$this->tag_search = array();
			$this->tag_replace = array();
			$this->tag_description = array();
			
			$this->data = "";
			$this->page_name = $_GET['browse'];
		}
		
		function register_plugins(){
		
			$script_path = pathinfo($_SERVER["SCRIPT_FILENAME"]); // path to this script
			$script_dir = $script_path['dirname'];// parent directory of this script
			$plugins_dir = $script_dir.'/plugins/'; // path to plugins folder
			
			
			if ($handle = @opendir($plugins_dir)) { // @ pour eviter l'affichage d'erreur php
				while (($file = readdir($handle)) !== false) {
					if (is_file($plugins_dir.$file)){ // it's a file
						if (substr($file,0,1) != "." ) {	// show visible files only
							include_once("plugins/".$file);
						}
					}
				}
			} else {
				$message= 'Can\'t find Plugins path';
			}
		}
		
		// Add new tag (name, search string, replace string)
		function add_tag($name, $search, $replace, $description=null) {
			
			array_push($this->tag_name,$name);
			array_push($this->tag_search,$search);
			array_push($this->tag_replace,$replace);
			array_push($this->tag_description, $description);
		}
		
		function list_tags(){
			$tag_list = "";
			$i=0;
			foreach ($this->tag_name as $t){
				$i++;
				if ($this->tag_description[$i])
					$tag_list .= "<li>".$this->tag_name[$i]." : ".$this->tag_description[$i]."</li>";
			}
			return $tag_list;
		}
		
			
		// Parse text
		function parse($data) {
			global $user;
			global $page;
			global $count_hit;
			global $highlight;
			global $reserve;
			
			$reserve = array();
			
			$data = preg_replace_callback('#\[html\](.*?)\[/html\]#si','reserve_callback',$data);
			
			if ($page->editable){
				$data = preg_replace("(#EDIT)","<a href='javascript:void(0)' onclick='javascript:editFunction(\"".($page->name)."\")' target='_self'>Edition</a>",$data);
			} else {
				$data = preg_replace("(#EDIT)","Edition",$data);
			}
			
			$data = preg_replace("(#-)","<hr />",$data);
			
			$count = 1;
			
			if (PHP_VERSION>=5.0){
				//echo "php 5";
				while($count>0){
					$data = preg_replace($this->tag_search, $this->tag_replace, $data,-1, $count);
				}
			} else {
				$data = preg_replace($this->tag_search, $this->tag_replace, $data);
			}
			
			$data = preg_replace_callback('#\[html\](.*?)\[/html\]#si','reserve_callback',$data);					  
						
			$data = preg_replace("'[\n]'", "<br />\n",$data);
			
			$data = preg_replace_callback("/\{(.*?)}/","agregate_callback",$data); // agregated pages
			$data = preg_replace("#\[(('?[^\n^\['])*)\]#si", ("<a href=\"?browse=\\1\" title=\"\\1 &#x2192;\">\\1</a>"), $data); // Internal Link
			
			/* public functions */
			$data = preg_replace("(#VERSION)",$version,$data);
			$data = preg_replace("(#COUNTER)",$count_hit,$data);
			$data = preg_replace("(#CREATION_DATE)",$page->creation_date,$data);
			$data = preg_replace("(#MODIFICATION_DATE)",$page->modification_date,$data);
			$data = preg_replace("(#AUTHOR)",$page->author,$data);
			$data = preg_replace("(#CURRENT_TIME)",date("H:i:s A"),$data);
			$data = preg_replace("(#NO_TITLE)","",$data, -1 , $count);
			
			$data = preg_replace("(#TAGS)",$this->list_tags(),$data);

			if ($count>0) {
				$page->set_title(""); // hide page title
			}
			
			$data = preg_replace("(#TITLE)",$page->title,$data);
			
			$data = preg_replace("(#ADMIN)","<a href='javascript:void(0)' onclick='javascript:adminFunction()' target='_self'>Administration</a>",$data);
			$data = preg_replace("(#SEARCH)",get_search_form(),$data);
			
			$data = preg_replace("/(#RAND\(([0-9]*),([0-9]*)\))/e","rand((\\2),(\\3))",$data);
			
			if ($user->user_ok == true){
				$data = preg_replace("(#LOGIN)","<a href=\"?logout=$page->title\" title=\"Logout\">Logout</a>",$data);
			} else {
				$data = preg_replace("(#LOGIN)","<a href=\"?login=$page->title\" title=\"Login\">Login</a>",$data);
			}
			
			$data = preg_replace_callback('(%RESERVE%)','reserve_back_callback',$data);
			
			return $data;

		}
	}	
?>