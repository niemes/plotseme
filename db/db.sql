-- phpMyAdmin SQL Dump
-- version 2.7.0-beta1
-- http://www.phpmyadmin.net
-- 
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2007 at 04:54 PM
-- Server version: 5.0.18
-- PHP Version: 5.2.4
-- 
-- Database: `plot2`
-- 

-- CREATE DATABASE `plot4` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
-- USE 1175;


-- --------------------------------------------------------

-- 
-- Table structure for table `plot_pages`
-- 

CREATE TABLE IF NOT EXISTS `@SQL_TABLE_PAGES@` (
  `name` varchar(255) NOT NULL default '',
  `text` text,
  `keywords` varchar(255) default NULL,
  `modification_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `creation_date` timestamp NULL default NULL,
  `ip` varchar(255) default NULL,
  `author` varchar(255) NOT NULL default 'nobody',
  `template` varchar(255) default NULL,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


INSERT INTO `@SQL_TABLE_PAGES@` (`name`, `text`, `keywords`, `modification_date`, `creation_date`, `ip`, `author`) VALUES
('.footer', 'Visites so far: #COUNTER\n\n\n', '', '2007-12-29 10:07:10', '2007-12-26 17:28:14', '::1', ''),
('.menu', '[Index] | [Liste] | [Liste par attributs] | #EDIT | #ADMIN | #LOGIN', '', '2007-12-24 14:26:08', '2007-02-28 12:50:52', '::1', ''),
('.css', 'body\n{\ncolor: #222;\nfont-family: ''trebuchet ms'', helvetica, arial, sans-serif;\nfont-size: 11px;\ntext-align: justify;\nbackground-color: #FFF;\n}\n\nb\n{\n}\n\n.left\n{\ntext-align: left;\n}\n\n.right\n{\ntext-align: right;\n}\n\n.center\n{\ntext-align: center;\n}\n\n.justify\n{\ntext-align: justify;\n}\n\nA\n{\ntext-decoration: none;\ncolor: #c60;\n}\n\nA:link\n{\ntext-decoration: none;\ncolor: #c60;\n}\n\nA:visited\n{\ncolor: #c60;\ntext-decoration: none;\n}\n\nA:hover\n{\ntext-decoration: underline;\ncolor: #c60;\n}\n\nA:active\n{\ntext-decoration: underline;\ncolor: #900;\n}\n\nul\n{\nlist-style-type: circle;\nlist-style-position: outside;\nmargin-left: -20px;\n}\n\nimg\n{\nmargin: 5px;\npadding: 2px;\nborder-width: 0px;\n}\n\nhr\n{\nwidth: 100%;\nborder-color: #c60;\nborder-width: 1px 0 0 0;\nborder-style: dashed;\n}\n\ntextarea\n{\nfont-family: ''trebuchet ms'', helvetica, arial, sans-serif;\nfont-size: 11px;\nwidth:100%;\ntext-align: justify;\n}\n\ninput\n{\nfont-family: ''trebuchet ms'', helvetica, arial, sans-serif;\nfont-size: 11px;\n}\n\nfieldset {\nborder-width: 0px;\n}\n\ntable {\nfont-family: ''trebuchet ms'', helvetica, arial, sans-serif;\nfont-size: 11px;\n}\n\ntd {\nalign: top;\n}\n\n.searchform {\ndisplay: inline;\nborder: 0px;\n}\n\n.clearer {\nclear: both;\n}\n\n.highlight {\ncolor:#f00;\nbackground-color:#fc6;\n}\n\n// back links\n.blinks\n{\nline-height: 10px;\ntext-align: right;\n}\nA.blinks\n{\ncolor: #777;\n}\n\nA.blinks:hover\n{\ntext-decoration: none;\ncolor: #333;\n}\n\nA.blinks: visited\n{\ntext-decoration: none;\ncolor: #777;\n}\n\nA.blinks: active\n{\ntext-decoration: none;\ncolor: #777;\n}\n\n\n.box {\nbackground-color: #FFF;\nborder-color: #c60;\nborder-width: 1px;\nborder-style: dashed;\npadding:10px;\n}\n\n#page_top{\nbackground-color: #FFF;\n}\n\n#page_frame{\nbackground-color: #FFF;\n}\n\n#page_left{\nfloat:left;\nwidth:140px;\n}\n\n#page_right{\npadding:10px;\nfloat:right;\nwidth:140px;\nbackground-color: #FFF;\n}\n\n#page_bottom{\nclear:both;\nbackground-color: #FFF;\n}\n\n#header\n{\nfont-size: 11px;\npadding:10px;\ncolor: inherit;\nbackground-color: inherit;\n}\n\n#menu\n{\npadding: 10px;\ncolor: inherit;\nbackground-color: #FFF;\n}\n\n#content\n{\npadding:10px;\nmargin-left:160px;\ntext-align: justify;\ncolor: inherit;\nbackground-color: #FFF;\n}\n\n#title\n{\nmargin-left:140px;\npadding:10px;\nfont-size: 64px;\nfont-family: Georgia, Garamond, "Times New Roman", Times, serif;\nfont-weight: lighter !important;\nline-height: 30px;\ntext-decoration: none;\ncolor: inherit;\nbackground-color: #FFF;\ntext-align: left;\n}\n\n#footer\n{\nclear:both;\ncolor: inherit;\nbackground-color: #FFF;\n}', '', '2007-12-27 17:08:17', '2007-03-24 15:41:09', '::1', ''),
('.copyright', '(cc) 010175.net', '', '2007-10-28 17:30:53', '2007-10-27 16:50:22', '', ''),
('.title', '#TITLE', '', '2007-11-21 18:14:28', '2007-10-28 18:26:37', '::1', ''),
('Liste', '[b]LISTE[/b]\n\n{}', '', '2007-12-23 15:48:45', '2007-10-27 15:01:39', '::1', ''),
('.skeleton', '<div id=page_frame>\n<div id=page_top>\n<div id=header>[.header]</div>\n<div id=menu>[.menu]</div>\n</div>\n<div id=page_left>\n<div class=box>\n[liste]\n</div>\n<br />\n<div class=box>\n[.links]\n</div>\n<br />\n<div class=box>\n[.search]\n</div>\n</div>\n<div id=title>[.title]</div>\n#CONTENT\n<div id=page_bottom>\n[.footer]\n[.copyright]\n</div>\n</div>', '', '2007-12-26 10:53:23', '2007-10-27 15:43:01', '::1', ''),
('.header', '[b]Plotsème de Publication Plot[/b]', '', '2007-12-27 17:03:05', '2007-02-27 11:41:57', '::1', ''),
('index', '', 'special', '2008-01-06 16:57:50', '2007-11-20 23:32:34', '::1', ''),
('.links', '[b]SPECIAL[/b]\n\n{special}\n\n', '', '2007-12-24 14:15:28', '2007-12-24 10:39:55', '::1', ''),
('.search', '[b]SEARCH[/b]\n\n#SEARCH', '', '2007-12-23 15:48:28', '2007-12-23 15:48:19', '::1', ''),
('Liste par attributs', '{}', 'special', '2007-12-29 10:06:49', '2007-12-24 10:12:50', '::1', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `plot_users`
-- 

CREATE TABLE IF NOT EXISTS `@SQL_TABLE_USERS@` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `privilege` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

