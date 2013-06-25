<?php

    $consumerKey = 'sxkRPZSLmlwDqOgIBETbg';
    $consumerSecret = 'b18SGk7f1osHbBB4c3V0azvTD8jGrk4MPf1oM5RqtY';
    $OAuthToken = '1428999014-56ixEk8FLO1A1ZmZMFpOot65Lt37J2dK4mjVaQq';
    $OAuthSecret = 'iUNXDeY2KHHgnDwOii4lpeiyFJSZ5um5jViJkJEbWn8';

    include 'tmhOAuth-master/tmhOAuth.php';

    $tmhOAuth = new tmhOAuth(array(
        'consumer_key' => $consumerKey,
        'consumer_secret' => $consumerSecret,
        'token' => $OAuthToken,
        'secret' => $OAuthSecret,
    ));

    $mensaje = "hola";

    $response = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), array(
        'status' => $mensaje
    ));

    if ($response != 200) {
        echo 'There was an error posting the message';
    }
?>
