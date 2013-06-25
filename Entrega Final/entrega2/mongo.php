
<?
	echo "estoy aqui";
	$m = new MongoClient(); // connect
	echo "a";
	$db = $m->selectDB("documentacion");
	$log = $db->selectCollection("reportes");

	$log->insert(array("id" => "2", "estado" => "Eealizado"));
?>