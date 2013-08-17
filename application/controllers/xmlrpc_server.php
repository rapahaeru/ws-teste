<?php

class Xmlrpc_server extends CI_Controller {

	public $staticdatabase = array(
		'languages' => './application/staticdatabase/pt-br/languages.xml'
	);
	
	function index()
	{
		$config['functions']['Greetings'] = array('function' => 'Xmlrpc_server.process');

		$this->xmlrpcs->initialize($config);
		$this->xmlrpcs->serve();
	}

	function process()
	{
		if(file_exists($this->staticdatabase['languages'])){
			$xmlUrl = $this->staticdatabase['languages'];
			$xmlStr = file_get_contents($xmlUrl);
			$xmlObj = simplexml_load_string($xmlStr);
			$arrXml = objectsIntoArray($xmlObj);
		}

		$arr = array();
		foreach ($arrXml['language'] as $index => $value) {
            if (is_object($value) || is_array($value)) {
            	$arr[] = array(array(
										'acronym' => array($value['acronym'],'string'),
										'title' => array($value['title'],'string')
									),'struct');
            }
		}
		$response = array($arr,'array');

		return $this->xmlrpc->send_response($response);
	}
}
?>