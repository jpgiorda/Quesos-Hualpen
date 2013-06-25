<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Gestion de Stock</title>
</head>

<body style="text-align:center">
<?

require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata_AuthSub');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Spreadsheets');
Zend_Loader::loadClass('Zend_Gdata_Docs');

$fun0 = "getSkuInfo";
$fun1 = "getStock";//params=sku
$fun2 = "getInfoBodegas";
$fun3 = "despacharStock";//params=almacenId,sku,units (deben ponerlos en ese orden separados por coma)
$fun4 = "moverStock";//params=from_almacenId,to_almacenId,sku,units (deben ponerlos en ese orden, separados por coma
$clave = "26JkBGs";

$pedido = $_GET["pedido"];
$sku = $_GET["sku"];
$idpedido = $_GET["id"];
$rs = $_GET["rs"];
$disp0 = 0;
$disp1 = 1;

$username = 'quesoshualpen'; // Your google account username
$password = 'integra3'; // Your google account password

$key = '0AhRzyWALVmYKdExSdW00UHVXRERXai13MmJfTnlQUEE';
$worksheetId ="od6";
$reserva = 0;


try 
{
	// connect to API
	$service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
	$client = Zend_Gdata_ClientLogin::getHttpClient($username, $password, $service);
	$spreadSheetService = new Zend_Gdata_Spreadsheets($client);

	$query = new Zend_Gdata_Spreadsheets_DocumentQuery();
	$query->setSpreadsheetKey($key);
	$feed = $spreadSheetService->getWorksheetFeed($query);
	$entries = $feed->entries[0]->getContentsAsRows();
	$cantador = 1;
	echo $sku;

	foreach ( $entries as $tupla){
		$cantador = $cantador+1;
		$skug = $tupla['sku'];
		$skug = $skug + 0;
		$sku = $sku + 0;

		if( $skug == $sku){
			$reserva = $tupla['reserva'];
			$contador = $cantador;
			echo $contador;
			echo $reserva;
			break;
		}	
	}

	$reservai = 0;
	if($rs=="34.242.924-1")
	{
		$reservai = $reserva;
		echo $reserva." aja ".$reservai;
		$reserva = 0;
	}

	$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun2."&key=".$clave;;
	$ch = curl_init($url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
	$json = curl_exec($ch);
	if(!$json) {
	    echo curl_error($ch);
	}
	$array = json_decode($json,TRUE);

	$capacidadBodega = 0;
	foreach ($array as $disponible) {
		if(strcmp($disponible[almacenId],"102") == 0)
		{
			$capacidadBodega = $disponible[capacidad];
		}
	}
	echo "capacidad: ".$capacidadBodega;

	$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun1."&key=".$clave."&params=".$sku;
	$ch = curl_init($url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);

	$json = curl_exec($ch);
	if(!$json) {
	    echo curl_error($ch);
	}

	$array = json_decode($json,TRUE);
	foreach ($array as $disponible) {
		if($disponible[almacenId] == 102)
		{
			$disp0 = $disponible[libre];
			$disp0 = $disp0 + 0;
			echo "almacen 102 disp".$disp0;
		}
		elseif($disponible[almacenId] == 55)
		{
			$disp1 = $disponible[libre];
			$disp1 = $disp1 + 0;
			echo "almacen 55 disp".$disp1;
		}
		elseif($disponible[almacenId] == 45) //Recepcion
		{
			$disp2 = $disponible[libre];
			$disp2 = $disp2 + 0;
			echo "almacen 45 disp".$disp2;
		}
		elseif($disponible[almacenId] == 59) //Devoluciones
		{
			$disp3 = $disponible[libre];
			$disp3 = $disp3 + 0;
			echo "almacen 59 disp".$disp3;
		}
		elseif($disponible[almacenId] == 58) //Dañados
		{
			$disp4 = $disponible[libre];
			$disp4 = $disp4 + 0;
			echo "almacen 58 disp".$disp4;
		}
	}

	//Revisar temperatura
	$temperatura = 0;
	$temp_min = 0;
	$temp_max = 0;

	$contabilidad = 0;

	$connect = mysql_connect("localhost","root","Ro:3n#A1");
	mysql_select_db("pedidos", $connect);
	if(mysql_errno($connect))
	{
		echo "Problemas en la conexion"."<br />";
	}
    else
    {
        echo "id Pedido: ".$idpedido;
        $consultasql = "SELECT lat, lon FROM data WHERE id=".$idpedido;
        $resultado = mysql_query($consultasql, $connect);
        if (!$resultado)
        {
            echo "<b>Error de busquedaaa CLIMA</b>";
            exit;
        }
        else
        {
            $row = mysql_fetch_row($resultado);
            $latitud = $row[0];
            $longitud = $row[1];
            $lati = trim($latitud);
            $long = trim($longitud);
            echo "latitud ".$lati." longitud ".$long." ";
			$url1 = "http://api.openweathermap.org/data/2.1/find/station?lat=".$lati."&lon=".$long."&cnt=1";
			
			//http://api.raventools.com/api?key=B1DFC59CA6EC76FF&method=domains&format=json";
			$ch1 = curl_init($url1);
			//curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch1,CURLOPT_RETURNTRANSFER,TRUE);
			//curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
			$json1 = curl_exec($ch1);
			if(!$json1) {
			    echo curl_error($ch1);
			}
			//print_r(json_decode($json));
			//$obj = json_decode($json);
			$array1 = json_decode($json1,TRUE);
			//print_r($obj);
			//print_r($array);
			$list1 = $array1['list'];
			$cero1 = $list1[0];
			$main1 = $cero1['main'];
			$temp1 = $main1['temp'];
			$temperatura= $temp1 - 273.15;
			echo "t: ".$temperatura;
        }

        $url2 = "iic3103.ing.puc.cl/webservice/integra3/?function=getSkuInfo&key=".$clave;
	    $ch2 = curl_init($url2);
	    curl_setopt($ch2,CURLOPT_RETURNTRANSFER,TRUE);
	    $json2 = curl_exec($ch2);
	    if(!$json2) {
	        echo curl_error($ch2);
	    }
	    $array2 = json_decode($json2,TRUE);

	    foreach ($array2 as $queso) {
	        if($queso[sku] == $sku)
	        {
	            $temp_min = $queso[min_temp];
	            echo "t min: ".$temp_min;
	            $temp_max = $queso[max_temp];
	            echo "t max: ".$temp_max;
	        }
	    }
    }

	//FIN

	echo "Pedido :".$pedido;
	$total = $disp0+$disp1+$disp2+$disp3+$disp4;
	echo "Disponible: ".$total;
	echo "Reserva: ".$reserva;

	if($disp0 - $reserva >= $pedido)
	{
		if($temperatura < $temp_min || $temperatura > $temp_max)
		{
			echo "No Temperatura optima";
			$_GRABAR_SQL = "UPDATE  data SET  estado =  '2' WHERE  data.id = ".$idpedido;
			mysql_query($_GRABAR_SQL, $connect);

			$m = new MongoClient(); // connect
			$db = $m->selectDB("documentacion");
			$log = $db->selectCollection("reportes");
			$log->insert(array("id" => $idpedido, "estado" => "Quiebre de Stock", "info" => "Temperatura"));
		}
		else
		{
			echo "primera bodega";
			$param = "102,".$sku.",".$pedido;//params=almacenId,sku,units
			$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun3."&key=".$clave."&params=".$param;

			$ch = curl_init($url);

			curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);

			$json = curl_exec($ch);
			if(!$json) {
		   		echo curl_error($ch);
			}

			$array = json_decode($json,TRUE);
			echo "ehhh ".$array;

			$_GRABAR_SQL = "UPDATE  data SET  estado =  '1' WHERE  data.id = ".$idpedido;
			mysql_query($_GRABAR_SQL, $connect);

			$m = new MongoClient(); // connect
			$db = $m->selectDB("documentacion");
			$log = $db->selectCollection("reportes");
			$log->insert(array("id" => $idpedido, "estado" => "Realizado"));

			$contabilidad = 1;

			if($rs=="34.242.924-1")
			{
				// update cell at row 6, column 5
				$queda = 0;
				if($pedido<$reservai)
				{
					$queda = $reservai - $pedido;
					$entry = $spreadSheetService->updateCell($contador, '2', $queda, $key, $worksheetId);
				}
				else
				{
					echo "si";
					$entry = $spreadSheetService->updateCell($contador, '2', '0.0', $key, $worksheetId);
				}
			}
		}

	}
	elseif($disp0+$disp1 - $reserva >= $pedido)
	{
		if($temperatura < $temp_min || $temperatura > $temp_max)
		{
			echo "No Temperatura optima";
			$_GRABAR_SQL = "UPDATE  data SET  estado =  '2' WHERE  data.id = ".$idpedido;
			mysql_query($_GRABAR_SQL, $connect);

			$m = new MongoClient(); // connect
			$db = $m->selectDB("documentacion");
			$log = $db->selectCollection("reportes");
			$log->insert(array("id" => $idpedido, "estado" => "Quiebre de Stock", "info" => "Temperatura"));
		}
		else
		{
			echo "segunda bodega";

			echo "capacidad ".$capacidadBodega;

			if($disp0 - $reserva + $capacidadBodega >= $pedido)
			{
				echo " cabe";
				$unit = $pedido - $disp0 + $reserva; //se pasa lo justo y necesario para hacer el pedido
				echo "units ".$unit;
				$param = "55,102,".$sku.",".$unit;
				$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun4."&key=".$clave."&params=".$param;

				$ch = curl_init($url);

				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);

				$json = curl_exec($ch);
				if(!$json) {
			   		echo curl_error($ch);
				}

				$array = json_decode($json,TRUE);
				echo "se pasaron ".$unit;

				$param = "102,".$sku.",".$pedido;//params=almacenId,sku,units
				$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun3."&key=".$clave."&params=".$param;

				$ch = curl_init($url);

				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);

				$json = curl_exec($ch);
				if(!$json) {
			   		echo curl_error($ch);
				}

				$array = json_decode($json,TRUE);
				echo "ehhh 2".$array;

				$_GRABAR_SQL = "UPDATE  data SET  estado =  '1' WHERE  data.id = ".$idpedido;
				mysql_query($_GRABAR_SQL, $connect);

				$m = new MongoClient(); // connect
				$db = $m->selectDB("documentacion");
				$log = $db->selectCollection("reportes");
				$log->insert(array("id" => $idpedido, "estado" => "Realizado"));

				$contabilidad = 1;

				if($rs=="34.242.924-1")
				{
					// update cell at row 6, column 5
					$queda = 0;
					echo $reservai." ".$pedido;
					if($pedido<$reservai)
					{
						$queda = $reservai - $pedido;
						echo $contador;
						$entry = $spreadSheetService->updateCell($contador, '2', $queda, $key, $worksheetId);
					}
					else
					{
						echo "si ".$contador;
						$entry = $spreadSheetService->updateCell($contador, '2', '0.0', $key, $worksheetId);
					}
				}
			}
			else
			{
				echo "No cabe en Bodega";
				$_GRABAR_SQL = "UPDATE  data SET  estado =  '2' WHERE  data.id = ".$idpedido;
				mysql_query($_GRABAR_SQL, $connect);

				$m = new MongoClient(); // connect
				$db = $m->selectDB("documentacion");
				$log = $db->selectCollection("reportes");
				$log->insert(array("id" => $idpedido, "estado" => "Quiebre de Stock", "info" => "No cabe en bodega de despacho"));
			}
		}
	}
	elseif($disp0+$disp1+$disp2 - $reserva >= $pedido)
	{
		if($temperatura < $temp_min || $temperatura > $temp_max)
		{
			echo "No Temperatura optima";
			$_GRABAR_SQL = "UPDATE  data SET  estado =  '2' WHERE  data.id = ".$idpedido;
			mysql_query($_GRABAR_SQL, $connect);

			$m = new MongoClient(); // connect
			$db = $m->selectDB("documentacion");
			$log = $db->selectCollection("reportes");
			$log->insert(array("id" => $idpedido, "estado" => "Quiebre de Stock", "info" => "Temperatura"));
		}
		else
		{
			echo "tercera bodega";
			echo "capacidad ".$capacidadBodega;

			if($disp0 - $reserva + $capacidadBodega >= $pedido)
			{
				echo " cabe";

				//Transpaso 1
				$unit = $disp1; //se pasa lo justo y necesario para hacer el pedido
				echo "units ".$unit;
				$param = "55,102,".$sku.",".$unit;
				$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun4."&key=".$clave."&params=".$param;
				$ch = curl_init($url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
				$json = curl_exec($ch);
				if(!$json) {
			   		echo curl_error($ch);
				}
				$array = json_decode($json,TRUE);
				echo "se pasaron ".$unit;

				//Transpaso 2
				$unit = $pedido - $disp0 - $disp1 + $reserva; //se pasa lo justo y necesario para hacer el pedido
				echo "units ".$unit;
				$param = "45,102,".$sku.",".$unit;
				$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun4."&key=".$clave."&params=".$param;
				$ch = curl_init($url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
				$json = curl_exec($ch);
				if(!$json) {
			   		echo curl_error($ch);
				}
				$array = json_decode($json,TRUE);
				echo "se pasaron ".$unit;

				$param = "102,".$sku.",".$pedido;//params=almacenId,sku,units
				$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun3."&key=".$clave."&params=".$param;

				$ch = curl_init($url);

				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);

				$json = curl_exec($ch);
				if(!$json) {
			   		echo curl_error($ch);
				}

				$array = json_decode($json,TRUE);
				echo "ehhh 2".$array;

				$_GRABAR_SQL = "UPDATE  data SET  estado =  '1' WHERE  data.id = ".$idpedido;
				mysql_query($_GRABAR_SQL, $connect);

				$m = new MongoClient(); // connect
				$db = $m->selectDB("documentacion");
				$log = $db->selectCollection("reportes");
				$log->insert(array("id" => $idpedido, "estado" => "Realizado"));

				$contabilidad = 1;

				if($rs=="34.242.924-1")
				{
					// update cell at row 6, column 5
					$queda = 0;
					echo $reservai." ".$pedido;
					if($pedido<$reservai)
					{
						$queda = $reservai - $pedido;
						$entry = $spreadSheetService->updateCell($contador, '2', $queda, $key, $worksheetId);
					}
					else
					{
						echo "si";
						$queda = '0';
						$entry = $spreadSheetService->updateCell($contador, '2', '0.0', $key, $worksheetId);
					}
				}
			}
			else
			{
				echo "No cabe en Bodega";
				$_GRABAR_SQL = "UPDATE  data SET  estado =  '2' WHERE  data.id = ".$idpedido;
				mysql_query($_GRABAR_SQL, $connect);

				$m = new MongoClient(); // connect
				$db = $m->selectDB("documentacion");
				$log = $db->selectCollection("reportes");
				$log->insert(array("id" => $idpedido, "estado" => "Quiebre de Stock", "info" => "No cabe en bodega de despacho"));
			}
		}
	}
	elseif($disp0+$disp1+$disp2+$disp3 - $reserva >= $pedido)
	{
		if($temperatura < $temp_min || $temperatura > $temp_max)
		{
			echo "No Temperatura optima";
			$_GRABAR_SQL = "UPDATE  data SET  estado =  '2' WHERE  data.id = ".$idpedido;
			mysql_query($_GRABAR_SQL, $connect);

			$m = new MongoClient(); // connect
			$db = $m->selectDB("documentacion");
			$log = $db->selectCollection("reportes");
			$log->insert(array("id" => $idpedido, "estado" => "Quiebre de Stock", "info" => "Temperatura"));
		}
		else
		{
			echo "cuarta bodega";
			echo "capacidad ".$capacidadBodega;

			if($disp0 - $reserva + $capacidadBodega >= $pedido)
			{
				echo " cabe";

				//Transpaso 1
				$unit = $disp1; //se pasa lo justo y necesario para hacer el pedido
				echo "units ".$unit;
				$param = "55,102,".$sku.",".$unit;
				$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun4."&key=".$clave."&params=".$param;
				$ch = curl_init($url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
				$json = curl_exec($ch);
				if(!$json) {
			   		echo curl_error($ch);
				}
				$array = json_decode($json,TRUE);
				echo "se pasaron ".$unit;

				//Transpaso 2
				$unit = $disp2; //se pasa lo justo y necesario para hacer el pedido
				echo "units ".$unit;
				$param = "45,102,".$sku.",".$unit;
				$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun4."&key=".$clave."&params=".$param;
				$ch = curl_init($url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
				$json = curl_exec($ch);
				if(!$json) {
			   		echo curl_error($ch);
				}
				$array = json_decode($json,TRUE);
				echo "se pasaron ".$unit;

				//Transpaso 3
				$unit = $pedido - $disp0 -$disp1 - $disp2 + $reserva; //se pasa lo justo y necesario para hacer el pedido
				echo "units ".$unit;
				$param = "59,102,".$sku.",".$unit;
				$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun4."&key=".$clave."&params=".$param;
				$ch = curl_init($url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
				$json = curl_exec($ch);
				if(!$json) {
			   		echo curl_error($ch);
				}
				$array = json_decode($json,TRUE);
				echo "se pasaron ".$unit;

				$param = "102,".$sku.",".$pedido;//params=almacenId,sku,units
				$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun3."&key=".$clave."&params=".$param;

				$ch = curl_init($url);

				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);

				$json = curl_exec($ch);
				if(!$json) {
			   		echo curl_error($ch);
				}

				$array = json_decode($json,TRUE);
				echo "ehhh 2".$array;

				$_GRABAR_SQL = "UPDATE  data SET  estado =  '1' WHERE  data.id = ".$idpedido;
				mysql_query($_GRABAR_SQL, $connect);

				$m = new MongoClient(); // connect
				$db = $m->selectDB("documentacion");
				$log = $db->selectCollection("reportes");
				$log->insert(array("id" => $idpedido, "estado" => "Realizado"));

				$contabilidad = 1;

				if($rs=="34.242.924-1")
				{
					// update cell at row 6, column 5
					$queda = 0;
					echo $reservai." ".$pedido;
					if($pedido<$reservai)
					{
						$queda = $reservai - $pedido;
						$entry = $spreadSheetService->updateCell($contador, '2', $queda, $key, $worksheetId);
					}
					else
					{
						echo "si";
						$queda = '0';
						$entry = $spreadSheetService->updateCell($contador, '2', '0.0', $key, $worksheetId);
					}
				}
			}
			else
			{
				echo "No cabe en Bodega";
				$_GRABAR_SQL = "UPDATE  data SET  estado =  '2' WHERE  data.id = ".$idpedido;
				mysql_query($_GRABAR_SQL, $connect);

				$m = new MongoClient(); // connect
				$db = $m->selectDB("documentacion");
				$log = $db->selectCollection("reportes");
				$log->insert(array("id" => $idpedido, "estado" => "Quiebre de Stock", "info" => "No cabe en bodega de despacho"));
			}
		}
	}
	elseif($disp0+$disp1+$disp2+$disp3+$disp4 - $reserva >= $pedido)
	{
		if($temperatura < $temp_min || $temperatura > $temp_max)
		{
			echo "No Temperatura optima";
			$_GRABAR_SQL = "UPDATE  data SET  estado =  '2' WHERE  data.id = ".$idpedido;
			mysql_query($_GRABAR_SQL, $connect);

			$m = new MongoClient(); // connect
			$db = $m->selectDB("documentacion");
			$log = $db->selectCollection("reportes");
			$log->insert(array("id" => $idpedido, "estado" => "Quiebre de Stock", "info" => "Temperatura"));
		}
		else
		{
			echo "quinta bodega";
			echo "capacidad ".$capacidadBodega;

			if($disp0 - $reserva + $capacidadBodega >= $pedido)
			{
				echo " cabe";

				//Transpaso 1
				$unit = $disp1; //se pasa lo justo y necesario para hacer el pedido
				echo "units ".$unit;
				$param = "55,102,".$sku.",".$unit;
				$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun4."&key=".$clave."&params=".$param;
				$ch = curl_init($url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
				$json = curl_exec($ch);
				if(!$json) {
			   		echo curl_error($ch);
				}
				$array = json_decode($json,TRUE);
				echo "se pasaron ".$unit;

				//Transpaso 2
				$unit = $disp2; //se pasa lo justo y necesario para hacer el pedido
				echo "units ".$unit;
				$param = "45,102,".$sku.",".$unit;
				$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun4."&key=".$clave."&params=".$param;
				$ch = curl_init($url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
				$json = curl_exec($ch);
				if(!$json) {
			   		echo curl_error($ch);
				}
				$array = json_decode($json,TRUE);
				echo "se pasaron ".$unit;

				//Transpaso 3
				$unit = $disp3; //se pasa lo justo y necesario para hacer el pedido
				echo "units ".$unit;
				$param = "59,102,".$sku.",".$unit;
				$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun4."&key=".$clave."&params=".$param;
				$ch = curl_init($url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
				$json = curl_exec($ch);
				if(!$json) {
			   		echo curl_error($ch);
				}
				$array = json_decode($json,TRUE);
				echo "se pasaron ".$unit;

				//Transpaso 4
				$unit = $pedido - $disp0 - $disp1 - $disp2 - $disp3 + $reserva; //se pasa lo justo y necesario para hacer el pedido
				echo "units ".$unit;
				$param = "58,102,".$sku.",".$unit;
				$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun4."&key=".$clave."&params=".$param;
				$ch = curl_init($url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
				$json = curl_exec($ch);
				if(!$json) {
			   		echo curl_error($ch);
				}
				$array = json_decode($json,TRUE);
				echo "se pasaron ".$unit;

				$param = "102,".$sku.",".$pedido;//params=almacenId,sku,units
				$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun3."&key=".$clave."&params=".$param;

				$ch = curl_init($url);

				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);

				$json = curl_exec($ch);
				if(!$json) {
			   		echo curl_error($ch);
				}

				$array = json_decode($json,TRUE);
				echo "ehhh 2".$array;

				$_GRABAR_SQL = "UPDATE  data SET  estado =  '1' WHERE  data.id = ".$idpedido;
				mysql_query($_GRABAR_SQL, $connect);

				$m = new MongoClient(); // connect
				$db = $m->selectDB("documentacion");
				$log = $db->selectCollection("reportes");
				$log->insert(array("id" => $idpedido, "estado" => "Realizado"));

				$contabilidad = 1;

				if($rs=="34.242.924-1")
				{
					// update cell at row 6, column 5
					$queda = 0;
					echo $reservai." ".$pedido;
					if($pedido<$reservai)
					{
						$queda = $reservai - $pedido;
						$entry = $spreadSheetService->updateCell($contador, '2', $queda, $key, $worksheetId);
					}
					else
					{
						echo "si";
						$queda = '0';
						$entry = $spreadSheetService->updateCell($contador, '2', '0.0', $key, $worksheetId);
					}
				}
			}
			else
			{
				echo "No cabe en Bodega";
				$_GRABAR_SQL = "UPDATE  data SET  estado =  '2' WHERE  data.id = ".$idpedido;
				mysql_query($_GRABAR_SQL, $connect);

				$m = new MongoClient(); // connect
				$db = $m->selectDB("documentacion");
				$log = $db->selectCollection("reportes");
				$log->insert(array("id" => $idpedido, "estado" => "Quiebre de Stock", "info" => "No cabe en bodega de depacho"));
			}
		}
	}
	else
	{
		echo "no se puede";//Ojo ver si se puede sacar desde otra bodega
		$_GRABAR_SQL = "UPDATE  data SET  estado =  '2' WHERE  data.id = ".$idpedido;
		mysql_query($_GRABAR_SQL, $connect);

		$m = new MongoClient(); // connect
		$db = $m->selectDB("documentacion");
		$log = $db->selectCollection("reportes");
		$log->insert(array("id" => $idpedido, "estado" => "Quiebre de Stock", "info" => "No hay suficiente Stock"));
	}
	mysql_close($connect);
	curl_close($ch);

	if($contabilidad==1)
	{
		$url = "Location: facturar.php?";
		$url.='id='.$idpedido.'&';
		$url.='numero='.$pedido;

		header($url);
		exit;
	}
}
catch (Exception $e) 
{
    die('ERROR: ' . $e->getMessage());
}
?>
</body>
</html>