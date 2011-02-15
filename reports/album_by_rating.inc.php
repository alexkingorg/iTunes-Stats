<?php

/*
Report Name: Top Rated Albums
Description: Shows a list of your top rated albums (by song rating).
Category: Album
Report URL: http://www.alexking.org/software/itunes_stats/
Version: 1.0
Author: Alex King
Author URL: http://www.alexking.org/
*/

$title = 'Top Rated Albums';

$result = mysql_query("
	SELECT 
	  b.name AS album_name
	, a.name AS artist_name
	, ROUND(AVG(s.rating), 2) AS rating
	, count(s.id) AS songs
	FROM `".$database_table_prefix."song` s
	JOIN `".$database_table_prefix."album` b
	ON s.album = b.id
	JOIN `".$database_table_prefix."artist` a
	ON s.artist = a.id
	WHERE s.rating > 0
	GROUP BY b.id
	ORDER BY rating DESC
") or die(mysql_error());
$grid = new grid;
$grid->columns = array(
	'Album' => 'album_name'
	,'Artist' => 'artist_name'
	,'# of Songs' => 'songs'
	,'Rating' => 'rating'
);
while ($data = mysql_fetch_object($result)) {
	$grid->items[] = $data;
}

?>