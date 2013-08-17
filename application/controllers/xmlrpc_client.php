<?php
class Xmlrpc_client extends CI_Controller {
	function index()
	{
		$server_url = site_url('articles');
		//$server_url = site_url('galleries');
		//$server_url = site_url('medias');
		$this->xmlrpc->set_debug(TRUE);
		$this->xmlrpc->server($server_url, 80);
		
		
		
		/* GET GALLERY */
		//$this->xmlrpc->method('getGallery');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip',1415); // ('token','site','id galeria')
		
		/* GET ADVERTISING */
		//$this->xmlrpc->method('getAdvertisingTag');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','',''); // ('token','site','peça','tipo')
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','tpm','','mobile'); // ('token','site','peça','tipo')
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','tpm','seal','mobile'); // ('token','site','peça','tipo')
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','tpm','mobileFullpage','mobile'); // ('token','site','peça','tipo')
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','mobileFullbanner','mobile'); // ('token','site','peça','tipo')
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','','tablet'); // ('token','site','peça','tipo')
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','horizontal','tablet'); // ('token','site','peça','tipo')
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','vertical','tablet'); // ('token','site','peça','tipo')
		
		//$this->xmlrpc->method('getArticleTopicsForMobile');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','','','','',''); // ('token','site','peça','tipo')
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','salada','','','',''); // ('token','site','peça','tipo')
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','','','2011-09-19','',''); // ('token','site','peça','tipo')
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','trip-girls','section','2011-08-25','',''); // ('token','site','peça','tipo')
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','cultura-pop','topic','2011-09-19','',''); // ('token','site','peça','tipo')		
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','reportagens','section','2011-09-23','',''); // ('token','site','peça','tipo')
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','tpm','reportagens','section','2011-09-23','',''); // ('token','site','peça','tipo')
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','tpm','ensaios','topic','2011-04-11','',''); // ('token','site','peça','tipo')
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','tpm','tv-tpm','section','2011-09-19','',''); // ('token','site','peça','tipo')		
		
		//$this->xmlrpc->method('getTopicsForMobile');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','tpm','','','','',''); // ('token','site','peça','tipo')		
		
		$this->xmlrpc->method('getLastArticlesByTopicForMobile');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','2011-09-20','salada','section');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','2011-09-20','trip-fm','section');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','tpm','2011-12-20','Badulaque','section');
		$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','tpm','2011-09-19','tv-tpm','section');
		
		//$this->xmlrpc->method('getLastMediaByDate');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','2011-09-20','video');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','trip','2011-09-20','audio');
		//$request = array('0d80f42a1a7928acf49f5f81de9b4a1a','tpm','2011-09-20','video');
		
		$this->xmlrpc->request($request);		
		
		if ( ! $this->xmlrpc->send_request()){

			echo $this->xmlrpc->display_error();
		}
		else
		{
			echo '<pre>';
			print_r($this->xmlrpc->display_response());
			echo '</pre>';
		}
	}
}
?>