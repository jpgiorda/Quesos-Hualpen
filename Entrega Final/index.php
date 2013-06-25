<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Documento sin título</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />

	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyADi1TXUM4WTLSpPv6ZASb1Yqb69UsT5rY&sensor=false"
	            type="text/javascript"></script>

</head>

<body class="oneColElsCtr" onload="initialize()" onunload="GUnload()">
    <div id="header">
    	<img src="img/logo_h.png" width="158" height="100" />
        </div>
    <div id="container">
    	<div id="mainContent">
            <div class="window">
            <img src="img/logo-twitter.png" width="27" height="27" /><br>
		<?php

		$oauth_hash = '';
		$oauth_hash .= 'oauth_consumer_key=E6O8k3hnG4DVuoyPyqxoUg&';
		$oauth_hash .= 'oauth_nonce=' . time() . '&';
		$oauth_hash .= 'oauth_signature_method=HMAC-SHA1&';
		$oauth_hash .= 'oauth_timestamp=' . time() . '&';
		$oauth_hash .= 'oauth_token=1428999014-7yu5M3KAroiu6fwDgGyGoDNrFivRARgsChYLAOk&';
		$oauth_hash .= 'oauth_version=1.0';

		$base = '';
		$base .= 'GET';
		$base .= '&';
		$base .= rawurlencode('https://api.twitter.com/1.1/statuses/user_timeline.json');
		$base .= '&';
		$base .= rawurlencode($oauth_hash);

		$key = '';
		$key .= rawurlencode('RA9HZZq4vpZuI2ovGS0jkPZpoz14RYRmUUMT0jE49NY');
		$key .= '&';
		$key .= rawurlencode('QqtKpepPXqX3ZQ3SiF20AyXcyfgsdyxhEj1saQFpWk');

		$signature = base64_encode(hash_hmac('sha1', $base, $key, true));
		$signature = rawurlencode($signature);

		$oauth_header = '';
		$oauth_header .= 'oauth_consumer_key="E6O8k3hnG4DVuoyPyqxoUg", ';
		$oauth_header .= 'oauth_nonce="' . time() . '", ';
		$oauth_header .= 'oauth_signature="' . $signature . '", ';
		$oauth_header .= 'oauth_signature_method="HMAC-SHA1", ';
		$oauth_header .= 'oauth_timestamp="' . time() . '", ';
		$oauth_header .= 'oauth_token="1428999014-7yu5M3KAroiu6fwDgGyGoDNrFivRARgsChYLAOk", ';
		$oauth_header .= 'oauth_version="1.0", ';
		$curl_header = array("Authorization: Oauth {$oauth_header}", 'Expect:');

		$curl_request = curl_init();
		curl_setopt($curl_request, CURLOPT_HTTPHEADER, $curl_header);
		curl_setopt($curl_request, CURLOPT_HEADER, false);
		curl_setopt($curl_request, CURLOPT_URL, 'https://api.twitter.com/1.1/statuses/user_timeline.json');
		curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);
		$json = curl_exec($curl_request);

		curl_close($curl_request);


		$twitter_data = json_decode($json);

		for($i=0;$i<4;$i++){
			print "<p>".$twitter_data[$i]->text ."</p>";	
			print $twitter_data[$i]->created_at." <br>";
		}

	 ?>
            </div>
            <div class="window">
            <img src="img/logo-expirar.png" width="27" height="27" /><br>

		<?php
		
		echo "<h2>Ultimos Quiebres de Stock</h2>";
		
		$connect = mysql_connect("localhost","root","Ro:3n#A1");
		mysql_select_db("pedidos", $connect);
		if(mysql_errno($connect)){
			echo "Problemas en la conexion"."<br />";
		}
		else{
			$consultasql = "SELECT *
							FROM data
							WHERE estado = '2' OR estado = '2'
							ORDER BY fecha DESC
							LIMIT 0,10";

		//	$consultasql = "SELECT num, depto, otros FROM direcciones WHERE rs =" '.';
			$resultado = mysql_query($consultasql, $connect);
			if (!$resultado){
				echo "<b>Error de busquedaaa</b>";
				exit;
			}
			else{
				while($row = mysql_fetch_row($resultado)){
					echo "<br>Rut: ".$row[0]."<br>Fecha: ".$row[1]."<br>Estado: ".$row[10]."<br><br>"; 
				}
			}
		}
		mysql_close($connect);
		?>

            </div>
            <div class="window">
		<img src="img/logo-maps.png" width="27" height="27" /><br><br>
		<?php


		    $opcion = 0;
		    $hoy = date("m.d.y");
		    list($mes, $dia, $año) = split('[/.-]', $hoy);
		    $dia = $dia;

		    $connect = mysql_connect("localhost","root","Ro:3n#A1");
		    mysql_select_db("pedidos", $connect);

		    if(mysql_errno($connect)){

		      echo "Problemas en la conexion"."<br />";

		    }
		    else
		    {

		        $consultasql = "SELECT lat, lon, estado, fecha
		                            FROM data";

		        $resultado = mysql_query($consultasql, $connect);

		        if (!$resultado)
		        {
		            echo "<b>Error de busquedaaa</b>";
		            exit;
		        }

		        else
		        {
		            if($opcion!=3)
		            {
		                $dia = $dia-$opcion;
		                $fecha = "20".$año."-".$mes."-".$dia;
		            }
		            $i=0;
		            while($row = mysql_fetch_row($resultado))
		            {
		                if($opcion != 3)
		                {
		                    if(strcmp($row[3]."",$fecha)==0)
		                    {
		                        if($row[0] && $row[1])
		                        {
		                            if($row[0]!=0)
		                            {
		                                $lat[$i] = $row[0];
		                                $lng[$i] = $row[1];
		                                $estado[$i] = $row[2];
		                                $i = $i+1; 
		                            }
		                        }
		                    }
		                }
		                else
		                {
		                    if($row[0] && $row[1])
		                    {
		                        if($row[0]!=0)
		                        {
		                            $lat[$i] = $row[0];
		                            $lng[$i] = $row[1];
		                            $estado[$i] = $row[2];
		                            $i = $i+1; 
		                        }
		                    }
		                }
		            }
		            if($opcion != 3)
		            {
		                echo "Pedidos del día: ".$fecha;
		            }
		        }
		    }

		?>
		<script type="text/javascript">

		    function initialize() 
		    {
		        if (GBrowserIsCompatible()) 
		        {

		            var map = new GMap2(document.getElementById("map_canvas"));
		            map.setCenter(new GLatLng(-33.437778, -70.650278), 5);
		            map.setUIToDefault();

		                // Create our "tiny" marker icon
		            var blueIcon = new GIcon(G_DEFAULT_ICON);
		            var greenIcon = new GIcon(G_DEFAULT_ICON);
		            var redIcon = new GIcon(G_DEFAULT_ICON);
		            blueIcon.image = "http://gmaps-samples.googlecode.com/svn/trunk/markers/blue/blank.png";
		                greenIcon.image = "http://gmaps-samples.googlecode.com/svn/trunk/markers/green/blank.png";
		                redIcon.image = "http://gmaps-samples.googlecode.com/svn/trunk/markers/red/blank.png";

		            var latjs      = [];
		            var lngjs      = [];
		            var estadojs      = [];


		            <?php
		                $contador = 0;
		                for($j=0; $j < $i; $j++ )
		                {
		                    if($lat[$j]!="")
		                    {
		                        echo 'latjs['.$contador.'] = "'.$lat[$j].'";';
		                        echo 'lngjs['.$contador.'] = "'.$lng[$j].'";';
		                        echo 'estadojs['.$contador.'] = "'.$estado[$j].'";';
		                        $contador++;
		                    }
		                }
		            ?>

		            var largo  = '<?php echo $contador; ?>';

		            //document.writeln(estadojz[0]);
		            //document.writeln(lngjs[0]);
		            // Set up our GMarkerOptions object
		            markerOptions0 = { icon:blueIcon };
		            markerOptions1 = { icon:greenIcon };
		            markerOptions2 = { icon:redIcon };

		            var bounds = map.getBounds();
		            var southWest = bounds.getSouthWest();
		            var northEast = bounds.getNorthEast();
		            var lngSpan = northEast.lng() - southWest.lng();
		            var latSpan = northEast.lat() - southWest.lat();

		            for(var i=0;i<largo;i++)
		            {
		                if(estadojs[i] == 0) //Azul
		                {
		                    var point = new GLatLng(latjs[i],lngjs[i]);
		                    map.addOverlay(new GMarker(point, markerOptions0));
		                }
		                else if(estadojs[i] == 1) //Verde
		                {
		                    var point = new GLatLng(latjs[i],lngjs[i]);
		                    map.addOverlay(new GMarker(point, markerOptions1));
		                }
		                else if(estadojs[i] == 2) //Rojo
		                {
		                    var point = new GLatLng(latjs[i],lngjs[i]);
		                    map.addOverlay(new GMarker(point, markerOptions2));
		                }
		            }
		            map.addOverlay(new GMarker(point, markerOptions2));
		        }
		    }

		    </script>
            <div id="map_canvas" style="height: 500px;"></div>
		<IMG SRC="red.png" width="15" height="15"/><font size="1">Quiebre Stock.
		<IMG SRC="green.png" width="15" height="15"/>Entregado.
		<IMG SRC="blue.png" width="15" height="15"/>En Proceso. </font>
            </div>

	<div class="window">
		<img src="img/logo-graph.png" width="27" height="27" /><br><br>
            
		<?php
		include( 'GoogChart.class.php' );

		$connect = mysql_connect("localhost","root","Ro:3n#A1");
		mysql_select_db("pedidos", $connect);
		$mayo=0;
		$junio=0;

		if(mysql_errno($connect)){

			echo "Problemas en la conexion"."<br />";

		}
		else{
			$consultasql = "SELECT COUNT(*)
							FROM data
							WHERE fecha BETWEEN '2013-05-01' AND '2013-05-31'";
			$resultado = mysql_query($consultasql, $connect);

			if (!$resultado){
				echo "<b>Error de busquedaaa</b>";
				exit;
			}

			else{
				while($row = mysql_fetch_row($resultado)){
					$mayo = $row[0];
					$mayo = $mayo/100;
				}
			}

			$consultasql = "SELECT COUNT(*)
							FROM data
							WHERE fecha BETWEEN '2013-06-01' AND '2013-06-30'";

			$resultado = mysql_query($consultasql, $connect);

			if (!$resultado){
				echo "<b>Error de busquedaaa</b>";
				exit;
			}

			else{
				while($row = mysql_fetch_row($resultado)){
					$junio = $row[0];
					$junio = $junio/100;
				}
			}
		}

		$chart = new GoogChart( );

		$color = array( '#95b645', '#7498e9', '#999999',);

		//$dataMultiple = array( 'Año 2009' => array( XBox => 30, PS3 => 20, Wii => 45, Otros => 5, ), 'Año 2008' => array( XBox => 40, PS3 => 20, Wii => 30, Otros => 10, ), );
		$dataMultiple = array(  Mayo => $mayo, Junio => $junio,   );
		//$dataMultiple = array( 'Mayo' => array( Mayo => $mayo,0, ), 'Junio' => array( Junio => 0,$junio, ), );
		echo "Ventas";
		$chart->setChartAttrs( array( 'type' => 'bar-vertical', 'data' => $dataMultiple, 'size' => array( 300, 200 ), 'color' => $color, 'labelsXY' => true, ));

		echo $chart;
		
		echo "<br>- Mayo: ".$mayo*100;
		echo "</br>";
		echo "- Junio: ".$junio*100;


		?>

            </div>

	<div class="window">
		<img src="img/logo-excel.png" width="27" height="27" /><br><br>
		<div style="text-align:center">
		<a href="export.php"><img src="excel.png"></a><br>Descargar</div>
            </div>

            <!-- end #mainContent -->
		</div>
    <!-- end #container -->
    </div>
</body>
</html>
