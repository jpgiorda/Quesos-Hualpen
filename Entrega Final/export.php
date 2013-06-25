<?
	header("Content-type: application/vnd.ms-excel" ) ; 
	header("Content-Disposition: attachment; filename=Quiebres.xls" ) ; 

	$m = new MongoClient();
	$db = $m->selectDB("documentacion");
	$log = $db->selectCollection("reportes");
	$fruitQuery = array('estado' => 'Quiebre de Stock');
	$log2 = $db->selectCollection("pedidos");

	echo "<table><tr>"; 
	$i = 0;
	echo "<td>Id</td>"; 
	echo "<td>Fecha</td>";
	echo "<td>RS</td>"; 
	echo "<td>Sku</td>"; 
	echo "<td>Cantidad</td>";
	echo "<td>Estado</td>"; 
	echo "<td>Info</td>"; 
	echo "<td>Direccion</td>";
	echo "<td>Numero</td>"; 

	echo "</tr>"; 

	$cursor = $log->find();

	$cursor->sort(array('estado' => 1));

	foreach ($cursor as $doc) 
	{
		$myid = $doc["id"];
		$myid = $myid + 0;
		$query = array('id' => $myid);
		$cursor2 = $log2->find($query);
		foreach($cursor2 as $doc2)
		{
			echo "<tr>";
			echo "<td>".$doc["id"]."</td>"; 
			echo "<td>".$doc2["fecha"]."</td>";
			echo "<td>".$doc2["rs"]."</td>";
			echo "<td>".$doc2["sku"]."</td>";
			echo "<td>".$doc2["cantidad"]."</td>";
			echo "<td>".$doc["estado"]."</td>"; 
			if($doc["estado"] == "Realizado")
			{
				echo "<td> </td>"; 
			}
			else
			{
				echo "<td>".$doc["info"]."</td>"; 
			}
			echo "<td>".$doc2["direccion"]."</td>";
			echo "<td>".$doc2["numero"]."</td>";

		    echo "</tr>";
		}
	}

	echo "</table>"; 
?>