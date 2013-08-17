<?php
/********************************************************************/
/* Development		: 2idea web solutions							*/
/* Created			: 14/06/2011									*/
/* For				: Teste de acesso externo ao webservice			*/
/********************************************************************/
header ("Content-Type:text/xml");
$server 		= 'http://revistatrip.uol.com.br/webservice/articles';
//$server 		= 'http://srvhm.trip.com.br/webservice/articles';
//$server 		= 'http://localhost/webservice/articles';
//$metodo			= 'getAdvertisingTag';
$metodo			= 'getLastArticlesByTopicForMobile';
$token			= '0d80f42a1a7928acf49f5f81de9b4a1a' ; //chave de acesso ao webservice

$request = xmlrpc_encode_request($metodo, array($token,'tpm','2011-09-19','tv-tpm','section'));
$context = stream_context_create(array('http' => array(
										'method' => "POST",
										'header' => "Content-Type: text/xml",
										'content' => $request
								)));
$file = file_get_contents($server, false, $context);
echo $file;
/*  $response = xmlrpc_decode($file);
if ($response && xmlrpc_is_fault($response)) {
    trigger_error("xmlrpc: $response[faultString] ($response[faultCode])");
} else {
    print_r($response);
}  */


		/* GETCONTENT */
		//$this->xmlrpc->method('getContent');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','23034'); //podcast
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','30574'); // default
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','23570'); //videocast
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','tpm','2963'); //TPM
		
		/* SEARCHWORDS */
		//$this->xmlrpc->method('getSearchWords');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','tpm','vermelhas','',3,'date'); // ('token','site','palavra buscada','page','limit','order')
				
		/* COUNT SEARCHWORDS */
		//$this->xmlrpc->method('getCountSearchWords');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','tpm','vermelhas'); // ('token','site','palavra buscada')
		
		/* GET GALLERY */
		//$this->xmlrpc->method('getGallery');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip',10); // ('token','site','id galeria')

		//$this->xmlrpc->method('getArticlesHome');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','tpm'); // ('token','site') 
		
		//$this->xmlrpc->method('getTopicsForMobile');
//		$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','','','','',''); // ('token','site','peça','tipo')


		//$this->xmlrpc->method('getLastArticlesByTopicForMobile');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','2011-09-20','salada','section');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','2011-09-20','trip-fm','section');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','2011-12-12','cultura-pop','topic');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','tpm','2011-09-19','tv-tpm','section');

//$this->xmlrpc->method('getArticleTopicsForMobile');
//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','','','','',''); // ('token','site','peça','tipo')
//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','salada','','','',''); // ('token','site','peça','tipo')
//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','','','2011-09-19','',''); // ('token','site','peça','tipo')
//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','trip-girls','section','2011-08-25','',''); // ('token','site','peça','tipo')
//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','cultura-pop','topic','2011-09-19','',''); // ('token','site','peça','tipo')
//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','reportagens','section','2011-09-23','',''); // ('token','site','peça','tipo')
//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','tpm','reportagens','section','2011-09-23','',''); // ('token','site','peça','tipo')
//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','tpm','ensaios','topic','2011-04-11','',''); // ('token','site','peça','tipo')		
		
?>
