<?php

$consumerKey = 'sxkRPZSLmlwDqOgIBETbg';
$consumerSecret = 'b18SGk7f1osHbBB4c3V0azvTD8jGrk4MPf1oM5RqtY';
$accessToken = '1428999014-56ixEk8FLO1A1ZmZMFpOot65Lt37J2dK4mjVaQq';
$accessTokenSecret = 'iUNXDeY2KHHgnDwOii4lpeiyFJSZ5um5jViJkJEbWn8';


require_once '../twitter.class.php';
echo "Hello";
// ENTER HERE YOUR CREDENTIALS (see readme.txt)
$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$status = $twitter->send('I am fine');

echo $status ? 'OK' : 'ERROR';
