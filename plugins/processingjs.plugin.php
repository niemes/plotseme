<?php

if ( !defined('IN_PLOT') )
{
	die("Hacking attempt");
}

global $parser;
// feed the parser

$O = "\[";
$C = "\]";
$tag = "processing";
$param1 = "width";
$param2 = "height";

//$parser->add_tag("processing", "#\[processing\](.*?)\[/processing\]#sie", "processing('\\1')", "[processing]path/to/project/sketch.pde[/processing], twit this."); //twitter
$parser->add_tag("processing", "#{$O}({$tag})({$C}((?>{$O}(?!/?{$tag}[^{$O}]*?{$C})|[^{$O}]|(?R))*){$O})/{$tag}{$C}#ise", "processing('\\3')","[processing]path/to/project/sketch.pde[/processing]");
$parser->add_tag("processing", "#{$O}({$tag} {$param1}=(.*?) {$param2}=(.*?))({$C}((?>{$O}(?!/?{$tag}[^{$O}]*?{$C})|[^{$O}]|(?R))*){$O})/{$tag}{$C}#ise", "processing('\\5','\\2','\\3')","[processing widh=100px height=80px]path/to/project/sketch.pde[/processing]");


function processing($sketch_path, $width="100", $height="100"){
	
	$html = "[html]";
	$html .= "<div><script src='scripts/processing.js' type='text/javascript'></script>";
	$html .= "<script type='text/javascript'>";
	$html .= "function getProcessingSketchId () { return '".$sketch_path."'; }\r";
	$html .= "</script>";
	$html .= "<canvas class='ProcessingCanvas' id='".$sketch_path."' data-processing-sources='".$sketch_path."' width='".$width."' height='".$height."'>\r";
	$html .= "<p>Your browser does not support the canvas tag.</p>\r";
	$html .= "</canvas>\r";
	$html .= "<noscript>\r";
	$html .= "<p>JavaScript is required to view the contents of this page.</p>";
	$html .= "</noscript>\r";
	$html .= "</div>\r";
	$html .= "[/html]";
	return $html;
}

?>