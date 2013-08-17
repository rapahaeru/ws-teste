<?php
class Medias extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Media_model', 'Media');
		$this->load->model('System_model');
	}
	
	public function index(){
	
		$config['functions']['getLastMediaByDate'] 					= array('function' => 'Medias.getLastMediaByDate');
		$config['object'] 											= $this;
	
		$this->xmlrpcs->initialize($config);
		$this->xmlrpcs->serve();
	}

	
	/*********************************
	Created : 01/11/2011
	by 		: Raphael Oliveira
	Updated	: 01/11/2011
	**********************************/
	function getLastMediaByDate($request){
		$parameters = $request->output_parameters();
	
		//$parameters['0'] | token "token=".md5(tripwebservice); //0d80f42a1a7928acf49f5f81de9b4a1a
		//$parameters['1'] | site
		//$parameters['2'] | data
		//$parameters['3'] | Media tag (audio/video)
	
		if ($parameters[0] != ''){
			//token
			$auth 			= $this->System_model->verifyToken($parameters[0]);
		}
	
		if ($auth){
	
			$site 							= $parameters['1'];
			$date							= $parameters['2'];
			$mediaTag						= $parameters['3'];
			$limit							= 6;
			$page							= 0;
			
			if($site == 'trip') 		$urlBase 		= 'http://revistatrip.uol.com.br';
			elseif($site == 'tpm') 		$urlBase 		= 'http://revistatpm.uol.com.br';
	
			if ($mediaTag != '' && $date != '') {

				$returnLastMedias	= $this->Media->getLastMedias($site,$date,$mediaTag,$page,$limit);
				foreach($returnLastMedias->result() as $value){
					if ($mediaTag == 'video'){
						$value->tx_link01 = '';
						$value->tx_link02 = '';					
					}else if ($mediaTag == 'audio'){
						$value->tx_linktube = '';
						
					}
				}
				$returnLastMediaFinal = $returnLastMedias;
				if ($returnLastMediaFinal){
						
					$arr = array();
	
					foreach($returnLastMediaFinal->result() as $index => $value){
	
						$arr[]	=	array(
											array(
												'MediaId'  				=> array($value->id,'int'),
												'Mediatitle'  			=> array($value->tx_titulo,'string'),
												'Medialead'  			=> array($value->tx_olho,'string'),
												'Mediadate'  			=> array($value->dt_publicacao,'date'),
												'MediaCover'			=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value->im_capa.'_._185_._320','string'),
												'MediaThumb' 			=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value->im_capa.'_._110_._115','string'),
												'MediaThumbTag'			=> array($value->tx_tag,'string'),
												'MediaLength'			=> array($value->tx_duracao,'int'),
												'MediaEdition'			=> array($value->tx_edicaor,'int'),
												'MediaObs'				=> array($value->tx_obs,'string'),
												'MediaLinkVideo'		=> array($value->tx_linktube,'string'),
												'MediaLinkAudio01'		=> array($value->tx_link01,'string'),
												'MediaLinkAudio02'		=> array($value->tx_link02,'string')
												),'struct');
	
						//$returnTopicArticleAllMedia = $returnLastArticles;
					}
	
					$response = array($arr,'array');
				}else{
					return $this->xmlrpc->send_response('none');
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
?>