
<?php
	class simple_xml_extended extends SimpleXMLElement
	{
	    public    function    Attribute($name)
	    {
	        foreach($this->Attributes() as $key=>$val)
	        {
	            if($key == $name)
	                return (string)$val;
	        }
	    }

	}
	
	$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
	$username = 'quesoshualpen@gmail.com';
	$password = 'integra3';
	
	$send = array();

	$mbox = imap_open($hostname,$username,$password) or die('Ha fallado la conexión: ' . imap_last_error());
	$emails = imap_search($mbox, 'UNSEEN FROM "pedidostallerintegracion@gmail.com"');

	if($emails) {

	 	 $salida = '';
		 $email_number = $emails[0]; //foreach($emails as $email_number) {    
		    	$overview = imap_fetch_overview($mbox,$email_number,0);
		    	$num_pedido = $overview[0]->subject;
			$num_pedido = substr($num_pedido, 7);
		
			$structure = imap_fetchstructure($mbox, $email_number);

			$data[$emails] = array(
				'num' => $num_pedido,
				'rut' => '',
				'direccionId' => '',
				'sku' => '',
				'fecha' => '',
				'fecha_llegada' => '',
				'hora_llegada' => '',
				'cantidad' => '',
				'cantidad_unidades' => ''
			);
			if(isset($structure->parts) && count($structure->parts)) {

				for($i = 0; $i < count($structure->parts); $i++) {

					$attachments[$i] = array(
						'is_attachment' => false,
						'filename' => '',
						'name' => '',
						'attachment' => ''
					);

					if($structure->parts[$i]->ifdparameters) {
						foreach($structure->parts[$i]->dparameters as $object) {
							if(strtolower($object->attribute) == 'filename') {
								$attachments[$i]['is_attachment'] = true;
								$attachments[$i]['filename'] = $object->value;
							}
						}
					}

					if($structure->parts[$i]->ifparameters) {
						foreach($structure->parts[$i]->parameters as $object) {
							if(strtolower($object->attribute) == 'name') {
								$attachments[$i]['is_attachment'] = true;
								$attachments[$i]['name'] = $object->value;
							}
						}
					}

					if($attachments[$i]['is_attachment']) {
						$attachments[$i]['attachment'] = imap_fetchbody($mbox, $email_number, $i+1);
						if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
							$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
							
							$attachments[$i]['attachment'] = substr($attachments[$i]['attachment'], 27);
							$attachments[$i]['attachment'] = substr ( $attachments[$i]['attachment'] , 0, -7 );
							$attachments[$i]['attachment'] = trim ( $attachments[$i]['attachment']);

							$text = $attachments[$i]['attachment'];

							$xml = simplexml_load_string($text, 'simple_xml_extended');
							
							$data[$email_number]['num'] = $num_pedido;
							
							$data[$email_number]['hora_llegada'] = $xml->Attribute('hora');
							$data[$email_number]['fecha_llegada'] =  $xml->Attribute('fecha');
							
							$data[$email_number]['rut'] =  $xml->Pedido->rut;
							$data[$email_number]['direccionId'] =  $xml->Pedido->direccionId;
							$data[$email_number]['fecha'] =  $xml->Pedido->fecha;
							$data[$email_number]['sku'] =  $xml->Pedido->sku;
							$data[$email_number]['cantidad'] =  $xml->Pedido->cantidad;
							$data[$email_number]['cantidad_unidad'] =  $xml->Pedido->cantidad->Attribute('unidad');
							
						}
						elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
							$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
						}
					}
					
				}
			}
		//}
	}
	imap_close($mbox);
	$email_number = $emails[0];
	//foreach($emails as $email_number) {

	//}
	if($data[$email_number]['num']!=''){
		$url = "Location: leeMail.php?";
		$url.='num_pedido='.$data[$email_number]['num'].'&';
		$url.='hora_llegada='.$data[$email_number]['hora_llegada'].'&';
		$url.='fecha_llegada='.$data[$email_number]['fecha_llegada'].'&';
	
		$url.='rut='.$data[$email_number]['rut'].'&';
		$url.='direccionId='.$data[$email_number]['direccionId'].'&';
		$url.='fecha='.$data[$email_number]['fecha'].'&';
		$url.='sku='.$data[$email_number]['sku'].'&';
		$url.='cantidad='.$data[$email_number]['cantidad'].'&';
		$url.='cantidad_unidad='.$data[$email_number]['cantidad_unidad'];
	}
	header($url);
	exit;
?>

