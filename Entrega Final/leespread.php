<?php
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata_AuthSub');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Spreadsheets');
Zend_Loader::loadClass('Zend_Gdata_Docs');
 

 
//-------------------------------------------------------------------------------
// Google user account
 
$username = 'quesoshualpen'; // Your google account username
$password = 'integra3'; // Your google account password
 
//-------------------------------------------------------------------------------
// Document key - get it from browser addres bar query key for your open spreadsheet
 
$key = '0AhRzyWALVmYKdHZSTk1hUmpvR1JjRGFjX0ZZQjhwLVE';
$worksheetId ="od6";
//---------------------------------------------------------------------------------
// Init Zend Gdata service
 
$service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
$client = Zend_Gdata_ClientLogin::getHttpClient($username, $password, $service);
$spreadSheetService = new Zend_Gdata_Spreadsheets($client);
 
//--------------------------------------------------------------------------------
// Example 1: Get cell data
 
$query = new Zend_Gdata_Spreadsheets_DocumentQuery();
$query->setSpreadsheetKey($key);
$feed = $spreadSheetService->getWorksheetFeed($query);
$entries = $feed->entries[0]->getContentsAsRows();


foreach ( $entries as $tupla){
	$skug = $tupla['sku'];
	if($skug == "30001025"){
		$reserva = $tupla['reserva'];
		echo $reserva;
	}
	
	
	
}


 

 
?>