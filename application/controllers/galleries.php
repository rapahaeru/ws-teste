<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Galleries extends CI_Controller {
	public function __construct()
       {
			parent::__construct();
			//$this->load->helper('date');
			$this->load->model('Gallery_model', 'Gallery');	
			$this->load->model('System_model');	
       }

	public function index(){
		$config['functions']['getGallery'] 						= array('function' => 'Galleries.getGallery');
		$config['object'] 										= $this;

		$this->xmlrpcs->initialize($config);
		$this->xmlrpcs->serve();

	}
	
	
 
	function getGallery($request){
		$parameters = $request->output_parameters();
		
		//var_dump($parameters);
				//$parameters['0'] | token "token=".md5(tripwebservice); //0d80f42a1a7928acf49f5f81de9b4a1a
				//$parameters['1'] | site
				//$parameters['2'] | searchWords
		
		if ($parameters[0] != ''){ //token
		
			$auth = $this->System_model->verifyToken($parameters[0]);
		
		}

		if ($auth){
		
			if ($parameters['0'] != '' && $parameters['1'] != '' && $parameters['2'] != '') {
				
				$site 						= $parameters['1'];
				$idGallery					= $parameters['2'];
				
				//INDICES
				
				//cria base url dos respectivos sites
				if($site == 'trip') 		$urlBase 		= 'http://revistatrip.uol.com.br';
				elseif($site == 'tpm') 		$urlBase 		= 'http://revistatpm.uol.com.br';
				
				//indices
				$returnGallery				= $this->Gallery->getArticleGalleryById($idGallery,$site);

				//var_dump($returnGallery);
				if ($returnGallery){
					
					//$imageArr					= explode($returnGallery[0]['tx_fotos'],',');
					
					$returnImages				= $this->Gallery->getArticleGalleryImageById($returnGallery[0]['tx_fotos'],$site);
					
					//var_dump($returnImages);
					
					$arr		= array();
					foreach ($returnImages as $index => $value) {
					
						$arr[]	=	 array(
											array(
													'imageId'		=> array($value['id'],'int'),
													'legend'		=> array($value['tx_legenda'],'string'),
													'credit'		=> array($value['tx_credito'],'string'),
													'image'			=> array($urlBase.'/_lib/common/imgResize.php?params='.$value['tx_imagem'].'_._460_._460','string'),
													'thumb' 		=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value['tx_imagem'].'_._110_._115','string')
						
											), 'struct');
					}
					$response = array($arr,'array');
				}else{
					return $this->xmlrpc->send_response('Nenhum item retornado!');
				}
					
				return $this->xmlrpc->send_response($response);
			}else{
				return $this->xmlrpc->send_error_message('100', 'Invalid Access');
			}
		
		}else{
			return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		}
	
	}
	

	
}

