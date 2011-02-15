<?php

/*
Report Name: Most Played Albums
Description: Shows a list of your albums in order of the most listened to songs.
Category: Album
Report URL: http://www.alexking.org/software/itunes_stats/
Version: 1.0
Author: Alex King
Author URL: http://www.alexking.org/
*/

$title = 'Most Played Albums';

$result = mysql_query("
	SELECT 
	  b.name AS album_name
	, a.name AS artist_name
	, SUM(s.play_count) as play_count
	, count(s.id) AS songs
	FROM `".$database_table_prefix."song` s
	JOIN `".$database_table_prefix."album` b
	ON s.album = b.id
	JOIN `".$database_table_prefix."artist` a
	ON s.artist = a.id
	GROUP BY b.id
	ORDER BY play_count DESC
") or die(mysql_error());
$grid = new grid;
$grid->columns = array(
	'Album' => 'album_name'
	,'Artist' => 'artist_name'
	,'# of Songs' => 'songs'
	,'Plays' => 'play_count'
);
while ($data = mysql_fetch_object($result)) {
	$grid->items[] = $data;
}

?>