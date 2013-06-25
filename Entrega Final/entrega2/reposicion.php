<?php
    echo "rep";
    $consumerKey = 'sxkRPZSLmlwDqOgIBETbg';
    $consumerSecret = 'b18SGk7f1osHbBB4c3V0azvTD8jGrk4MPf1oM5RqtY';
    $OAuthToken = '1428999014-56ixEk8FLO1A1ZmZMFpOot65Lt37J2dK4mjVaQq';
    $OAuthSecret = 'iUNXDeY2KHHgnDwOii4lpeiyFJSZ5um5jViJkJEbWn8';


    $almacenId = $_GET["almacendId"];
    $sku = $_GET["sku"];
    $clave = "26JkBGs";
    $mensaje = "";
    $url = "iic3103.ing.puc.cl/webservice/integra3/?function=getStock&key=".$clave."&params=".$sku;
    $ch = curl_init($url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
    $json = curl_exec($ch);
    if(!$json) {
        echo curl_error($ch);
    }
    $array = json_decode($json,TRUE);
    echo "si";

    $validador = 0;

    foreach ($array as $disponible) {
        $diso = $disponible[almacenId];
        $diso = $diso + 0;
        echo $diso." = ".$almacenId;
        $almacenId = $almacenId + 0;
        if($diso == $almacenId)
        {
            $validador = 1;
            $disp0 = $disponible[libre];
            $disp0 = $disp0 + 0;
            echo $disp0;
            if($disp0 <= 0)
            {
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


                include 'tmhOAuth-master/tmhOAuth.php';

                $tmhOAuth = new tmhOAuth(array(
                    'consumer_key' => $consumerKey,
                    'consumer_secret' => $consumerSecret,
                    'token' => $OAuthToken,
                    'secret' => $OAuthSecret,
                ));

                $response = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), array(
                    'status' => $mensaje
                ));

                if ($response != 200) {
                    echo 'There was an error posting the message';
                }

            }
        }
    }

    if($validador == 0)
    {

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
        
        include 'tmhOAuth-master/tmhOAuth.php';

        $tmhOAuth = new tmhOAuth(array(
            'consumer_key' => $consumerKey,
            'consumer_secret' => $consumerSecret,
            'token' => $OAuthToken,
            'secret' => $OAuthSecret,
        ));

        $response = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), array(
            'status' => $mensaje
        ));

        if ($response != 200) {
            echo 'There was an error posting the message';
        }  
    }
?>