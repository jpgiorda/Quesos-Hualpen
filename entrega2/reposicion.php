<?php
    $consumerKey = 'sxkRPZSLmlwDqOgIBETbg';
    $consumerSecret = 'b18SGk7f1osHbBB4c3V0azvTD8jGrk4MPf1oM5RqtY';
    $OAuthToken = '1428999014-56ixEk8FLO1A1ZmZMFpOot65Lt37J2dK4mjVaQq';
    $OAuthSecret = 'iUNXDeY2KHHgnDwOii4lpeiyFJSZ5um5jViJkJEbWn8';


    $almacenId = $_GET["almacendId"];
    $sku = $_GET["sku"];
    $clave = "26JkBGs";
    $mensaje = "";

    $url = "iic3103.ing.puc.cl/webservice/integra3/?function=getSkuInfo&key=".$clave;
    $ch = curl_init($url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
    $json = curl_exec($ch);
    if(!$json) {
        echo curl_error($ch);
    }
    $array = json_decode($json,TRUE);

    foreach ($array as $queso) {
        if($queso[sku] == $sku)
        {
            $mensaje = $mensaje."Reposicion de queso ".$queso[nombre];
        }
    }

    $url = "iic3103.ing.puc.cl/webservice/integra3/?function=getInfoBodegas&key=".$clave;
    $ch = curl_init($url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
    $json = curl_exec($ch);
    if(!$json) {
        echo curl_error($ch);
    }
    $array = json_decode($json,TRUE);

    foreach ($array as $bodega) {
        if($bodega[almacenId] == $almacenId)
        {
            $mensaje = $mensaje." en la bodaga: ".$bodega[tipo]." Id: ".$almacenId;
        }
    }

    // Full path to twitterOAuth.php (change OAuth to your own path)
    require_once($_SERVER['DOCUMENT_ROOT'].'/entrega2/twitteroauth-master/twitteroauth/twitteroauth.php');
    // create new instance
    $tweet = new TwitterOAuth($consumerKey, $consumerSecret, $OAuthToken, $OAuthSecret);
    // Send tweet
    $tweet->post('statuses/update', array('status' => "$mensaje"));
?>