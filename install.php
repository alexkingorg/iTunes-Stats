<?php 

// iTunes Stats
//
// Copyright (c) 2005-2010 Alex King. All rights reserved.
// http://alexking.org
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
// **********************************************************************

// requires PHP 5+ (XML libraries) and MySQL 4+ (case insensitive SELECTs)

/*

Database structure

CREATE TABLE `album` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `artist` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ;

# --------------------------------------------------------

CREATE TABLE `artist` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ;

# --------------------------------------------------------

CREATE TABLE `song` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `rating` int(11) NOT NULL default '0',
  `play_count` int(11) NOT NULL default '0',
  `artist` int(11) NOT NULL default '0',
  `album` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ;  

TRUNCATE `artist`;
TRUNCATE `album`;
TRUNCATE `song`;

*/

require_once('config.inc.php');

$database_connection = mysql_connect(
	$database_host
	, $database_user
	, $database_pass
);
if ($database_connection) {
	if (!mysql_select_db($database_name, $database_connection)) {
		die('<p>Error selecting database: '.$database_name);
	}
}
else {
	die('<p>Error connecting to database: '.$database_host);
}

mysql_query("DROP TABLE IF EXISTS `".$database_table_prefix."artist`") or die(mysql_error());
mysql_query("DROP TABLE IF EXISTS `".$database_table_prefix."album`") or die(mysql_error());
mysql_query("DROP TABLE IF EXISTS `".$database_table_prefix."song`") or die(mysql_error());

mysql_query("
	CREATE TABLE `".$database_table_prefix."album` (
	  `id` int(11) NOT NULL auto_increment,
	  `name` varchar(255) NOT NULL default '',
	  `artist` int(11) NOT NULL default '0',
	  PRIMARY KEY  (`id`)
	)
") or die(mysql_error());

mysql_query("
	CREATE TABLE `".$database_table_prefix."artist` (
	  `id` int(11) NOT NULL auto_increment,
	  `name` varchar(255) NOT NULL default '',
	  PRIMARY KEY  (`id`)
	)
") or die(mysql_error());

mysql_query("
	CREATE TABLE `".$database_table_prefix."song` (
	  `id` int(11) NOT NULL auto_increment,
	  `name` varchar(255) NOT NULL default '',
	  `rating` int(11) NOT NULL default '0',
	  `play_count` int(11) NOT NULL default '0',
	  `artist` int(11) NOT NULL default '0',
	  `album` int(11) NOT NULL default '0',
	  PRIMARY KEY  (`id`)
	)
") or die(mysql_error());

print('<p>Everything should be set up now.</p>');

?>