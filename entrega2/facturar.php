<?php

	echo "hola";
	print_r(PDO::getAvailableDrivers());	
	putenv('ODBCSYSINI=/usr/local/etc'); 
    putenv('ODBCINI=/usr/local/etc/odbc.ini'); 
    $username = ""; 
    $password = ""; 
    try { 
      $dbh = new PDO("odbc:MSSQLServer", 
                    "$username", 
                    "$password" 
                   ); 
    } catch (PDOException $exception) { 
      echo $exception->getMessage(); 
      exit; 
    } 
    echo var_dump($dbh); 
    unset($dbh); 

//	$connection = odbc_connect("Driver={Microsoft Access Driver (*.mdb)};Dbq=\var\www\entrega1\accessdb\pricing.accdb");

//	echo "0";
//	$db_connection = new COM("ADODB.Connection", NULL, 1251);

//	echo "1";
//	$db_connstr = "DRIVER={Microsoft Access Driver (*.accdb)}; DBQ=\var\www\entrega1\accessdb\pricing.accdb;DefaultDir=\var\www";
//	echo "2";
//	$db_connection->open($db_connstr);
//	echo "3";
//	echo $db_connection;
//	echo "desp";
	//$rs = $db_connection->execute("SELECT EmpNameLocal, EmpPosLocal FROM tbl_Employee WHERE ID='$IDNo'");
	//$rs_fld0 = $rs->Fields(0);
	//$rs_fld1 = $rs->Fields(1);
	//while (!$rs->EOF) {
	//$empNameLoc = $rs_fld0->value;
	//$empWPPos = $rs_fld1->value;
	//$rs->MoveNext();
	//}

	//$rs->Close();
	//db_connection->Close();
?>