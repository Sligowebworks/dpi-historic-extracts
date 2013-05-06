#!/usr/bin/php
<?php

$test_run = false;
$with_file_check = true;
$throttle = 0; # seconds between requests

$webroot = "http://data.dpi.state.wi.us/data";

include dirname(__FILE__).'/config.php';

/*** JOB ***/
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
        //`wget "$url" -O $file`;
        sleep($throttle);
    }
}

/*** Functions ***/

function is_disabled_condition($dataset, $qs) {
    if (!is_array($qs)) $qs = qs_to_array($qs);
  
    switch ($dataset) {
    case 'WSAS':
        if (get_param('WOW', $qs) == 'WSAS') {
            if (any_equals($qs, array('Level' => 'SWD', 'Level' => 'ELL'))) return true;  
            if (get_param('Year', $qs) < '2003') return true;
        } else {
            if (get_param('Year', $qs) > '2002') return true;
        }
//var_dump(get_param('SubjectID',$qs));
        if (get_param('SubjectID', $qs) == '0AS') return true;
    break;
    case 'Attendance':
        //if year is < 2005 && Disability, Economic Status, English Proficiency
        if (any_equals($qs, array('Group' => 'Disability', 'Group' => 'EconDisadv', 'Group' => 'ELP'))) {
            if (get_param('Year', $qs) < 2005) return true;
        }
    break;
    }

    return false;
}

function any_equals($qs, $equals) {
    if (!is_array($qs)) $qs = qs_to_array($qs);
    if (!is_array($equals) ) return null;

    foreach ($equals as $key => $value) {
        if (!array_key_exists($key, $qs)) continue;
        if ($qs[$key] == $value) return true;
    }

    return false;
}

function get_param($name, $qs) {
    if (is_array($qs) !== true) $qs = qs_to_array($qs);

    if (!array_key_exists($name, $qs)) { 
        return null;
    }
    return $qs[$name];
}

function get_qs($conf) {
//var_dump($conf);
	$params =& $conf['params']; 
	$minyear = $conf['minyear'];
	$maxyear = $conf['maxyear'];

	for ($year = $minyear; $year <= $maxyear; $year++) {
	    $params['Year'][] = $year;
	}

	$qs = array('');
	$n = 1;
	foreach ($params as $param => $args ) {
	    $tmp_qs = array();
	    foreach ($qs as $root) {
		$tmp_qs = array_merge($tmp_qs, qs_product($root.'&'.$param.'=', $args));
	    }
	    $qs = $tmp_qs;
	}

	 return $qs;
}

function qs_to_array($qs) {
    preg_match_all('/&([^=]*)=([^&]*)/', $qs, $params);
    return array_combine($params[1], $params[2]);
}

function get_filename($dataset, $qs) {
    global $labels;
    $file = $dataset['fileprefix'];

    foreach (qs_to_array($qs) as $param => $arg) {
        if ( array_key_exists($param, $labels)
	    && array_key_exists($arg, $labels[$param])) {
	    $arg = $labels[$param][$arg];
        }
	//if ($param == 'WOW') continue;
        if ($param == 'Year')
		$arg = $arg-1 .'-'.substr($arg,2,2);
        $file .= "_$arg";
    }

    return strtolower("$file.csv");
}

function qs_product($root, $array) {
    $product = array();
    foreach ($array as $value) {
        $product[] = $root.$value;
    }
    return $product;
}

/***
 * From a dataset configuration,
 * build Query Strings
 * generate file names
 * validate requests
 * generate URLs
 * return array of filename => url's
 ***/
function create_urls($dataset, $conf) {
    global $webroot;
    $qs_list = get_qs($conf);
    $page = $conf['page'];
    $urls = array();
    foreach ($qs_list as $qs) {
        if (is_disabled_condition($dataset, $qs)) continue;
        $file = get_filename($conf, $qs);
        if (key_exists($file, $urls)) echo "WARNING: Duplicate Key Detected: $file, $value;";
        $urls[$file] = "$webroot/$page?$qs&SupDwnld=1";
    }
    return $urls;
}

?>
