<?php

$link[0] = array('link' => 'http://top.nydus.org/vote/4373/', 'percent' => 23);
$link[1] = array('link' => 'http://toplist.raidrush.ws/vote/5527/', 'percent' => 30);
$link[2] = array('link' => 'http://www.cyonix.to/Uranjits.html', 'percent' => 15);
$link[3] = array('link' => 'http://www.linkr.top/Uranjits.html', 'percent' => 9);

$percent_arr = array();
foreach ($link as $k => $_l) {
    $percent_arr = array_merge($percent_arr, array_fill(0, $_l['percent'], $k));
}

$random_key   = $percent_arr[mt_rand(0, count($percent_arr) - 1)];
$redirectlink = $link[$random_key]['link'];

header("Location: $redirectlink");
