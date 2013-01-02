<?php

if ( !defined('IN_PLOT') )
{
	die("Hacking attempt");
}

global $parser;

// feed the parser

$parser->add_tag("slideshow", "#\[slideshow\](.*?)\[/slideshow\]#sie", "slideshow('\\1')"); //image [img]http://www.somewere.com/image.png[/img]


function slideshow($path){
	//$file_array[] = array();
	
	if ($directory_handle = @opendir($path)){ // dir exist
		while (($file = readdir($directory_handle)) !== false) {
			if (is_file($path.$file)){
			if (substr($file,0,1) != "." ){	// show visible files only
				$file_count++;
				$file_array[] = $file;
			}
			}
		}
		closedir($directory_handle);
	} else {
		return "Slide show directory doesn't exist";
	}
	
	sort($file_array,SORT_NUMERIC);
	
	//return "count :".$file_count;
	//print_r($file_array);
	$html  ="[html]";
	
	$html .="<script type=\"text/javascript\">\r";
	$html .="var first;\r";
	$html .="if (first == null) {\r";
	$html .="var images = new Array();\r";
	$html .="function loaded(id){\r";
	$html .="window.status=\"Image is loaded\";\r";
	$html .="obj = document.getElementById(id);\r";
	$html .="objdescription = document.getElementById(id+'_description');\r";
	$html .="objdescription.innerHTML=images[id][obj.index].description;\r";
	$html .="}\r";
	$html .="function loading(id){\r";
	$html .="window.status=\"Image loading...\";\r";
	$html .="obj = document.getElementById(id+\"_description\");\r";
	$html .="obj.innerHTML=\"Loading...\"\r";
	$html .="}\r";
	$html .="function slideImage(src, alt, description, author, credit) {\r";
	$html .="this.src = src;\r";
	$html .="this.alt = alt;\r";
	$html .="this.description = description;\r";
	$html .="this.author = author;\r";
	$html .="this.credit = credit;\r";
	$html .="}\r";
	$html .="var first = 1;\r";
	$html .="function slide_next(id) {\r";
	$html .="loading(id)\r";
	$html .="obj = document.getElementById(id);\r";
	$html .="obj.index+=1;\r";
	$html .="if (obj.index > (obj.length_diapo-1)) obj.index=0;\r";
	$html .="obj.src = images[id][obj.index].src;\r";
	$html .="obj.alt = images[id][obj.index].alt;\r";
	$html .="obj.decription = images[id][obj.index].description;\r";
	$html .="obj.author = images[id][obj.index].author;\r";
	$html .="obj.credit = images[id][obj.index].credit;\r";
	//$html .="objdescription = document.getElementById(id+'_description');\r";
	//$html .="objdescription.innerHTML=images[id][obj.index].description;\r";
	$html .="objcredit = document.getElementById(id+'_credit');\r";
	$html .="objcredit.innerHTML=images[id][obj.index].credit;\r";
	$html .="objauthor = document.getElementById(id+'_author');\r";
	$html .="objauthor.innerHTML=images[id][obj.index].author;\r";
	$html .="objcounter = document.getElementById(id+'_counter');\r";
	$html .="objcounter.innerHTML= obj.index+1 + '/' + obj.length_diapo;\r";
	$html .="}\r";
	$html .="function slide_prev(id) {\r";
	$html .="loading(id)\r";
	$html .="obj = document.getElementById(id);\r";
	$html .="obj.index-=1;\r";
	$html .="if (obj.index < 0 ) obj.index=obj.length_diapo-1;\r";
	$html .="obj.src = images[id][obj.index].src;\r";
	$html .="obj.alt = images[id][obj.index].alt;\r";
	$html .="obj.decription = images[id][obj.index].description;\r";
	$html .="obj.author = images[id][obj.index].author;\r";
	$html .="obj.credit = images[id][obj.index].credit;\r";
	//$html .="objdescription = document.getElementById(id+'_description');\r";
	//$html .="objdescription.innerHTML=images[id][obj.index].description;\r";
	$html .="objcredit = document.getElementById(id+'_credit');\r";
	$html .="objcredit.innerHTML=images[id][obj.index].credit;\r";
	$html .="objauthor = document.getElementById(id+'_author');\r";
	$html .="objauthor.innerHTML=images[id][obj.index].author;\r";
	$html .="objcounter = document.getElementById(id+'_counter');\r";
	$html .="objcounter.innerHTML=  obj.index+1 + '/' + obj.length_diapo;\r";
	$html .="}\r";
	$html .="function slide_refresh(id) {\r";
	$html .="loading(id)\r";
	$html .="obj = document.getElementById(id);\r";
	$html .="obj.src = images[id][obj.index].src;\r";
	$html .="obj.alt = images[id][obj.index].alt;\r";
	$html .="obj.decription = images[id][obj.index].description;\r";
	$html .="obj.author = images[id][obj.index].author;\r";
	$html .="obj.credit = images[id][obj.index].credit;\r";
	//$html .="objdescription = document.getElementById(id+'_description');\r";
	//$html .="objdescription.innerHTML=images[id][obj.index].description;\r";
	$html .="objcredit = document.getElementById(id+'_credit');\r";
	$html .="objcredit.innerHTML=images[id][obj.index].credit;\r";
	$html .="objauthor = document.getElementById(id+'_author');\r";
	$html .="objauthor.innerHTML=images[id][obj.index].author;\r";
	$html .="objcounter = document.getElementById(id+'_counter');\r";
	$html .="objcounter.innerHTML= obj.index+1 + '/' + obj.length_diapo;\r";
	$html .="}\r";
	$html .="}\r";
	$html .="images['".$path."']=new Array();\r";
	
	for ($i=0;$i<sizeof($file_array);$i++){
		$html .="images['".$path."'][$i]=new slideImage();\r";
		$size = getimagesize ($path.$file_array[$i], $info);
		$iptc = iptcparse($info["APP13"]);
		
		$description = '';
		$author = '';
		$credit = '';
		
		if ($iptc==false){ // no iptc in that file
		
			$html .="images['".$path."'][$i].description=\"\";\r";
			$html .="images['".$path."'][$i].author=\"\";\r";
			$html .="images['".$path."'][$i].credit=\"\";\r";
			
			$html .="images['".$path."'][$i].src=\"".$path.$file_array[$i]."\";\r";
			$html .="images['".$path."'][$i].alt=\"".$path.$file_array[$i]."\";\r";
		} else {
			foreach($iptc as $key => $value)
			{
				if ($key=='2#120'){ // descrition
					
					foreach($value as $innerkey => $innervalue)
					{
						if( ($innerkey+1) != count($value) )
							$description = iconv('macintosh', 'UTF-8', $innervalue);
						else
							$description .= iconv('macintosh', 'UTF-8', $innervalue);
					}
				}
				
				if ($key=='2#080'){ // author
				
					foreach($value as $innerkey => $innervalue)
					{
						if( ($innerkey+1) != count($value) )
							$author = iconv('macintosh', 'UTF-8', $innervalue);
						else
							$author .= iconv('macintosh', 'UTF-8', $innervalue);
					}
					
				}
				
				if ($key=='2#110'){ // credit
				
					foreach($value as $innerkey => $innervalue)
					{
						if( ($innerkey+1) != count($value) )
							$credit = iconv('macintosh', 'UTF-8', $innervalue);
						else
							$credit .= iconv('macintosh', 'UTF-8', $innervalue);
					}
				}
			}
			//echo mb_detect_encoding($description);
			
			$html .="images['".$path."'][$i].description=\"".$description."\";\r";
			$html .="images['".$path."'][$i].author=\"".$author."\";\r";
			$html .="images['".$path."'][$i].credit=\"".$credit."\";\r";
			
			$html .="images['".$path."'][$i].src=\"".$path.$file_array[$i]."\";\r";
			$html .="images['".$path."'][$i].alt=\"".$path.$file_array[$i]."\";\r";
		}
	}
	
	//set first image value;
	
	
	
	$html .= "</script>\r";
	$html .= "[/html]";
	
	$html .= "<div class=\"slideshow\">\r"; // slide show div
	$html .= "<div class=\"slideshow_header\">\r";// slide show header
	$html .= "<span class=\"slideshow_control\">\r";// slide show control
	$html .= "<a href=\"javascript:slide_prev('".$path."')\"><<</a>\r";
	$html .= "<span id =\"".$path."_counter\">0/0</span>\r";
	$html .= "<a href=\"javascript:slide_next('".$path."')\">>></a>\r";
	$html .= "</span>\r";// end slide show control
	$html .= "<span class=\"slideshow_description\" id=\"".$path."_description\"></span>\r";
	$html .= "</div>\r";// end slide show header
	$html .= "<a href=\"javascript:slide_next('".$path."')\"><img class=\"slideshow_image\" src=\"".$path.$file_array[0]."\" id='".$path."'  name='".$path."' onload=\"loaded('".$path."')\" /></a>\r";
	$html .= "<div class=\"slideshow_credit\" id=\"".$path."_credit\"></div>\r";
	$html .= "<div class=\"slideshow_author'\" id=\"".$path."_author\"></div>\r";
	$html .= "</div>\r"; // end slide show div
	$html .= "<script type=\"text/javascript\">\r";
	$html .= "obj = document.getElementById('".$path."');\r";
	$html .= "obj.index=0;\r";
	$html .= "obj.length_diapo=images['".$path."'].length;\r";
	$html .= "slide_refresh('".$path."');\r";
	$html .= "</script>\r";
	
	
	return $html;
}
?>