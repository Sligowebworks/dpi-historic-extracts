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

/*
foreach ($urls as $file => $url) {
	if ( file_exists($file) ) continue;

	//file doesn't exist, log it:
	`echo $url >> report.txt`;

}*/

/*$act_report = 'report_act.txt';
`rm $act_report`;
foreach ($urls as $file => $url) {
    if ( strpos($file, 'act_') === false) continue;

	`wc -l ../$file >> $act_report`;
//	`echo $file >> $act_report`;
}*/

$att_report = 'report_att.txt';
`rm $att_report`;
foreach ($urls as $file => $url) {
	if (strpos($file, 'attendance_') === false) continue;

	`wc -l ../$file >> $att_report`;

}

$enr_report = 'report_enr.txt';
`rm $enr_report`;
foreach ($urls as $file => $url) {
	if (strpos($file, 'enrollment_') === false) continue;

	`wc -l ../$file >> $enr_report`;

}

?>
