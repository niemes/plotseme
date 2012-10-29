<?php

if ( !defined('IN_PLOT') )
{
	die("Hacking attempt");
}

global $parser;

// feed the parser

$O = "\[";
$C = "\]";
$tag = "object";
$param1 = "width";
$param2 = "height";


$parser->add_tag("object", "#{$O}({$tag})({$C}((?>{$O}(?!/?{$tag}[^{$O}]*?{$C})|[^{$O}]|(?R))*){$O})/{$tag}{$C}#ise", "object('\\3')");
$parser->add_tag("object", "#{$O}({$tag} {$param1}=(.*?) {$param2}=(.*?))({$C}((?>{$O}(?!/?{$tag}[^{$O}]*?{$C})|[^{$O}]|(?R))*){$O})/{$tag}{$C}#ise", "object('\\5','\\2','\\3')");

//$parser->add_tag("object width= height=", "#\[object width=(.*?) height=(.*?)\](.*?)\[/object\]#sie","object('\\3','\\1','\\2')"); //[img=campiong.png]camping[/img]

function object($filename, $width=null, $height=null){
	//echo $filename." ".$width." ".$height;
	
	$sizeparam = (isset($width) && isset($height));
	
	if (function_exists('finfo_open')) {
	
		$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype
	
		$mimetype = @finfo_file($finfo, $filename);
		if ($mimetype == false) $mimetype= get_mime_type($filename);
				
		finfo_close($finfo);
	} else if (function_exists('mime_content_type')) {
	
		$mimetype = @mime_content_type($filename);
		if ($mimetype == false) $mimetype= get_mime_type($filename);
	}
	if (!isset($mimetype) || ($mimetype=="")) $mimetype = 'unknow/octet-stream';
	
	$imagearray= array('image/jpeg', 'image/png', 'image/gif');
	
	if (in_array($mimetype, $imagearray)){
		if (!$sizeparam) $html = sprintf("<img src=\"%s\" alt=\"%s\" title=\"%s\" />", $filename, $filename, $filename); // file is an image
		else $html = sprintf("<img src=\"%s\" alt=\"%s\" title=\"%s\" width=\"%s\" height=\"%s\" />", $filename, $filename, $filename, $width, $height); // file is an image with size param
	} else {
			if (!$sizeparam) $html = sprintf("<object data=\"%s\" type=\"%s\"><a href=\"%s\">%s</a></object>",$filename, $mimetype, $filename, $filename);
			else $html = sprintf("<object data=\"%s\" type=\"%s\" width=\"%s\" height=\"%s\"><a href=\"%s\">%s</a></object>",$filename, $mimetype, $width, $height, $filename, $filename);
	}
	
	
	return $html;
}


function get_mime_type($filename, $mimePath = '') { 
   $fileext = substr(strrchr($filename, '.'), 1); 
   if (empty($fileext)) return (false); 
   $regex = "/^([\w\+\-\.\/]+)\s+(\w+\s)*($fileext\s)/i"; 
   $lines = file("mime.types"); 
   foreach($lines as $line) { 
      if (substr($line, 0, 1) == '#') continue; // skip comments 
      $line = rtrim($line) . " "; 
      if (!preg_match($regex, $line, $matches)) continue; // no match to the extension 
      return ($matches[1]); 
   } 
   return (false); // no match at all 
} 


?>