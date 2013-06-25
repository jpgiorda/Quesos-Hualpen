<?php

  	/*echo "hola";
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
    unset($dbh); */

    $idpedido = $_GET['id'];
    $cantidad = $_GET['numero'];

    $ahora = date("m.d.y");
    list($mes, $dia, $ano) = split('[/.-]', $ahora);
    $dia = $dia + 0;
    $mes = $mes + 0;
    $ano = $ano + 2000;
    $precio = 0;

    $connect = mysql_connect("localhost","root","Ro:3n#A1");
    mysql_select_db("precios", $connect);
    if(mysql_errno($connect))
    {
        echo "Problemas en la conexion"."<br />";
    }
    else
    {
        $consultasql = "SELECT precio, desde, hasta FROM precios WHERE material=".$idpedido;
        $resultado = mysql_query($consultasql, $connect);
        if (!$resultado)
        {
            echo "<b>Error de busquedaaa CLIMA</b>";
            exit;
        }
        else
        {
            while($row = mysql_fetch_row($resultado))
            {
                list($mesd, $diad, $anod) = split('[/.-]', $row[1]);
                $diad = $diad + 0;
                $mesd = $mesd + 0;
                $anod = $anod + 0;
                list($mesh, $diah, $anoh) = split('[/.-]', $row[2]);
                $diah = $diah + 0;
                $mesh = $mesh + 0;
                $anoh = $anoh + 0;
                if(($anod < $ano) || ($anod == $ano && $mesd<$mes) || ($anod == $ano && $mesd==$mes && $diad <= $dia))
                {
                    if(($anoh > $ano) || ($anoh == $ano && $mesh>$mes) || ($anoh == $ano && $mesh==$mes && $diah > $dia))
                    {
                        $precio = $row[0];
                    }
                }
            }
        }
    }

    echo "precio ".$precio;

    $monto = $precio*$cantidad;

    echo $monto;

    $hoy = date("H:i:s");
    $clave = "26JkBGs";
    $accion = "earning"; //earning o cost
    $fecha = $mes."-".$dia."-".$ano."%20".$hoy; //fecha en formato M-D-Y H:M:S
    echo $fecha;


    $url = "http://iic3103.ing.puc.cl/webservice/integra3/contabilidad/?key=26JkBGs&action=earning&date=".$fecha."&amount=".$monto;
    $ch = curl_init($url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
    $json = curl_exec($ch);
    if(!$json) {
        echo curl_error($ch);
    }
    $array = json_decode($json,TRUE);
    echo "Id :".$array["id"];
    echo "Resultado :".$array["result"];
?>