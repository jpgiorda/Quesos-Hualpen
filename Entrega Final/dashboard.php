<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Documento sin título</title>
</head>

<body>
<table width="1024" height="527" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;
	<IMG SRC="twitter.png">
    <?php

	echo "</br>";
 	echo"<ul />";
	echo "<br /><li>Tweets: ";
	
	
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
	
	echo"<ul />";

	for($i=0;$i<4;$i++){
		echo "<br /><li>";
		$resultado = print_r($twitter_data[$i]->created_at);

		echo "<br />";
		
		$resultado = print_r($twitter_data[$i]->text);	
		
		echo "</li>";
	}
	
	echo "<ul>";


 ?>
    </td>
    <td>&nbsp;
	
		<?php
		
		echo"<ul />";
		echo "<br /><li>Estados de pedidos: (Pedidos que expiran pronto) ";
		
		$connect = mysql_connect("localhost","root","Ro:3n#A1");
		mysql_select_db("pedidos", $connect);

		if(mysql_errno($connect)){

			echo "Problemas en la conexion"."<br />";

		}

		else{

			$consultasql = "SELECT *
							FROM data
							WHERE estado = '0' OR estado = '2'
							ORDER BY fecha ASC
							LIMIT 0,10";

		//	$consultasql = "SELECT num, depto, otros FROM direcciones WHERE rs =" '.';



			$resultado = mysql_query($consultasql, $connect);


			if (!$resultado){
				echo "<b>Error de busquedaaa</b>";
				exit;
			}

			else{

				echo"<ul />";

				while($row = mysql_fetch_row($resultado)){
					echo "<br /><li>Rut: ".$row[0]."</li><li> Fecha: ".$row[1]."</li><li> Estado: ".$row[10]."</li><br />"; 
				}

				echo "<ul>";

			}

		}


		mysql_close($connect);



		?>
		
	
	</td>
    <td>&nbsp;
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
		echo '<h2>Quesos Hualpen</h2>'; $chart->setChartAttrs( array( 'type' => 'bar-vertical', 'title' => 'Ventas (x100): '.$fecha, 'data' => $dataMultiple, 'size' => array( 550, 300 ), 'color' => $color, 'labelsXY' => true, ));

		echo $chart;
		
		echo "Ventas en Mayo: ".$mayo*100;
		echo "</br>";
		echo "Ventas en Junio: ".$junio*100;


		?>
	</td>
  </tr>
  <tr>
    <td>&nbsp;
	<?php 
	echo"<ul />";
	echo "<br />Descargar Quiebres en Excel<br /><ul>"; ?>
	 <a href="export.php"><img src="excel.png"></a>
	</td>
    <td>&nbsp;
	 
	<IMG SRC="red.png">
		<?php echo ": Quiebre de Stock."; ?>
	</br>
	<IMG SRC="green.png">
		<?php echo ": Entregado."; ?>
	</br>
	<IMG SRC="blue.png">
		<?php echo ": En Proceso."; ?>
	</br>
	
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

		<!DOCTYPE html "-//W3C//DTD XHTML 1.0 Strict//EN"
		  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		  <head>
		    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
		    <title>Mapa</title>
		    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyADi1TXUM4WTLSpPv6ZASb1Yqb69UsT5rY&sensor=false"
		            type="text/javascript"></script>
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
		  </head>
		  <body onload="initialize()" onunload="GUnload()">
		    <div id="map_canvas" style="width: 300px; height: 800px;"></div>
		  </body>
		</html>
	
	</td>
    <td>&nbsp;
	 <?php echo "Cuadro 6"; ?>
	</td>
  </tr>
  <tr>
    <td>&nbsp;
	 <?php echo "Cuadro 7"; ?>
	</td>
    <td>&nbsp;
	 <?php echo "Cuadro 8"; ?>
	</td>
    <td>&nbsp;
	 <?php echo "Cuadro 9"; ?>
	</td>
  </tr>
</table>
</body>
</html>