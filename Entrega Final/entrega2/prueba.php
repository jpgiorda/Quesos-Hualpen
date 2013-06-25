<?php
include_once('vtwsclib/Vtiger/WSClient.php'); // Para acceder a librearía de VTiger

$rut = $_GET['rut'];
$direccionId = $_GET['direccionId'];

// Agregar a base de datos sql pedidos
$connect = mysql_connect("localhost","root","Ro:3n#A1");
mysql_select_db("direcciones_clientes", $connect);

if(mysql_errno($connect)){
    
    print "Problemas en la conexion"."<br />";
        
}

else{
    $consultasql = "SELECT direccion, num, depto, otros
                    FROM direcciones
                    WHERE rs = '".$rut."' AND id = '".$direccionId."'";
    
//  $consultasql = "SELECT num, depto, otros FROM direcciones WHERE rs =" '.';
                    

    $resultado = mysql_query($consultasql, $connect);
    
    
    if (!$resultado){
        print "<b>Error de busquedaaa</b>";
        exit;
    }
    
    else{

        while($row = mysql_fetch_row($resultado)){
            $direccion = $row[0];
            $num = $row[1];
            $dpto = $row[2];
            $otros = $row[3];
        }
        
        //coordenadas de la direccion

        echo iconv("ASCII", "UTF-8", $direccion);
        echo iconv("UTF-8", "ASCII", $direccion);
        $dirtrun = iconv("ISO-8859-1", "UTF-8", $direccion);
        echo iconv("ISO-8859-1", "ASCII", $direccion);
        echo iconv("ASCII", "ISO-8859-1", $direccion);
        echo iconv("UTF-8", "ISO-8859-1", $direccion);


        echo $direccion."echo".$dirtrun;

        $dirtru = str_replace("Ã±", "ñ", $dirtrun);

        echo $dirtru."echo".$dirtrun;

        $dur = $dirtru."%20".$num;
        $dir = str_replace(" ","%20", $dur);
        $url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$dir.",+Chile&sensor=false";
        //http://api.raventools.com/api?key=B1DFC59CA6EC76FF&method=domains&format=json";
        $ch = curl_init($url);
        //curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
        //curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
        $json = curl_exec($ch);
        if(!$json) {
            print curl_error($ch);
        }
        //print_r(json_decode($json));
        //$obj = json_decode($json);
        $array = json_decode($json,TRUE);

        $resu = $array["results"];

        //var_dump($array);
        $cero = $resu[0];
        $geom = $cero["geometry"];
        $loca = $geom["location"];
        $lat = "".$loca["lat"];
        $lng = "".$loca["lng"];
        //fin
        echo "lat: ".$lat."lng".$lng;
    }
}
?>
