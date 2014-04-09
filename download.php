#!/usr/bin/php
<?php

$test_run = false;
$with_file_check = true; #for partial-downloads, check to see if already downloaded
$throttle = 20; # seconds between requests

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
	if ($with_file_check && file_exists($file) ) continue;

echo `date`."requesting: $file\n";
    if (!$test_run) {
	    `wget --ignore-length --tries 1 --save-cookies cookie-jar --keep-session-cookies "$url" -O $file`;
	//	curl_it($url, $file);
        sleep($throttle);
    }
}

?>
