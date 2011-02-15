<?php

/*
Report Name: 500 Most Played Songs
Description: Shows a list of your songs in order of "most listened to".
Category: Song
Report URL: http://www.alexking.org/software/itunes_stats/
Version: 1.0
Author: Alex King
Author URL: http://www.alexking.org/
*/

$title = '500 Most Played Songs';

$result = mysql_query("
	SELECT 
	  a.name AS artist_name
	, b.name AS album_name
	, s.name as song_name
	, s.play_count as play_count
	FROM `".$database_table_prefix."song` s
	JOIN `".$database_table_prefix."artist` a
	ON s.artist = a.id
	JOIN `".$database_table_prefix."album` b
	ON s.album = b.id
	ORDER BY play_count DESC
	LIMIT 500
") or die(mysql_error());
$grid = new grid;
$grid->columns = array(
	'Song' => 'song_name'
	,'Artist' => 'artist_name'
	,'Album' => 'album_name'
	,'Plays' => 'play_count'
);
while ($data = mysql_fetch_object($result)) {
	$grid->items[] = $data;
}

?>