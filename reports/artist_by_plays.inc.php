<?php

/*
Report Name: Most Played Artists
Description: Shows a list of your artists in order of the most listened to songs.
Category: Artist
Report URL: http://www.alexking.org/software/itunes_stats/
Version: 1.0
Author: Alex King
Author URL: http://www.alexking.org/
*/

$title = 'Most Played Artists';

$result = mysql_query("
	SELECT 
	a.name AS artist_name
	, SUM(s.play_count) as play_count
	, count(s.id) AS songs
	FROM `".$database_table_prefix."song` s
	JOIN `".$database_table_prefix."artist` a
	ON s.artist = a.id
	GROUP BY a.id
	ORDER BY play_count DESC
") or die(mysql_error());
$grid = new grid;
$grid->columns = array(
	'Artist' => 'artist_name'
	,'# of Songs' => 'songs'
	,'Plays' => 'play_count'
);
while ($data = mysql_fetch_object($result)) {
	$grid->items[] = $data;
}

?>