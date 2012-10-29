<?php
	if ( !defined('IN_PLOT') )
	{
		die("Hacking attempt");
	}
	
	
	class page {
		var $title;
		var $text;
		var $name;
		var $creation_date;
		var $modification_date;
		var $author;
		var $keywords= array();
		var $ip;
		var $editable;
		var $template;
		
		// Constructor
		function page($page_name){
		
			// set page name
			$this->set_name($page_name); 
			$this->set_title($page_name); 
			// get page content from db
			if ($page_name){
				$content = page_get_content($page_name);
			 
				$this->set_text(htmlentities($content['text'], ENT_QUOTES, "UTF-8"));
				$this->set_author(htmlentities($content['author'], ENT_QUOTES, "UTF-8"));
				$this->set_creation_date($content['creation_date']);
				$this->set_modification_date($content['modification_date']);
				$this->set_keywords(htmlentities($content['keywords'], ENT_QUOTES, "UTF-8"));
				$this->set_ip($content['ip']);
				$this->set_template(htmlentities($content['template'], ENT_QUOTES, "UTF-8"));
				
				$this->set_editable(true);
			} else {
				$this->set_text('404');
				$this->set_author('');
				$this->set_creation_date('');
				$this->set_modification_date('');
				$this->set_keywords('');
				$this->set_ip('');
				$this->set_template('');
				
				$this->set_editable(false);
			}
		}
		
		
		
		function set_name($data){
			$this->name = $data;
		}
		
		function set_title($data){
			$this->title = $data;
		}
		
		function get_name($data){
			return stripslashes($this->name);
		}
		
		function set_text($data){
			$this->text = $data;
		}

		function set_editable($data){
			$this->editable = $data;
		}
		
		function set_creation_date($data){
			$this->creation_date = $data;
		}
		
		function set_modification_date($data){
			$this->modification_date = $data;
		}
		
		function set_author($data){
			$this->author = $data;
		}
		
		function set_keywords($data){
			$this->keywords = $data;
		}
		
		function set_ip($data){
			$this->ip = $data;
		}
		
		function set_template($data){
			$this->template = $data;
		}
	}
?>