<?php

$connect = mysql_connect("localhost","root","Ro:3n#A1");
mysql_select_db("pedidos", $connect);

$consultasql = "SELECT MAX(id) as ID from data";
				
$resultado = mysql_query($consultasql, $connect);

$row = mysql_fetch_row($resultado);
echo $row[0];

?>