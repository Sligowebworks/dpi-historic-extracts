<?php

/*** Functions ***/
function curl_it($url, $file, $userpass) {
		$crl = curl_init($url);
		$fh = fopen($file, 'w');

		curl_setopt($crl, CURLOPT_FILE, $fh);
		curl_setopt($crl, CURLOPT_HEADER, 0);
		curl_setopt($crl, CURLOPT_FOLLOWLOCATION, true);
		if (isset($userpass)) {
			curl_setopt($crl, CURLOPT_USERPWD, $userpass);
			curl_setopt($crl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		}

		$output = curl_exec($crl);
		curl_close($crl);
		fclose($fh);
		return $output;
}

function any_equals($qs, $equals) {
    if (!is_array($qs)) $qs = qs_to_array($qs);
    if (!is_array($equals) ) return null;

    foreach ($equals as $equal) {
		$key = $equal[0];
		$value = $equal[1];
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
