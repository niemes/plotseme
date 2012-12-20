<?php

if ( !defined('IN_PLOT') )
{
	die("Hacking attempt");
}

global $parser;

// feed the parser


$O = "\[";
$C = "\]";
$tag1 = "button";
$tag2 = "button alias";

$parser->add_tag("button alias", "#{$O}({$tag2}=(.*?))({$C}((?>{$O}(?!/?{$tag1}[^{$O}]*?{$C})|[^{$O}]|(?R))*){$O})/{$tag1}{$C}#ise", "xbutton_alias('\\2','\\4')","[button alias=link]alias[/button]");
$parser->add_tag("button", "#{$O}({$tag1})({$C}((?>{$O}(?!/?{$tag1}[^{$O}]*?{$C})|[^{$O}]|(?R))*){$O})/{$tag1}{$C}#ise", "xbutton('\\3')", "[button]link[/button]");

function xbutton($name){
	
	global $page;
	$encoded_name = htmlentities($name,ENT_QUOTES,"UTF-8");
	//echo($name);
	//echo($decoded_name);
	//echo($page->title);
	//echo("---------");
	if (strtolower($encoded_name)==strtolower($page->title)){ // selected
		return "<a href=\"?browse=$name\" title=\"$name &#x2192;\" class=\"selected\">$name</a>";
	} else {
		//echo $page->keywords."\r";
		if ($page->keywords==""){ // not selected
			return"<a href=\"?browse=$name\" title=\"$name &#x2192;\" class=\"unselected\">$name</a>";
		}
		
		$array = preg_split("/[,]+/",($page->keywords)); // get keywords
		//print_r($array);
		//echo ' '.$name.' , ';
		array_walk($array, 'trim_value');
		if (in_array(strtolower($encoded_name),$array)){
			return "<a href=\"?browse=$name\" title=\"$name &#x2192;\" class=\"selected\">$name</a>";
		} else {
			return"<a href=\"?browse=$name\" title=\"$name &#x2192;\" class=\"unselected\">$name</a>";
		}
	}
}

function xbutton_alias($name, $alias){

	//echo " - name : ".$name. "   alias:".$alias;
	global $page;
	$encoded_name = htmlentities($name,ENT_QUOTES,"UTF-8");
	
	if (strtolower($encoded_name)==strtolower($page->title)){ // selected
		return "<a href=\"?browse=$name\" title=\"$name &#x2192;\" class=\"selected\">$alias</a>";
	} else {
		//echo $page->keywords."\r";
		if ($page->keywords==""){ // not selected
			return"<a href=\"?browse=$name\" title=\"$name &#x2192;\" class=\"unselected\">$alias</a>";
		}
		
		$array = preg_split("/[,]+/",($page->keywords)); // get keywords
		//print_r($array);
		//echo ' '.$name.' , ';
		array_walk($array, 'trim_value');
		if (in_array(strtolower($encoded_name),$array)){
			return "<a href=\"?browse=$name\" title=\"$name &#x2192;\" class=\"selected\">$alias</a>";
		} else {
			return"<a href=\"?browse=$name\" title=\"$name &#x2192;\" class=\"unselected\">$alias</a>";
		}
	}
}
?>