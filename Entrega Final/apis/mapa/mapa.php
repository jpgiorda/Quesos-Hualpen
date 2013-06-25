<?php


    $opcion = $_GET['id'];
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