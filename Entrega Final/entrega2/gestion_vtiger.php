<?php
include_once('vtwsclib/Vtiger/WSClient.php');
$url = 'http://integra3.ing.puc.cl/entrega1/vtigercrm';
$client = new Vtiger_WSClient($url);
$login = $client->doLogin('admin','ClKOZEeSxd8CtDWe');
if(!$login) echo 'Login Failed';
else {
	$module = 'SalesOrder';
	
	$record = $client->doCreate($module, Array(
		'subject'=>'aaaa',
		'sostatus' => 'Cancellex', //Created, Delivered, Cancelled
		'cf_650' => 1, //Sku
		'cf_656' => 2, //Cantidad
		'cf_658' => 'c', //Direccion
		'cf_666' => 'd', //Rut
		'cf_667' => 'e', //Fecha
		));
	
	if($record) {
		echo 'doing..';
		$recordid = $client->getRecordId($record['id']);
		echo 'doing..';
	}
	
	$error = $client->lasterror();
	    if($error) {
	    echo $error['code'] . ' : ' . $error['message'];
	}

	
	
}
?>