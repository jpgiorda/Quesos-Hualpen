<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Documento sin título</title>
</head>

<body>
<table width="1024" height="527" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;
    <?php echo "Cuadro 1"; ?>
    </td>
    <td>&nbsp;
	
		<?php
		
		echo"<ul />";
		echo "<br /><li>Estados de pedidos: ";
		
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
		
	
	</td>
    <td>&nbsp;
	 <?php echo "Cuadro 3"; ?>
	</td>
  </tr>
  <tr>
    <td>&nbsp;
	 <?php echo "Cuadro 4"; ?>
	</td>
    <td>&nbsp;
	 <?php echo "Cuadro 5"; ?>
	</td>
    <td>&nbsp;
	 <?php echo "Cuadro 6"; ?>
	</td>
  </tr>
  <tr>
    <td>&nbsp;
	 <?php echo "Cuadro 7"; ?>
	</td>
    <td>&nbsp;
	 <?php echo "Cuadro 8"; ?>
	</td>
    <td>&nbsp;
	 <?php echo "Cuadro 9"; ?>
	</td>
  </tr>
</table>
</body>
</html>