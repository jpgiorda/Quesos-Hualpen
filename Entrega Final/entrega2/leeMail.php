<?php
include_once('vtwsclib/Vtiger/WSClient.php'); // Para acceder a librearía de VTiger

$rut = $_GET['rut'];
$direccionId = $_GET['direccionId'];
$fecha = $_GET['fecha'];
$sku = $_GET['sku'];
$cantidad_unidad = $_GET['cantidad_unidad'];
$cantidad = $_GET['cantidad'];

// Agregar a VTiger
include_once('vtwsclib/Vtiger/WSClient.php');
$url = 'http://integra3.ing.puc.cl/entrega1/vtigercrm';
$client = new Vtiger_WSClient($url);
$login = $client->doLogin('admin','ClKOZEeSxd8CtDWe');
if(!$login) print 'Login Failed';
else {
	$module = 'SalesOrder';
	
	$record = $client->doCreate($module, Array(
		'subject'=>'Pedido',
		'sostatus' => 'Created', //Created, Delivered, Cancelled
		'cf_650' => $sku, //Sku
		'cf_656' => $cantidad, //Cantidad
		'cf_658' => $direccionId, //Direccion
		'cf_666' => $rut, //Rut
		'cf_667' => $fecha, //Fecha
		));
	
	if($record) {
		$recordid = $client->getRecordId($record['id']);
	}
	
	$error = $client->lasterror();
	    if($error) {
	    print $error['code'] . ' : ' . $error['message'];
	}
}

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
	
//	$consultasql = "SELECT num, depto, otros FROM direcciones WHERE rs =" '.';
					

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
		
		$connect = mysql_connect("localhost","root","Ro:3n#A1");
		mysql_select_db("pedidos", $connect);

		$consultasql = "SELECT MAX(id) as ID from data";

		$resultado = mysql_query($consultasql, $connect);

		$row = mysql_fetch_row($resultado);
		$max = $row[0];
		$id = $max +1;
		
		//coordenadas de la direccion

        $dirtrun = iconv("ISO-8859-1", "UTF-8", $direccion);
        $dirtru = str_replace("Ã±", "ñ", $dirtrun);

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

		$connect = mysql_connect("localhost","root","Ro:3n#A1");
		mysql_select_db("pedidos", $connect);
		
		if(mysql_errno($connect)){

			print "Problemas en la conexion"."<br />";

		}

		else{
			$_GRABAR_SQL = "INSERT INTO data (rs, fecha, sku, cantidad, cantidad_unidad, direccion, numero, dpto, otros, id, lat, lon) VALUES ('$rut','$fecha','$sku','$cantidad', '$cantidad_unidad', '$direccion','$num','$dpto','$otros', '$id', '$lat', '$lng')";
			mysql_query($_GRABAR_SQL, $connect);

			mysql_close($connect);

			$m = new MongoClient(); // connect
			$db = $m->selectDB("documentacion");
			$log = $db->selectCollection("pedidos");
			$log->insert(array("id" => $id, "rs" => $rut, "fecha" => $fecha, "sku" => $sku, "cantidad" => $cantidad, "cantidad_unidad" => $cantidad_unidad, "direccion" => $dirtru, "numero" => $num));
			
		}
	}
		
}

$url = "Location: gestiondestock.php?";
$url.='pedido='.$cantidad.'&';
$url.='sku='.$sku.'&';
$url.='id='.$id.'&';
$url.='rs='.$rut;

header($url);
exit;



?>