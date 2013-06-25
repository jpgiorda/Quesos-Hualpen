<?php
	$connect = mysql_connect("localhost","root","Ro:3n#A1");
	mysql_select_db("pedidos", $connect);

	if(mysql_errno($connect))
	{
		echo "Problemas en la conexion"."<br />";
	}

	else
	{
		
		$consultasql = "SELECT direccion, numero, id FROM data WHERE id = 5477";
		
	//	$consultasql = "SELECT num, depto, otros FROM direcciones WHERE rs =" '.';
						

						
		$resultado = mysql_query($consultasql, $connect);
		
		
		if (!$resultado)
		{
			echo "<b>Error de busquedaaa</b>";
			exit;
		}
		
		else
		{

			while($row = mysql_fetch_row($resultado))
			{
				echo $id." ";
				$direccion = $row[0];
				$num = $row[1];
				$id = $row[2];

				//coordenadas de la direccion

		        $dur = $direccion."%20".$num;
        		$dir = str_replace(" ","%20", $dur);
        		echo $dir;
		        $url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$dir.",+Chile&sensor=false";
		        //http://api.raventools.com/api?key=B1DFC59CA6EC76FF&method=domains&format=json";
		        $ch = curl_init($url);
		        //curl_setopt($ch,CURLOPT_URL,$url);
		        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
		        //curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
		        $json = curl_exec($ch);
		        if(!$json) {
		            echo curl_error($ch);
		        }
		        //print_r(json_decode($json));
		        //$obj = json_decode($json);
		        $array = json_decode($json,TRUE);

		        $resu = $array["results"];

		        //var_dump($array);
		        $cero = $resu[0];
		        $geom = $cero["geometry"];
		        $loca = $geom["location"];
		        $lat = " ".$loca["lat"];
		        $lng = "".$loca["lng"];
				//fin
		        echo $lat." ".$lng;
				$_GRABAR_SQL =  "UPDATE data set lat=".$lat.", lon=".$lng." where id=".$id;
				
				$a = mysql_query($_GRABAR_SQL, $connect);

				echo $a." 3";
			}
		}
			
	}

	mysql_close($connect);
?>