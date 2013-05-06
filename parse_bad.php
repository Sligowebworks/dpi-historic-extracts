#!/usr/bin/php
<?php

$requests = array();

while ($line = fgets(STDIN)) {
    $match = array();
    preg_match('/requesting: (.*)/', $line, $match);
    if (count($match) > 0) accumulate('file', $match[1]);
    preg_match('/(http.*)/', $line, $match);
    if (count($match) > 0) accumulate('url', $match[1]);
}

function accumulate($type, $value) {
    global $requests;
    $last = count($requests)-1;
    if ($type == 'file')
	$requests[] = array('file' => $value);
    if ($type == 'url')
	$requests[$last]['url'] = $value;
}

//var_dump($requests);
if (!is_dir('re-try')) `mkdir re-try`;
//`rm re-try/*`;

foreach ($requests as $request) {
    $file = 're-try/'.$request['file'];
    if (file_exists($file)) { /*echo "skipping $file\n";*/ continue; }
    $url = $request['url'];
//    `wget --progress=bar:force --ignore-length --tries 1 --save-cookies cookie-jar --keep-session-cookies "$url" -O $file`;
//    sleep(60);
//    curl_it($url, $file);
    report($url, $file);
}
function report($url, $file) {
    echo "$file $url\n";
}
function curl_it($url, $file) {
	$crl = curl_init($url);
	$fh = fopen($file, 'w');

	curl_setopt($crl, CURLOPT_FILE, $fh);
	curl_setopt($crl, CURLOPT_HEADER, 0);

	curl_exec($crl);
	curl_close($crl);
	fclose($fh);
}


