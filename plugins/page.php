<?php

if ( !defined('IN_PLOT') )
{
	die("Hacking attempt");
}

global $parser;
// feed the parser

$O = "\[";
$C = "\]";
$tag = "page";

$parser->add_tag("page", "#{$O}({$tag})({$C}((?>{$O}(?!/?{$tag}[^{$O}]*?{$C})|[^{$O}]|(?R))*){$O})/{$tag}{$C}#ise", "page('\\3')", "[page]a page name[/page], embed a page in a page tag.");

function page($thepage){
	global $pagelist;
	global $page_name;
	
	if(!isset($pagelist)) $pagelist = array("first");

	$O = "\[";
	$C = "\]";
	$tag = "page";

	$html = page_get_text($thepage);
	//print_r($pagelist);
	
	if (in_array($thepage, $pagelist)) return "<div class='page_include'>overflow in page ".$thepage." from ".$page_name."</div>";
	
	array_push($pagelist,$thepage);
	return  "<div class='page_include'>".$html."</div>";
	
}
?>