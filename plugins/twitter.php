<?php

if ( !defined('IN_PLOT') )
{
	die("Hacking attempt");
}

global $parser;
// feed the parser

$O = "\[";
$C = "\]";
$tag = "twitter";

$parser->add_tag("twitter", "#\[twitter\](.*?)\[/twitter\]#sie", "twit('\\1')", "[twitter]message[/twitter], twit this."); //twitter


function twit($message){

	$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
	if ($_SERVER["SERVER_PORT"] != "80")
	{
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} 
	else 
	{
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}

	//$pageURL = urlencode($pageURL);
	
	//$html = $pageURL."<br />";
	//$html .= "<a rel=\"nofollow\" target=\"_blank\" href=\"http://twitter.com/home/?status=".$message." ".$pageURL."\">".$message."</a>";
	//$twitturl .= "http://twitter.com/home/?status=".$message." ".$pageURL;

	//$html .= "<a href=\"javascript:window.open('".$twitturl."','".$message."','location=false, height=300, width=500');\">".$message." </a>";
	
	
	

	$html = "[html]";
	$html .= "<a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-text=\"".($message)."\" data-url=\"".$pageURL."\" data-counturl=\"http://groups.google.com/group/twitter-api-announce\">".($message)."</a>";
	$html .= "<script type=\"text/javascript\">\r";
	$html .= "!function(d,s,id){\r";
	$html .= "var js,fjs=d.getElementsByTagName(s)[0];\r";
	$html .= "if(!d.getElementById(id)){\r";
	$html .= "js=d.createElement(s);\r";
	$html .= "js.id=id;\r";
	$html .= "js.src=\"//platform.twitter.com/widgets.js\";\r";
	$html .= "fjs.parentNode.insertBefore(js,fjs);\r";
	$html .= "}}\r";
	$html .= "(document,\"script\",\"twitter-wjs\");\r";
	$html .= "</script>\r";
	$html .= "[/html]";
	return $html;
	
	/*
	$html .= "<a href=\"#\" onmousedown=\"popup('popup".stripslashes($name)."')\">".stripslashes($name)."</a>";
	$html .= "<div class=\"popup\" id=\"popup".stripslashes($name)."\" style=\"display:none;\">";
	$html .= stripslashes($content);
	$html .= "</div>";
  
	
    */
}

?>