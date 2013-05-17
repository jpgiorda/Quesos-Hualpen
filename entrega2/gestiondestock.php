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
$param = "30080341";

$pedido = $_GET["pedido"];
$sku = $_GET["sku"];
$idpedido = $_GET["id"];
$rs = $_GET["rs"];
$disp0 = 0;
$disp1 = 1;

if($rs=="34.242.924-1")
{
	$reserva = 0;
}
else
{
	$username = 'quesoshualpen'; // Your google account username
	$password = 'integra3'; // Your google account password

	$key = '0AhRzyWALVmYKdHZSTk1hUmpvR1JjRGFjX0ZZQjhwLVE';
	$worksheetId ="od6";

	$service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
	$client = Zend_Gdata_ClientLogin::getHttpClient($username, $password, $service);
	$spreadSheetService = new Zend_Gdata_Spreadsheets($client);

	$query = new Zend_Gdata_Spreadsheets_DocumentQuery();
	$query->setSpreadsheetKey($key);
	$feed = $spreadSheetService->getWorksheetFeed($query);
	$entries = $feed->entries[0]->getContentsAsRows();


	foreach ( $entries as $tupla){
		$skug = $tupla['sku'];
		if($skug == $sku){
			$reserva = $tupla['reserva'];
		}	
	}
}

$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun1."&key=".$clave."&params=".$param;

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
		echo "almacen 102 disp".$disp0;
	}
	elseif($disponible[almacenId] == 55)
	{
		$disp1 = $disponible[libre];
		echo "almacen 55 disp".$disp1;
	}
}

$connect = mysql_connect("localhost","root","Ro:3n#A1");
mysql_select_db("pedidos", $connect);

if(mysql_errno($connect))
{
	echo "Problemas en la conexion"."<br />";
}

if($disp0 - $reservas >= $pedido)
{
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

}
elseif($disp0+$disp1 - $reservas >= $pedido)
{
	$url = "iic3103.ing.puc.cl/webservice/integra3/?function=".$fun2."&key=".$clave."&params=".$param;

	$ch = curl_init($url);

	curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);

	$json = curl_exec($ch);
	if(!$json) {
   		echo curl_error($ch);
	}

	$array = json_decode($json,TRUE);
	$capacidadBodega = 0;
	foreach ($array as $bodega) {
		if($bodega[almacenId] == 55)
			{
				$capacidadBodega = $bodega[capacidad];
			}
	}

	echo "capacidad ".$capacidadBodega;

	if($disp0 - $reservas + $capacidadBodega >= $pedido)
	{
		$unit = $pedido - $disp0 + $reservas; //se pasa lo justo y necesario para hacer el pedido
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
	}
	else
	{
		echo "No cabe en Bodega";
		$_GRABAR_SQL = "UPDATE  data SET  estado =  '2' WHERE  data.id = ".$idpedido;
		mysql_query($_GRABAR_SQL, $connect);
	}
}
else
{
	echo "no se puede";//Ojo ver si se puede sacar desde otra bodega
	$_GRABAR_SQL = "UPDATE  data SET  estado =  '2' WHERE  data.id = ".$idpedido;
	mysql_query($_GRABAR_SQL, $connect);
}
mysql_close($connect);
curl_close($ch);
?>
</body>
</html>