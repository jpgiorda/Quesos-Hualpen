<?php
$connect = mysql_connect("localhost","root","Ro:3n#A1");
mysql_select_db("pedidos", $connect);

if(mysql_errno($connect)){
	
	echo "Problemas en la conexion"."<br />";
		
}

else{
	
	$consultasql = "SELECT *
					FROM data
					WHERE estado = '0' OR estado = '2'
					ORDER BY fecha ASC
					LIMIT 0,10";
	
//	$consultasql = "SELECT num, depto, otros FROM direcciones WHERE rs =" '.';
					

					
	$resultado = mysql_query($consultasql, $connect);
	
	
	if (!$resultado){
		echo "<b>Error de busquedaaa</b>";
		exit;
	}
	
	else{
		
		echo"<ul />";
		
		while($row = mysql_fetch_row($resultado)){
			echo "<br /><li>Rut: ".$row[0]."</li><li> Fecha: ".$row[1]."</li><li> Estado: ".$row[10]."</li><br />"; 
		}
		
		echo "<ul>";
	      
	}
		
}


mysql_close($connect);



?>