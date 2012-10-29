<?php

if ( !defined('IN_PLOT') )
{
	die("Hacking attempt");
}

global $parser;

// feed the parser

$parser->add_tag("btable", "#\[btable\](.*?)\[/btable\]#si", "<table>\\1</table>"); //btable
$parser->add_tag("tablehack", "#\[/bline\]([\n])\[bline\]#si", "[/bline][bline]");
$parser->add_tag("bline", "#\[bline\](.*?)\|(.*?)\|(.*?)\|(.*?)\[/bline\]#si","<tr><td><b>\\1</b></td><td><i>\\2</i></td><td>\\3</td><td><font color=\"green\">\\4</font></td></tr>");

 ?>
