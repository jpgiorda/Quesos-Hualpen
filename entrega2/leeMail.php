<?php

$rut = $_GET['rut'];
$direccionId = $_GET['direccionId'];
$fecha = $_GET['fecha'];
$sku = $_GET['sku'];
$cantidad_unidad = $_GET['cantidad_unidad'];
$cantidad = $_GET['cantidad'];


$connect = mysql_connect("localhost","root","Ro:3n#A1");
mysql_select_db("direcciones_clientes", $connect);

if(mysql_errno($connect)){
	
	echo "Problemas en la conexion"."<br />";
		
}

else{
	
	$consultasql = "SELECT direccion, num, depto, otros
					FROM direcciones
					WHERE rs = '".$rut."' AND id = '".$direccionId."'";
	
//	$consultasql = "SELECT num, depto, otros FROM direcciones WHERE rs =" '.';
					

					
	$resultado = mysql_query($consultasql, $connect);
	
	
	if (!$resultado){
		echo "<b>Error de busquedaaa</b>";
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
		
		$connect = mysql_connect("localhost","root","Ro:3n#A1");
		mysql_select_db("pedidos", $connect);
		
		if(mysql_errno($connect)){

			echo "Problemas en la conexion"."<br />";

		}

		else{
		$_GRABAR_SQL = "INSERT INTO data (rs, fecha, sku, cantidad, cantidad_unidad, direccion, numero, dpto, otros, id) VALUES ('$rut','$fecha','$sku','$cantidad', '$cantidad_unidad', '$direccion','$num','$dpto','$otros', '$id')";
		
		mysql_query($_GRABAR_SQL, $connect);
		}
	}
		
}

mysql_close($connect);

$pedido = $cantidad;
$idpedido = $id;

$url = "Location: gestiondestock.php?";
$url.='pedido='.$pedido.'&';
$url.='sku='.$sku.'&';
$url.='id='.$idpedido.'&';
$url.='rs='.$rut;

header($url);
exit;



?>