<?php

/*
Report Name: Top Rated Albums (Weighted by # of Rated Songs and # of Plays)
Description: Shows a list of your top rated albums (by song rating), weighted by the number of songs rated per album and # of plays.
Category: Album
Report URL: http://www.alexking.org/software/itunes_stats/
Version: 1.0
Author: Alex King
Author URL: http://www.alexking.org/
*/

$title = 'Top Rated Albums (Weighted by # of Rated Songs)';

$result = mysql_query("
	SELECT 
	  AVG(s.rating) * count(*) * SUM(play_count) AS rating
	FROM `".$database_table_prefix."song` s
	JOIN `".$database_table_prefix."album` b
	ON s.album = b.id
	WHERE s.rating > 0
	GROUP BY b.id
	ORDER BY rating DESC
	LIMIT 1
") or die(mysql_error());
if ($result) {
	while ($data = mysql_fetch_object($result)) {
		$max = $data->rating;
	}
	$result = mysql_query("
		SELECT 
		  b.name AS album_name
		, a.name AS artist_name
		, ROUND(AVG(s.rating) * count(*), 2) AS rating
		, SUM(s.play_count) AS play_count
		, ROUND((AVG(s.rating) * count(*) * SUM(play_count)) / $max, 2) AS percent
		, count(s.id) AS songs
		FROM `".$database_table_prefix."song` s
		JOIN `".$database_table_prefix."album` b
		ON s.album = b.id
		JOIN `".$database_table_prefix."artist` a
		ON s.artist = a.id
		WHERE s.rating > 0
		GROUP BY b.id
		ORDER BY percent DESC
	") or die(mysql_error());
	$grid = new grid;
	$grid->columns = array(
		'Album' => 'album_name'
		,'Artist' => 'artist_name'
		,'# of Songs' => 'songs'
		,'Rating' => 'rating'
		,'Plays' => 'play_count'
		,'Relative' => 'percent'
	);
	while ($data = mysql_fetch_object($result)) {
		$grid->items[] = $data;
	}
}

?>