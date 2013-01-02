<?php

if ( !defined('IN_PLOT') )
{
	die("Hacking attempt");
}

global $parser;

// feed the parser

$O = "\[";
$C = "\]";
$tag = "popup";

$parser->add_tag("popup", "#{$O}({$tag}=(.*?))({$C}((?>{$O}(?!/?{$tag}[^{$O}]*?{$C})|[^{$O}]|(?R))*){$O})/{$tag}{$C}#ise", "xpopup('\\2','\\4')", "[popup=click here]some text[/popup], popup content tag.");


function xpopup($name,$content){
	$html .= "[html]";
	$html .= "<script type=\"text/javascript\">\r";
	$html .= "function popup(div_id){\r";
	$html .= "var el = document.getElementById(div_id);\r";
	$html .= "if (el.style.display == 'block') {el.style.display = 'none'} else {el.style.display = 'block'} ;\r";
	$html .= "}\r";
	$html .= "function popdown(div_id){\r";
	$html .= "var el = document.getElementById(div_id);\r";
	$html .= "el.style.display = 'none';\r";
	$html .= "}\r";
	$html .= "</script>\r";
	$html .= "[/html]";
	$html .= "<a href=\"#\" onmousedown=\"popup('popup".stripslashes($name)."')\">".stripslashes($name)."</a>";
	$html .= "<div class=\"popup\" id=\"popup".stripslashes($name)."\" style=\"display:none;\">";
	$html .= stripslashes($content);
	$html .= "</div>";
   
	return $html;
   
}
?>