<?php

if ( !defined('IN_PLOT') )
{
	die("Hacking attempt");
}

global $parser;
// feed the parser

$O = "\[";
$C = "\]";
$tag = "tweet";

$parser->add_tag("tweet", "#\[tweet\](.*?)\[/tweet\]#sie", "tweet('\\1')", "[tweet]message[/tweet], tweet this."); //tweet button


function tweet($message){

	$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
	if ($_SERVER["SERVER_PORT"] != "80")
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	else 
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

	$pageURL =rawurlencode(rawurlencode($pageURL));
	
	$twitturl .= "http://twitter.com/home/?status=".$message." ".$pageURL;
	$html = "<a href=\"javascript:window.open('".$twitturl."','".$message."','location=false, height=300, width=500');\">".$message."<img class=\"twitter_img\" 
	src=\"../files/twitter.png\" alt=\"twitter\" /></a>";
	
	return $html;	
}
?>