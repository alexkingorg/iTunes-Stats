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

class artist {
	var $id;
	var $name;
	var $albums;
	var $songs;
	
	function insert() {
		global $database_table_prefix;
		
		mysql_query("
			INSERT 
			INTO `".$database_table_prefix."artist`
			( `name`
			)
			VALUES 
			( '".addslashes($this->name)."'
			)
		") or die(mysql_error());

		$this->id = mysql_insert_id();
	}
}

class album {
	var $id;
	var $name;
	var $artist;
	var $artist_id;
	var $songs;
	var $rating;

	function insert() {
		global $database_table_prefix;
		
		mysql_query("
			INSERT 
			INTO `".$database_table_prefix."album`
			( `name`
			, `artist`
			)
			VALUES 
			( '".addslashes($this->name)."'
			, '".addslashes($this->artist)."'
			)
		") or die(mysql_error());

		$this->id = mysql_insert_id();
	}
}

class song {
	var $id;
	var $name;
	var $artist;
	var $artist_id;
	var $album;
	var $album_id;
	var $play_count;
	var $rating;
	
	function song() {
		$this->artist_id = 0;
		$this->album_id = 0;
		$this->play_count = 0;
		$this->rating = 0;
	}

	function insert() {
		global $database_table_prefix;
		
		if (!empty($this->artist)) {
			$result = mysql_query("
				SELECT *
				FROM `".$database_table_prefix."artist`
				WHERE `name` = '".addslashes($this->artist)."'
			") or die(mysql_error());
	
			if (mysql_num_rows($result) == 0) {
				$artist = new artist;
				$artist->name = $this->artist;
				$artist->insert();
				$this->artist_id = $artist->id;
			}
			else {
				while ($data = mysql_fetch_object($result)) {
					$this->artist_id = $data->id;
				}
			}
		}

		if (!empty($this->album)) {
			$result = mysql_query("
				SELECT *
				FROM `".$database_table_prefix."album`
				WHERE `name` = '".addslashes($this->album)."'
				AND `artist` = '".addslashes($this->artist_id)."'
			") or die(mysql_error());
	
			if (mysql_num_rows($result) == 0) {
				$album = new album;
				$album->name = $this->album;
				$album->artist = $this->artist_id;
				$album->insert();
				$this->album_id = $album->id;
			}
			else {
				while ($data = mysql_fetch_object($result)) {
					$this->album_id = $data->id;
				}
			}
		}

		$result = mysql_query("
			INSERT 
			INTO `".$database_table_prefix."song`
			( `name`
			, `rating`
			, `play_count`
			, `artist`
			, `album`
			)
			VALUES 
			( '".addslashes($this->name)."'
			, '".addslashes($this->rating)."'
			, '".addslashes($this->play_count)."'
			, '".addslashes($this->artist_id)."'
			, '".addslashes($this->album_id)."'
			)
		") or die(mysql_error());

		$this->id = mysql_insert_id();
	}
}

class grid {
	var $items;
	var $columns;
	
	function grid() {
		$this->items = array();
		$this->columns = array();
	}
	
	function display() {
		print('
			<table class="report" cellspacing="1">
				<thead>
					<tr>
						<th></th>
		');
		foreach ($this->columns as $title => $property) {
			print('
						<th>'.$title.'</th>
			');
		}
		print('
					</tr>
				</thead>
				<tbody>
		');
		$i = 0;
		foreach ($this->items as $item) { 
			$i++;
			if ($i % 2 != 0) {
				$class = ' class="odd"';
			}
			else {
				$class = '';
			}
			print('
					<tr'.$class.'>
						<td class="right">'.$i.'</td>
			');
			foreach ($this->columns as $title => $property) { 
				if (!in_array($title, array('Artist', 'Album', 'Song'))) {
					$class = ' class="right"';
				}
				else {
					$class = '';
				}
				print('
						<td'.$class.'>'.$item->$property.'</td>
				');
			}
			print('
					</tr>
			');
		}
		print('
				</tbody>
			</table>
		');
	}
}

class report {
	var $author;
	var $author_URL;
	var $name;
	var $description;
	var $version;
	var $URL;
	var $key;
	var $category;
	
	function report() {
		$this->author = '';
		$this->author_URL = '';
		$this->name = '';
		$this->description = '';
		$this->version = '';
		$this->URL = '';
		$this->key = '';
		$this->category = '';
	}

	function author_link() {
		if (empty($this->author) && empty($this->author_URL)) {
			return 'Anonymous';
		}
		else if (!empty($this->author) && !empty($this->author_URL)) {
			return '<a href="'.$this->author_URL.'">'.$this->author.'</a>';
		}
		else if (!empty($this->author) && empty($this->author_URL)) {
			return $this->author;
		}
		else if (empty($this->author) && !empty($this->author_URL)) {
			return '<a href="'.$this->author_URL.'">'.$this->author_URL.'</a>';
		}
	}
	
	function print_dl_item() {
		print('
			<dt><a href="index.php?report='.$this->key.'">'.$this->name.'</a></dt>
			<dd>
				<p>'.$this->description.'</p>
				<ul>
					<li>Version: '.$this->version.'</li>
					<li>By: '.$this->author_link().'</li>
				</ul>
			</dd>
		');

	}
	
}

?>