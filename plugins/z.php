<?php
global $parser;

if ( !defined('IN_PLOT') )
{
	die("Hacking attempt");
}

// feed the parser
//$parser->add_tag("tag name", "search string", "replace string");


//inline elements

$parser->add_tag("tab", "(\t)", "&nbsp;&nbsp;&nbsp;&nbsp;"); //tab

$parser->add_tag("4 spaces", "(    )", "&nbsp;&nbsp;&nbsp;&nbsp;"); //4 spaces to tab
$parser->add_tag("note", "#\[note=(.*?)\]#si", "<sup id=\"fnref_\\1\"><a href=\"#fn_\\1\">\\1\</a></sup>", "[note=xxx] a footer note tag");
$parser->add_tag("note ref", "#\[noteref=(.*?)\]#si", "<span id=\"fn_\\1\"><a href=\"#fnref_\\1\">\\1</a></span>", "[noteref=xxx], footer note back link tag."); // footnotes
$parser->add_tag("url", "#\[url\](.*?)\[/url\]#si", "<a href=\"\\1\" target=\"_blank\" title=\"\\1 &#x2197;\">\\1</a>", "[url]http://www.example.com[/url], hyperlink tag."); // url [url]http://www.somewhere.org[/url]
$parser->add_tag("url=", "#\[url=(.*?)\](.*?)\[/url\]#si", "<a href=\"\\1\" target=\"_blank\" title=\"\\1 &#x2197;\">\\2</a>", "[url=http://www.example.com]hyperlink[/url], another hyperlink tag."); // url [url=http://www.somewhere.org]somewhere[/url]

$parser->add_tag("link", "#\[link=(.*?)\](.*?)\[/link\]#si", "<a href=\"?browse=\\1\" target=\"_self\" title=\"\\1\">\\2</a>"); // link [link=somwhere]sometexte[/link]
$parser->add_tag("alias", "#\[alias=(.*?)\](.*?)\[/alias\]#si", "<a href=\"?browse=\\1\" target=\"_self\" title=\"\\1\">\\2</a>"); // alias [alias=somwhere]sometexte[/alias]

$parser->add_tag("file", "#\[file\](.*?)\[/file\]#si", "<a href=\"\\1\" target=\"_blank\" title=\"\\1 &#x2197;\">\\1</a>"); //fichiers
$parser->add_tag("file=", "#\[file=(.*?)\](.*?)\[/file\]#si", "<a href=\"\\1\" target=\"_blank\" title=\"\\1 &#x2197;\">\\2</a>"); // url [url=http://www.somewhere.org]somewhere[/url]
$parser->add_tag("email", "#\[email\](.*?)\[/email\]#si","<a href=\"mailto:\\1\">\\1</a>"); // mail [email]someone@somewhere.org[/email]
$parser->add_tag("email=", "#\[email=(.*?)\](.*?)\[/email\]#si","<a href=\"mailto:\\1\">\\2</a>"); // mail [email=someone@somewhere.org]someone[/email]

$parser->add_tag("b", "#\[b\](.*?)\[/b\]#si", "<b>\\1</b>", "[b]this is bold[/b], bold font style tag."); //bold
$parser->add_tag("i", "#\[i\](.*?)\[/i\]#si", "<i>\\1</i>", "[i]this is italic[/i], italic font style tag."); //italic
$parser->add_tag("u", "#\[u\](.*?)\[/u\]#si", "<u>\\1</u>"); //underline
$parser->add_tag("strike", "#\[strike\](.*?)\[/strike\]#si", "<strike>\\1</strike>"); //strike // to be removed
$parser->add_tag("del", "#\[del\](.*?)\[/del\]#si", "<del>\\1</del>"); //del
$parser->add_tag("sub", "#\[sub\](.*?)\[/sub\]#si", "<sub>\\1</sub>"); //subscript
$parser->add_tag("sup", "#\[sup\](.*?)\[/sup\]#si", "<sup>\\1</sup>"); //superscript
$parser->add_tag("color", "#\[color=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)\[/color\]#si", "<span style=\"color:\\1\">\\2</span>"); // text color
$parser->add_tag("bgcolor", "#\[bgcolor=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)\[/bgcolor\]#si", "<span style=\"background:\\1\">\\2</span>"); // text bg color
$parser->add_tag("font", "#\[font=(.*?)\](.*?)\[/font\]#si", "<span style=\"font:\\1\">\\2</span>"); // text font


$parser->add_tag("size", "#\[size=([_a-z0-9-]+)](.*?)\[/size\]#si",  "<span style=\"font-size:\\1\">\\2</span>"); //text  size
$parser->add_tag("blink", "#\[blink\](.*?)\[/blink\]#si", "<span class='blink'>\\1</span>"); //blink
$parser->add_tag("highlight", "#\[highlight\](.*?)\[/highlight\]#si", "<span class=\"highlight\">\\1</span>"); //del

$parser->add_tag("center", "#\[center\](.*?)\[/center\]#si", "<div class=\"center\">\\1</div>"); //center
$parser->add_tag("left", "#\[left\](.*?)\[/left\]#si", "<div class=\"left\">\\1</div>"); //left
$parser->add_tag("right", "#\[right\](.*?)\[/right\]#si", "<div class=\"right\">\\1</div>"); //right
$parser->add_tag("justify", "#\[justify\](.*?)\[/justify\]#si", "<div class=\"justify\">\\1</div>"); //left

$parser->add_tag("div", "#\[div\](.*?)\[/div\]#si", "<div>\\1</div>"); //div
$parser->add_tag("div id=", "#\[div id=(.*?)\](.*?)\[/div\]#si", "<div id=\"\\1\">\\2</div>"); //div id=
$parser->add_tag("div class=", "#\[div class=(.*?)\](.*?)\[/div\]#si", "<div class=\"\\1\">\\2</div>"); //div class=
$parser->add_tag("span", "#\[span\](.*?)\[/span\]#si", "<span>\\1</span>"); //span
$parser->add_tag("span id=", "#\[span id=(.*?)\](.*?)\[/span\]#si", "<span id=\"\\1\">\\2</span>"); //span id=
$parser->add_tag("span class=", "#\[span class=(.*?)\](.*?)\[/span\]#si", "<span class=\"\\1\">\\2</span>"); //span id=

//block elements
$parser->add_tag("embed", "#\[embed\](.*?)\[/embed\]#si", "<embed src=\"\\1\" />"); //embed [embed]http://www.somewere.com/image.png[/embed]

$parser->add_tag("list", "#\[list\](.*?)\[/list\]#si", "<ul>\\1</ul>"); //list
$parser->add_tag("pre", "#\[pre\](.*?)\[/pre\]#si", "<pre>\\1</pre>"); //preformatted

$parser->add_tag("img", "#\[img\](.*?)\[/img\]#si", "<img src=\"\\1\" alt=\"\\1\"/>"); //image [img]http://www.somewere.com/image.png[/img]
$parser->add_tag("img alt=", "#\[img alt=(.*?)\](.*?)\[/img\]#si","<img src=\"\\2\" alt=\"\\1\" />"); //[img=campiong.png]camping[/img]
$parser->add_tag("img right", "#\[img right\](.*?)\[/img\]#si", "<img align=\"right\" src=\"\\1\" alt=\"\\1\" />"); //image [img]http://www.somewere.com/image.png[/img]
$parser->add_tag("img right=", "#\[img right=(.*?)\](.*?)\[/img\]#si","<img align=\"right\" src=\"\\1\" alt=\"\\2\" />"); //[img=campiong.png]camping[/img]
$parser->add_tag("img left", "#\[img left\](.*?)\[/img\]#si", "<img align=\"left\" src=\"\\1\" alt=\"\\1\" />"); //image [img]http://www.somewere.com/image.png[/img]
$parser->add_tag("img left=", "#\[img left=(.*?)\](.*?)\[/img\]#si","<img align=\"left\" src=\"\\1\" alt=\"\\2\" />"); //[img=campiong.png]camping[/img]
$parser->add_tag("img width= height=", "#\[img width=(.*?) height=(.*?)\](.*?)\[/img\]#si","<img width=\"\\1\" height=\"\\2\" src=\"\\3\" alt=\"\\4\" />"); //[img=campiong.png]camping[/img]

$parser->add_tag("pde", "#\[pde\](.*?)\[/pde\]#si", "<applet code=\"\\1\" archive=\"pde/\\1.jar\" width=\"200\" height=\"200\" mayscript=\"true\"><param name=\"image\" value=\"loading.gif\"><param name=\"boxmessage\" value=\"Loading Processing software...\"><param name=\"boxbgcolor\" value=\"#FFFFFF\"><!-- This is the message that shows up when people don't have Java installed in their browser. -->To view this content, you need to install Java from <A HREF=\"http://java.com\">java.com</A></applet><br />Source code: <a href=\"pde/\\1.pde\">\\1</a>"); //processing [pde]pde_script_name[/pde]
$parser->add_tag("movie", "#\[movie width=(.*?) height=(.*?)\](.*?)\[/movie\]#si", "<OBJECT CLASSID=\"clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B\" width=\"\\1\" height=\"\\2\"  CODEBASE=\"http://www.apple.com/qtactivex/qtplugin.cab\"><PARAM name=\"SRC\" VALUE=\"\\3\"><PARAM name=\"CONTROLLER\" VALUE=\"true\"><PARAM name=\"AUTOPLAY\" VALUE=\"false\"><embed src=\"\\3\" width=\"\\1\" height=\"\\2\" autoplay=\"false\" controller=\"true\" PLUGINSPAGE=\"http://www.apple.com/quicktime/download/\"></embed></object>"); //video [video]qt file[/video]
$parser->add_tag("movie", "#\[movie\](.*?)\[/movie\]#si", "<OBJECT CLASSID=\"clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B\" CODEBASE=\"http://www.apple.com/qtactivex/qtplugin.cab\"><PARAM name=\"SRC\" VALUE=\"\\1\"><PARAM name=\"CONTROLLER\" VALUE=\"true\"><PARAM name=\"AUTOPLAY\" VALUE=\"false\"><embed src=\"\\1\" autoplay=\"false\" controller=\"true\" PLUGINSPAGE=\"http://www.apple.com/quicktime/download/\"></embed></object>"); //video [video]qt file[/video]

$parser->add_tag("comments", "#\/\*(.+?)\*/#is", "<!-- \\1-->"); //tr
?>