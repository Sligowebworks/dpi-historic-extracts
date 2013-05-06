#!/usr/bin/php
<?php

$webroot = "http://data.dpi.state.wi.us/data";

include dirname(__FILE__).'/config.php';
include dirname(__FILE__).'/functions.php';

$configs = get_config();
$labels = get_labels();

$urls = array();
foreach ($configs as $name => $conf) {
    $urls = array_merge($urls, create_urls($name, $conf));
}

foreach ($urls as $file => $url) {
	if ( file_exists($file) ) continue;

	//file doesn't exist, log it:
	`echo $url >> report.txt`;

}

?>
