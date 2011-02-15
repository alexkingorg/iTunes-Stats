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

function print_pre($data) {
	print('<pre>');
	print_r($data);
	print('</pre>');
}

function get_reports() {
	$reports = array();

	$report_meta = array(
		'name' => 'Report Name:'
		, 'description' => 'Description:'
		, 'URL' => 'Report URL:'
		, 'version' => 'Version:'
		, 'author' => 'Author:'
		, 'author_URL' => 'Author URL:'
		, 'category' => 'Category:'
	);
	
	$path = 'reports/';
	if ($handle = opendir($path)) {
		while (false !== ($filename = readdir($handle))) {
			if ($filename != "." && $filename != ".." && is_file($path.$filename) && 
				strtolower(substr($filename, -4, 4)) == ".php") {
				$data = file($path.$filename);
				$report = new report;
				for ($i = 0; $i < count($data); $i++) {
					$report->key = str_replace(array('.inc.php', '.php'), '', $filename);
					foreach ($report_meta as $property => $string) {
						if (strstr($data[$i], $string)) {
							$report->$property = trim(str_replace($string, '', $data[$i]));
						}
					}
					if (strstr($data[$i], '*/')) {
						if (!empty($report->category)) {
							if (!isset($reports[$report->category])) {
								$reports[$report->category] = array();
							}
							$reports[$report->category][] = $report;
						}
						else {
							$reports[] = $report;
						}
						$i = count($data);
					}
				}
			}
		}
		closedir($handle);
	}
	
	return $reports;
}

function print_reports_category($title = '', $reports = array()) {
	if (count($reports) < 1) {
		return;
	}
	print('
		<h3 class="reports">'.$title.'</h3>
		<dl class="reports">
	');
	foreach ($reports as $report) {
		$report->print_dl_item();
	}
	print('
		</dl>
	');
}

?>