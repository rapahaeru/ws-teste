<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Articles extends CI_Controller {
	public function __construct()
       {
			parent::__construct();
			//$this->load->helper('date');
			$this->load->model('Article_model', 'Article');	
			$this->load->model('System_model');	
       }

	public function index(){
		
		$config['functions']['getArticlesHome'] 					= array('function' => 'Articles.getArticlesHome');
		$config['functions']['getAdvertisingTag'] 					= array('function' => 'Articles.getAdvertisingTag');
		$config['functions']['getTopicsForMobile'] 					= array('function' => 'Articles.getTopicsForMobile');
		$config['functions']['getArticleTopicsForMobile'] 			= array('function' => 'Articles.getArticleTopicsForMobile');
		$config['functions']['getLastArticlesByTopicForMobile'] 	= array('function' => 'Articles.getLastArticlesByTopicForMobile');
		$config['object'] 											= $this;

		$this->xmlrpcs->initialize($config);
		$this->xmlrpcs->serve();

	}
	

 
	/********************************* 	
	Created : 20/06/2011
	by 		: Raphael Oliveira
	Updated	: 24/11/2011
	**********************************/

	function getArticlesHome($request){
		$parameters = $request->output_parameters();
		
		//var_dump($parameters);
				//$parameters['0'] | token "token=".md5(tripwebservice); //0d80f42a1a7928acf49f5f81de9b4a1a
				//$parameters['1'] | site
				//$parameters['2'] | searchWords
		
		if ($parameters[0] != ''){ //token
		
			$auth = $this->System_model->verifyToken($parameters[0]);
		
		}

		if ($auth){
		
			if ($parameters['0'] != '' && $parameters['1'] != '') {
				
				$site 						= $parameters['1'];
				
				//INDICES
				
				//cria base url dos respectivos sites
				if($site == 'trip') 		$urlBase 		= 'http://revistatrip.uol.com.br';
				elseif($site == 'tpm') 		$urlBase 		= 'http://revistatpm.uol.com.br';
				
				//indices
				$returnIdArticlesHome				= $this->Article->getIdArticlesHome($site);

				//var_dump($returnIdArticlesHome);
				$str_idArticles = '';
				if ($returnIdArticlesHome){
					$str_idArticles = $returnIdArticlesHome[0]['id_dg'].',';
					$str_idArticles .= $returnIdArticlesHome[0]['id_dm'].',';
					$str_idArticles .= $returnIdArticlesHome[0]['id_dp1'].',';
					$str_idArticles .= $returnIdArticlesHome[0]['id_dp2'].',';
					$str_idArticles .= $returnIdArticlesHome[0]['id_dp3'].',';
					$str_idArticles .= $returnIdArticlesHome[0]['id_dp4'];
				}
				//echo $str_idArticles;
				
				$returnArticlesHome				= $this->Article->getArticlesHome($site,$str_idArticles);
				//var_dump($returnArticlesHome);
				if ($returnArticlesHome){
				
					$arr = array ();
					
					foreach($returnArticlesHome as $index => $value){
					
						$arr[]	=	array(
											array(
													'contentId'  	=> array($value['id'],'int'),
													'title'  		=> array($value['tx_titulo'],'string'),
													'author'  		=> array($value['tx_texto'],'string'),
													'imageCredit'	=> array($value['tx_foto'],'string'),
													'lead'  		=> array($value['tx_olho'],'string'),
													'content'  		=> array(getMobileOrContent($value['tx_conteudo'],$value['tx_conteudo_mobile']),'string'),
													'date'  		=> array($value['dt_conteudo'],'date'),
													'contentCover'	=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value['im_capa'].'_._185_._320','string'),
													'contentThumb' 	=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value['im_capa'].'_._60_._105','string'),
													'contentUrl'  	=> array($urlBase.$value['tx_url_completo'],'string'),
													'gallery'  		=> array($value['id_galeria'],'int'),
													'topicId'  		=> array($value['assunto_id'],'int'),
													'topic'  		=> array($value['tx_assunto'],'string')
												),'struct');
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
		
	} /* END getArticlesHome */
	
	
	/*********************************
	Created : 20/06/2011
	by 		: Raphael Oliveira
	Updated	: 26/08/2011
	**********************************/	
	function getAdvertisingTag($request){
		$parameters = $request->output_parameters();
		//Trip_mobile_fullpage_280x370
		//Trip_mobile_fullbanner_300x50
		//Trip_mobile_selo_30x30
		//Trip_tablet_horizontal_320x622
		//Trip_tablet_vertical_728x208		

		//$parameters['0'] | token "token=".md5(tripwebservice); //0d80f42a1a7928acf49f5f81de9b4a1a
		//$parameters['1'] | site
		//$parameters['2'] | peça 
		//$parameters['3'] | tipo (iphone / android / ipad)

		if ($parameters[0] != ''){ //token
		
			$auth = $this->System_model->verifyToken($parameters[0]);
		
		}
	
		if ($auth){
		
			if ($parameters['1'] != '' ) {
				
				if ($parameters['2'] != '') $piece			= trim($parameters['2']);
				else $piece = '';
				
				if ($parameters['3'] != '') $type			= trim($parameters['3']);
				else $type = '';				
				
				$site 						= trim($parameters['1']);
				if($site == 'trip'){
					$urlBase 		= 'http://revistatrip.uol.com.br';
					$prefixTag		= 'Trip';
				}elseif($site == 'tpm'){
					$urlBase 		= 'http://revistatpm.uol.com.br';
					$prefixTag		= 'Tpm';
				}elseif($site == 'audi'){
					//$urlBase 		= 'http://revistatpm.uol.com.br';
					$prefixTag		= 'Audi';
				}


				if ($type == 'ipad'){
					if($piece != '' && $piece == 'horizontal') 	$response = array( array( 'ipadHorizontal' => $prefixTag.'_ipad_horizontal_320x622'), 'struct');
					elseif($piece != '' && $piece == 'vertical') 	$response = array( array( 'ipadVertical' => $prefixTag.'_ipad_vertical_728x208'), 'struct');
					else
						$response = array (
											array(
												'ipadHorizontal' 		=> array($prefixTag.'_ipad_horizontal_320x622','string'),
												'ipadVertical' 			=> array($prefixTag.'_ipad_vertical_728x208','string')
												),'struct');				
						
				
				}elseif ($type == 'iphone'){
					if($piece != '' && $piece == 'iphoneSeal') 	$response = array( array( 'iphoneSeal' => $prefixTag.'_iphone_selo_30x30'), 'struct');
					elseif($piece != '' && $piece == 'iphoneFullpage') 	$response = array( array( 'iphoneFullpage' => $prefixTag.'_iphone_fullpage_280x370'), 'struct');
					elseif($piece != '' && $piece == 'iphoneFullbanner') 	$response = array( array( 'iphoneFullbanner' => $prefixTag.'_iphone_fullbanner_300x50'), 'struct');
					else
				
						$response = array (
											array(
												'iphoneSeal' 			=> array($prefixTag.'_iphone_selo_30x30','string'),
												'iphoneFullpage' 		=> array($prefixTag.'_iphone_fullpage_280x370','string'),
												'iphoneFullbanner' 		=> array($prefixTag.'_iphone_fullbanner_300x50','string') 
												),'struct');				
				
				}elseif ($type == 'android'){
					if($piece != '' && $piece == 'seal') 	$response = array( array( 'androidSeal' => $prefixTag.'_android_selo_30x30'), 'struct');
					elseif($piece != '' && $piece == 'fullpage') 	$response = array( array( 'androidFullpage' => $prefixTag.'_android_fullpage_280x370'), 'struct');
					elseif($piece != '' && $piece == 'fullbanner') 	$response = array( array( 'androidFullbanner' => $prefixTag.'_android_fullbanner_300x50'), 'struct');
					else
				
						$response = array (
											array(
												'androidSeal' 			=> array($prefixTag.'_android_selo_30x30','string'),
												'androidFullpage' 		=> array($prefixTag.'_android_fullpage_280x370','string'),
												'androidFullbanner' 		=> array($prefixTag.'_android_fullbanner_300x50','string') 
												),'struct');				
				}else{
						$response = array (
											array(
												'ipadHorizontal' 		=> array($prefixTag.'_ipad_horizontal_320x622','string'),
												'ipadVertical' 			=> array($prefixTag.'_ipad_vertical_728x208','string'),
												'iphoneSeal' 			=> array($prefixTag.'_iphone_selo_30x30','string'),
												'iphoneFullpage' 		=> array($prefixTag.'_iphone_fullpage_280x370','string'),
												'iphoneFullbanner' 		=> array($prefixTag.'_iphone_fullbanner_300x50','string'), 
												'androidSeal' 			=> array($prefixTag.'_android_selo_30x30','string'),
												'androidFullpage' 		=> array($prefixTag.'_android_fullpage_280x370','string'),
												'androidFullbanner' 		=> array($prefixTag.'_android_fullbanner_300x50','string') 
												),'struct');				
				
				}

				
			
				return $this->xmlrpc->send_response($response);

			}else{
				return $this->xmlrpc->send_error_message('100', 'Invalid Access');
			}
		
		}else{
			return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		}	
	
	
	}/* END getAdvertising */
	
	
	/*********************************
	Created : 15/12/2011
	by 		: Raphael Oliveira
	Updated	: 31/10/2011
	**********************************/
	function getTopicsForMobile($request){
		$parameters = $request->output_parameters();
		
		//var_dump($parameters);
				//$parameters['0'] | token "token=".md5(tripwebservice); //0d80f42a1a7928acf49f5f81de9b4a1a
				//$parameters['1'] | site
		
		if ($parameters[0] != ''){ //token
		
			$auth = $this->System_model->verifyToken($parameters[0]);
		
		}

		if ($auth){
		
			if ($parameters['1'] != '') {
				
				$site 						= $parameters['1'];
				//cria base url dos respectivos sites
				if($site == 'trip'){
					$urlBase 		= 'http://revistatrip.uol.com.br';
					
					$topicsForMobile	= array();
					$topicsForMobile[0] = array('seo' => 'viagem','type' => 'assunto');
					$topicsForMobile[1] = array('seo' => 'tv-trip','type' => 'secao');
					$topicsForMobile[2] = array('seo' => 'trip-fm','type' => 'secao');
					$topicsForMobile[3] = array('seo' => 'reportagens','type' => 'secao');
					$topicsForMobile[4] = array('seo' => 'entrevistas','type' => 'assunto');
					$topicsForMobile[5] = array('seo' => 'cultura-pop','type' => 'assunto');
					$topicsForMobile[6] = array('seo' => 'esporte','type' => 'assunto');
					$topicsForMobile[7] = array('seo' => 'tripgirls','type' =>'assunto'); 
					
					//$topicsForMobile[9] = array('seo' => 'notas','type' => 'secao');
					//$topicsForMobile[5] = array('seo' => 'salada','type' => 'secao');
					//$topicsForMobile[3] = array('seo' => 'colunas','type' => 'secao');

					$topicos = array();
					$assuntos = array();
					$cont = 0;
					for($x=0; $x< sizeof($topicsForMobile); $x++){
						if ($topicsForMobile[$x]['type'] == 'assunto') {
							$assuntos[$cont] = $topicsForMobile[$x]['seo'];
							$cont = $cont + 1;
							
						}
					}
					
					$secoes = array();
					$cont2 = 0;
					for($y=0; $y< sizeof($topicsForMobile); $y++){
						if ($topicsForMobile[$y]['type'] == 'secao') {
							$secoes[$cont2] = $topicsForMobile[$y]['seo'];
							$cont2 = $cont2 + 1;
						}
					}					
					
					//$topicos = array_push($assuntos, $secoes);
					//$topicos = array_merge($assuntos,$secoes);
					//var_dump($topicos);
					
					//var_dump($assuntos);
					//var_dump($secoes);
					
  					for($x=0; $x< sizeof($assuntos); $x++){
						//echo $assuntos[$x];
 							$returnTopic 				= $this->Article->getTopicBySeo($site,$assuntos[$x]);
 							if($returnTopic){
 								$returnArrAssuntos[$x]['id'] 		= $returnTopic[0]['id'];
 								$returnArrAssuntos[$x]['name'] 		= $returnTopic[0]['name'];
 								$returnArrAssuntos[$x]['seo'] 		= $returnTopic[0]['seo'];
 								$returnArrAssuntos[$x]['type'] 		= 'Topic';

				
 						}
  					}
  					//var_dump($returnArr);
  					//die();
  					
  					for($y=0; $y< sizeof($secoes); $y++){

 							$returnTopic 				= $this->Article->getSectionBySeo($site,$secoes[$y]);
 							if($returnTopic){							
 								$returnArrSecoes[$y]['id'] 		= $returnTopic[0]['id'];
 								$returnArrSecoes[$y]['name'] 		= $returnTopic[0]['name'];
 								$returnArrSecoes[$y]['seo'] 		= $returnTopic[0]['seo'];							
 								$returnArrSecoes[$y]['type'] 		= 'section';
 							}

 					}
					
 					$returnArr = array_merge($returnArrAssuntos,$returnArrSecoes);
 					
					//var_dump($returnArr);
					if ($returnArr){
					
						for($y=0; $y<sizeof($returnArr); $y++){

							$arr[] = array(
												array(	
														'topicForMobileId'  	=> array($returnArr[$y]['id'],'int'),
														'topicForMobileName'  	=> array($returnArr[$y]['name'],'string'),
														'topicForMobileSeoTag'  => array($returnArr[$y]['seo'],'string'),
														'topicForMobileType'  	=> array($returnArr[$y]['type'],'string')
													), 'struct');				
						}
						
						$response = array($arr,'array');
						
					}else{
						return $this->xmlrpc->send_response('none');
					}
						
					return $this->xmlrpc->send_response($response);
				
				}elseif($site == 'tpm'){
					$urlBase 		= 'http://revistatpm.uol.com.br';
					
					$topicsForMobile	= array();
					$topicsForMobile[0] = array('seo' => 'entrevistas','type' => 'assunto');
					$topicsForMobile[1] = array('seo' => 'moda','type' => 'assunto');
					$topicsForMobile[2] = array('seo' => 'beleza','type' => 'assunto');
					$topicsForMobile[3] = array('seo' => 'ensaios','type' => 'assunto');
					$topicsForMobile[4] = array('seo' => 'decoracao','type' => 'assunto');
					$topicsForMobile[5] = array('seo' => 'reportagens','type' => 'assunto');
					$topicsForMobile[6] = array('seo' => 'tv-tpm','type' => 'secao');
					$topicsForMobile[7] = array('seo' => 'trip-fm','type' => 'secao');
					$topicsForMobile[8] = array('seo' => 'badulaque','type' => 'secao');
					$topicsForMobile[9] = array('seo' => 'esporte','type' => 'assunto');
					$topicsForMobile[10] = array('seo' => 'cultura-pop','type' => 'assunto');
					$topicsForMobile[11] = array('seo' => 'viagem','type' => 'assunto');
					//$topicsForMobile[3] = array('seo' => 'colunas','type' => 'secao');
					//var_dump($topicsForMobile);
					
					$topicos = array();
					$assuntos = array();
					$cont = 0;
					for($x=0; $x< sizeof($topicsForMobile); $x++){
						if ($topicsForMobile[$x]['type'] == 'assunto') {
							$assuntos[$cont] = $topicsForMobile[$x]['seo'];
							$cont = $cont + 1;
							
						}
					}
					
					$secoes = array();
					$cont2 = 0;
					for($y=0; $y< sizeof($topicsForMobile); $y++){
						if ($topicsForMobile[$y]['type'] == 'secao') {
							$secoes[$cont2] = $topicsForMobile[$y]['seo'];
							$cont2 = $cont2 + 1;
						}
					}					
					
					//$topicos = array_push($assuntos, $secoes);
					//$topicos = array_merge($assuntos,$secoes);
					//var_dump($topicos);
					
					//var_dump($assuntos);
					//var_dump($secoes);
					
  					for($x=0; $x< sizeof($assuntos); $x++){
						//echo $assuntos[$x];
 							$returnTopic 				= $this->Article->getTopicBySeo($site,$assuntos[$x]);
 							if($returnTopic){
 								$returnArrAssuntos[$x]['id'] 		= $returnTopic[0]['id'];
 								$returnArrAssuntos[$x]['name'] 		= $returnTopic[0]['name'];
 								$returnArrAssuntos[$x]['seo'] 		= $returnTopic[0]['seo'];
 								$returnArrAssuntos[$x]['type'] 		= 'Topic';

				
 						}
  					}
  					//var_dump($returnArr);
  					//die();
  					
  					for($y=0; $y< sizeof($secoes); $y++){

 							$returnTopic 				= $this->Article->getSectionBySeo($site,$secoes[$y]);
 							if($returnTopic){							
 								$returnArrSecoes[$y]['id'] 		= $returnTopic[0]['id'];
 								$returnArrSecoes[$y]['name'] 		= $returnTopic[0]['name'];
 								$returnArrSecoes[$y]['seo'] 		= $returnTopic[0]['seo'];							
 								$returnArrSecoes[$y]['type'] 		= 'section';
 							}

 					}
					
 					$returnArr = array_merge($returnArrAssuntos,$returnArrSecoes);
 					

					if ($returnArr){
					
						for($y=0; $y<sizeof($returnArr); $y++){
							$arr[] = array(
												array(	
														'topicForMobileId'  	=> array($returnArr[$y]['id'],'int'),
														'topicForMobileName'  	=> array($returnArr[$y]['name'],'string'),
														'topicForMobileSeoTag'  => array($returnArr[$y]['seo'],'string'),
														'topicForMobileType'  	=> array($returnArr[$y]['type'],'string')
													), 'struct');				
						}
						
						$response = array($arr,'array');
						
					}else{
						return $this->xmlrpc->send_response('none');
					}
						
					return $this->xmlrpc->send_response($response);					
					
				}
				
			}else{
				return $this->xmlrpc->send_error_message('100', 'Invalid Access');
			}
		
		}else{
			return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		}
		
	} /* END getTopic */
	
	/*********************************
	Created : 19/07/2011
	by 		: Raphael Oliveira
	Updated	: 24/11/2011
	**********************************/
	function getArticleTopicsForMobile($request){
		$parameters = $request->output_parameters();
		
		//$parameters['0'] | token "token=".md5(tripwebservice); //0d80f42a1a7928acf49f5f81de9b4a1a
		//$parameters['1'] | site
		//$parameters['2'] | topicMobileTag (section/topic)
		//$parameters['3'] | topicForMobileType (trip-girls/tv-trip/entrevistas)
		//$parameters['4'] | data filtro asssunto
		//$parameters['5'] | page // paginacao
		//$parameters['6'] | limit

		date_default_timezone_set("Brazil/East");
		
		if ($parameters[0] != ''){ //token
			$auth 			= $this->System_model->verifyToken($parameters[0]);
		}

		if ($auth){
		
				$site 						= $parameters['1'];
				
				$topicMobileTag				= $parameters['2'];
				$topicForMobileType			= $parameters['3'];
				
				$date						= $parameters['4'];
				$page						= $parameters['5'];
				$limit						= $parameters['6'];

				if($site == 'trip'){
					$urlBase 			= 'http://revistatrip.uol.com.br';
					$topicsForMobile	= array();
					$topicsForMobile[0] 	= array('seo' => 'tripgirls','type' =>'assunto');
					$topicsForMobile[1] 	= array('seo' => 'tv-trip','type' => 'secao');
					$topicsForMobile[2] 	= array('seo' => 'trip-fm','type' => 'secao');
					$topicsForMobile[3] 	= array('seo' => 'reportagens','type' => 'secao');
					$topicsForMobile[4] 	= array('seo' => 'entrevistas','type' => 'assunto');
					$topicsForMobile[5] 	= array('seo' => 'cultura-pop','type' => 'assunto');
					$topicsForMobile[6] 	= array('seo' => 'esporte','type' => 'assunto');
					$topicsForMobile[7] 	= array('seo' => 'viagem','type' => 'assunto');
					
					//$topicsForMobile[5] 	= array('seo' => 'salada','type' => 'secao');
					//$topicsForMobile[9] 	= array('seo' => 'notas','type' => 'secao');
					
					//inserido para agrupar com cultura-pop
// 					$topicsForMobile[11] 	= array('seo' => 'artes','type' => 'assunto');
// 					$topicsForMobile[12] 	= array('seo' => 'cinema','type' => 'assunto');
// 					$topicsForMobile[13] 	= array('seo' => 'design','type' => 'assunto');
// 					$topicsForMobile[14] 	= array('seo' => 'fotografia','type' => 'assunto');
// 					$topicsForMobile[15] 	= array('seo' => 'livros','type' => 'assunto');
// 					$topicsForMobile[16] 	= array('seo' => 'musica','type' => 'assunto');
// 					$topicsForMobile[17] 	= array('seo' => 'quadrinho','type' => 'assunto');					

					//monta string com SEO de todas as categorias solicitadas mobile
					$str_topicsForMobile = '';
					for($c=0; $c<sizeof($topicsForMobile); $c++){
						$str_topicsForMobile .= $topicsForMobile[$c]['seo'].',';
					}
					$str_topicsForMobile = substr($str_topicsForMobile,0,-1);
				
				}elseif($site == 'tpm'){
					$urlBase 			= 'http://revistatpm.uol.com.br';
					$topicsForMobile	= array();
					$topicsForMobile[0] = array('seo' => 'entrevistas','type' => 'assunto');
					$topicsForMobile[1] = array('seo' => 'moda','type' => 'assunto');
					$topicsForMobile[2] = array('seo' => 'beleza','type' => 'assunto');
					$topicsForMobile[3] = array('seo' => 'ensaios','type' => 'assunto');
					$topicsForMobile[4] = array('seo' => 'decoracao','type' => 'assunto');
					$topicsForMobile[5] = array('seo' => 'reportagens','type' => 'assunto');
					$topicsForMobile[6] = array('seo' => 'tv-tpm','type' => 'secao');
					$topicsForMobile[7] = array('seo' => 'trip-fm','type' => 'secao');
					$topicsForMobile[8] = array('seo' => 'badulaque','type' => 'secao');
					$topicsForMobile[9] = array('seo' => 'esporte','type' => 'assunto');
					$topicsForMobile[10] = array('seo' => 'cultura-pop','type' => 'assunto');
					$topicsForMobile[11] = array('seo' => 'viagem','type' => 'assunto');
					//$topicsForMobile[3] = array('seo' => 'colunas','type' => 'secao');
					//monta string com SEO de todas as categorias solicitadas mobile
					$str_topicsForMobile = '';
					for($c=0; $c<sizeof($topicsForMobile); $c++){
						$str_topicsForMobile .= $topicsForMobile[$c]['seo'].',';
					}
					$str_topicsForMobile = substr($str_topicsForMobile,0,-1);
				
				}
		
		
			if ($topicMobileTag == '' && $date == '') { // retorna todas as noticias separadas pelo assunto NA data atual  OK
				
				//if ($date == '') $date 			= '2000-06-07'; // data ex de data atual
				if ($date == '') $date 				= date("Y-m-d"); // data atual
				if ($page == '') $page 				= 0;
				if ($limit == '') $limit 			= 10;
				
				$returnTopicArticleAll				= $this->Article->getArticleTopicForMobileAll($site,$date,$topicsForMobile,$page,$limit);		//model ok		

				if ($returnTopicArticleAll){
					foreach($returnTopicArticleAll->result() as $value){
						$returnMediaCast 	= '';
						$returnMediaVideo 	= '';
						
						if ($site == 'trip'){
							$returnMediaCast			= $this->Article->getPodcastByContentId($value->id);
							$returnMediaVideo			= $this->Article->getVideoByContentId($value->id);	
						
							if($returnMediaVideo){ //videocast
								$typeMedia					= "Videocast";
								$returnVideoLink 			= $returnMediaVideo[0]['tx_link'];
								$returnAudioLink			= "";
								$typeMediaExtension			= getMediaExtension($returnVideoLink,-3);
							}elseif($returnMediaCast){ //verifica se tem audio, ou seja, podcast
								$typeMedia					= "Podcast";
								$returnAudioLink 			= $returnMediaCast[0]['tx_link01'];
								$returnVideoLink			= "";
								$typeMediaExtension			= "";								
							}elseif($returnMediaVideo == TRUE && $returnMediaCast == TRUE){	//verifica se tem audio e video
								$typeMedia					= "Videocast/Podcast";
								$returnVideoLink 			= $returnMediaVideo[0]['tx_link'];
								$returnAudioLink 			= $returnMediaCast[0]['tx_link01'];
								$typeMediaExtension			= getMediaExtension($returnVideoLink,-3);							
							}else{ //default
								$typeMedia					= "default";
								$returnVideoLink			= "";
								$returnAudioLink			= "";
								$typeMediaExtension			= "";
								
							}
							
							//verifica se é secao ou assunto
							if ($value->assunto_seo == 'esporte'){
								$value->topicMobileTag 			= 'topic';
								$value->topicForMobileType 		= $value->assunto_seo;
							}else{
								$value->topicMobileTag = 'section';
								$returnSection 					= $this->Article->getInfoBySectionId($site,$value->section_seo,$value->id);
								$value->topicForMobileType 		= $returnSection[0]['tx_seo'];
							}
							
						}elseif($site == 'tpm'){
							$returnMediaVideo			= $this->Article->getVideoTpmByContentId($value->id);
								
							if($returnMediaVideo){
								//videocast
								$typeMedia					= "Videocast";
								$returnVideoLink 			= $returnMediaVideo[0]['tx_link'];
								$returnAudioLink			= "";
								$typeMediaExtension			= getMediaExtension($returnVideoLink,-3);
							}

							$value->tx_conteudo_mobile 	= "";
							
							if ($value->assunto_seo == 'moda' || $value->assunto_seo == 'beleza' || $value->assunto_seo == 'decoracao' ){
								$value->topicMobileTag 			= 'topic';
								$value->topicForMobileType 		= $value->assunto_seo;
							}else{
								$value->topicMobileTag 			= 'section';
								$returnSection 					= $this->Article->getInfoBySectionId($site,$value->section_seo,$value->id);
								$value->topicForMobileType 		= $returnSection[0]['tx_seo'];								
							}							
							
						}					
						
						$value->type			= $typeMedia;
						$value->linkPodcast 	= $returnAudioLink;
						$value->linkVideocast 	= $returnVideoLink;
						$value->typeMediaExt 	= $typeMediaExtension;
						
					
					}
					$returnTopicArticleAllMedia = $returnTopicArticleAll;
				
					if ($returnTopicArticleAllMedia){
					
						$arr = array();
						
						foreach($returnTopicArticleAllMedia->result() as $index => $value){
								
							$arr[]	=	array(
												array(
														'contentId'  			=> array($value->id,'int'),
														'title'  				=> array($value->tx_titulo,'string'),
														'author'  				=> array($value->tx_texto,'string'),
														'imageCredit'  			=> array($value->tx_foto,'string'),
														'lead'  				=> array($value->tx_olho,'string'),
														'leadMobile'			=> array($value->tx_chamada,'string'),
														'content'  				=> array(getMobileOrContent($value->tx_conteudo,$value->tx_conteudo_mobile),'string'),
														'date'  				=> array($value->dt_conteudo,'date'),
														'contentCover'			=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value->im_capa.'_._185_._320','string'),
														'contentThumb' 			=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value->im_capa.'_._60_._105','string'),
														'gallery'  				=> array($value->id_galeria,'int'),
														'contentUrl'  			=> array($urlBase.$value->tx_url_completo,'string'),														
														'topicForMobileSeoTag'  => array($value->topicMobileTag,'string'),
														'topicForMobileType'  	=> array($value->topicForMobileType,'string'),
														'type' 					=> array($value->type,'string'),
														'typeMediaExt'			=> array($value->typeMediaExt,'string'),														
														'linkPodcast' 			=> array($value->linkPodcast,'string'),
														'linkVideocast'			=> array($value->linkVideocast,'string')
													),'struct');
						
						}
						
						$response = array($arr,'array');

					}				
				
				}else{
					return $this->xmlrpc->send_response('none');
				}
				
				return $this->xmlrpc->send_response($response);			
			
			}elseif ($topicMobileTag != '' && $date == '') { // retorna noticias filtradas pelo assunto (ID) NA data atual OK

				//if ($date == '') $date 					= '2000-06-07'; // data ex
				//if ($date == '') $date 					= '2011-09-19'; // data ex
				
				if ($date == '') $date 						= date("Y-m-d"); // data atual
				if ($page == '') $page 						= 0;
				if ($limit == '') $limit 					= 10;
				
				$returnArticleTopicBySeoTag					= $this->Article->getArticleTopicForMobileBySeoTag($site,$date,$topicMobileTag,$topicForMobileType,$page,$limit);
				
				if($returnArticleTopicBySeoTag){
				
					foreach($returnArticleTopicBySeoTag->result() as $value){
						$returnMediaCast 	= '';
						$returnMediaVideo 	= '';
						
						if ($site == 'trip'){
							$returnMediaCast			= $this->Article->getPodcastByContentId($value->id);
							$returnMediaVideo			= $this->Article->getVideoByContentId($value->id);	

							if($returnMediaVideo){ //videocast
								$typeMedia					= "Videocast";
								$returnVideoLink 			= $returnMediaVideo[0]['tx_link'];
								$returnAudioLink			= "";
								$typeMediaExtension			= getMediaExtension($returnVideoLink,-3);
							
							}elseif($returnMediaCast){ // podcast
								$typeMedia					= "Podcast";
								$returnAudioLink 			= $returnMediaCast[0]['tx_link01'];
								$returnVideoLink			= "";
								$typeMediaExtension			= "";
							}elseif($returnMediaVideo == TRUE && $returnMediaCast == TRUE){//verifica se tem audio e video
								$typeMedia					= "Videocast/Podcast";
								$returnVideoLink 			= $returnMediaVideo[0]['tx_link'];
								$returnAudioLink 			= $returnMediaCast[0]['tx_link01'];
								$typeMediaExtension			= getMediaExtension($returnVideoLink,-3);
							}else{ //default
								$typeMedia					= "default";
								$returnVideoLink			= "";
								$returnAudioLink			= "";
								$typeMediaExtension			= "";
							}				
						}elseif($site == 'tpm'){
							$returnMediaVideo			= $this->Article->getVideoTpmByContentId($value->id);
								
							if($returnMediaVideo){
								//videocast
								$typeMedia					= "Videocast";
								$returnVideoLink 			= $returnMediaVideo[0]['tx_link'];
								$returnAudioLink			= "";
								$typeMediaExtension			= getMediaExtension($returnVideoLink,-3);
							}

							
						}					
						
						$value->type			= $typeMedia;
						$value->linkPodcast 	= $returnAudioLink;
						$value->linkVideocast 	= $returnVideoLink;
						$value->typeMediaExt 	= $typeMediaExtension;
						$value->tx_conteudo_mobile 	= "";
					}				
				
				$returnArticleTopicByIdMedia = $returnArticleTopicBySeoTag;
				
				if ($returnArticleTopicByIdMedia){
				
					$arr = array ();
					
					foreach($returnArticleTopicByIdMedia->result() as $value){
					
							$arr[]	=	array(
												array(
														'contentId'  			=> array($value->id,'int'),
														'title'  				=> array($value->tx_titulo,'string'),
														'author'  				=> array($value->tx_texto,'string'),
														'imageCredit'  			=> array($value->tx_foto,'string'),
														'lead'  				=> array($value->tx_olho,'string'),
														'leadMobile'			=> array($value->tx_chamada,'string'),
														'content'  				=> array(getMobileOrContent($value->tx_conteudo,$value->tx_conteudo_mobile),'string'),
														'date'  				=> array($value->dt_conteudo,'date'),
														'contentCover'			=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value->im_capa.'_._185_._320','string'),
														'contentThumb' 			=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value->im_capa.'_._60_._105','string'),
														'gallery'  				=> array($value->id_galeria,'int'),
														'contentUrl'  			=> array($urlBase.$value->tx_url_completo,'string'),
														'type' 					=> array($value->type,'string'),
														'typeMediaExt'			=> array($value->typeMediaExt,'string'),														
														'linkPodcast' 			=> array($value->linkPodcast,'string'),
														'linkVideocast'			=> array($value->linkVideocast,'string')
													),'struct');
					}
					
					$response = array($arr,'array');
				}
			
			}else{
					return $this->xmlrpc->send_response('none');	
			}
			
				return $this->xmlrpc->send_response($response);
			
			}elseif ($topicMobileTag == '' && $date != '') { // retorna noticias separadas POR assunto a partir da data enviada   OK
				
				if ($page == '') $page 						= 0;
				if ($limit == '') $limit 					= 10;

				$returnTopicArticleByDate					= $this->Article->getArticleTopicForMobileAll($site,$date,$topicsForMobile,$page,$limit);
				
				if($returnTopicArticleByDate){
					foreach($returnTopicArticleByDate->result() as $value){
						$returnMediaCast 	= '';
						$returnMediaVideo 	= '';
						
						if ($site == 'trip'){
							$returnMediaCast			= $this->Article->getPodcastByContentId($value->id);
							$returnMediaVideo			= $this->Article->getVideoByContentId($value->id);	
							
							if($returnMediaVideo){ //videocast
								$typeMedia					= "Videocast";
								$returnVideoLink 			= $returnMediaVideo[0]['tx_link'];
								$returnAudioLink			= "";
								$typeMediaExtension			= getMediaExtension($returnVideoLink,-3);
							
							}elseif($returnMediaCast){ //verifica se tem audio, ou seja, podcast
								$typeMedia					= "Podcast";
								$returnAudioLink 			= $returnMediaCast[0]['tx_link01'];
								$returnVideoLink			= "";
								$typeMediaExtension			= "";
							}elseif($returnMediaVideo == TRUE && $returnMediaCast == TRUE){	//verifica se tem audio e video
								$typeMedia					= "Videocast/Podcast";
								$returnVideoLink 			= $returnMediaVideo[0]['tx_link'];
								$returnAudioLink 			= $returnMediaCast[0]['tx_link01'];
								$typeMediaExtension			= getMediaExtension($returnVideoLink,-3);								
							}else{ //default
								$typeMedia					= "default";
								$returnVideoLink			= "";
								$returnAudioLink			= "";
								$typeMediaExtension			= "";
							}

							if ($value->assunto_seo == 'esporte'){
								$value->topicMobileTag 			= 'topic';
								$value->topicForMobileType 		= $value->assunto_seo;
							}else{
								$value->topicMobileTag = 'section';
								$returnSection 					= $this->Article->getInfoBySectionId($site,$value->section_seo,$value->id);
								$value->topicForMobileType 		= $returnSection[0]['tx_seo'];
							}							
							
						}elseif($site == 'tpm'){
							$returnMediaVideo			= $this->Article->getVideoTpmByContentId($value->id);
								
							if($returnMediaVideo){
								//videocast
								$typeMedia					= "Videocast";
								$returnVideoLink 			= $returnMediaVideo[0]['tx_link'];
								$returnAudioLink			= "";
								$typeMediaExtension			= getMediaExtension($returnVideoLink,-3);
							}

							$value->tx_conteudo_mobile 	= "";
							
							if ($value->assunto_seo == 'moda' || $value->assunto_seo == 'beleza' || $value->assunto_seo == 'decoracao' ){
								$value->topicMobileTag 			= 'topic';
								$value->topicForMobileType 		= $value->assunto_seo;
							}else{
								$value->topicMobileTag 			= 'section';
								$returnSection 					= $this->Article->getInfoBySectionId($site,$value->section_seo,$value->id);
								$value->topicForMobileType 		= $returnSection[0]['tx_seo'];								
							}							
							
						}					
						
						$value->type			= $typeMedia;
						$value->linkPodcast 	= $returnAudioLink;
						$value->linkVideocast 	= $returnVideoLink;
						$value->typeMediaExt 	= $typeMediaExtension;					
					}				
				
					$returnTopicArticleByDateMedia = $returnTopicArticleByDate;				
				
				if ($returnTopicArticleByDateMedia){
				
					$arr = array ();
					
					foreach($returnTopicArticleByDateMedia->result() as $value){
					
							$arr[]	=	array(
												array(
														'contentId'  			=> array($value->id,'int'),
														'title'  				=> array($value->tx_titulo,'string'),
														'author'  				=> array($value->tx_texto,'string'),
														'imageCredit'  			=> array($value->tx_foto,'string'),
														'lead'  				=> array($value->tx_olho,'string'),
														'leadMobile'			=> array($value->tx_chamada,'string'),
														'content'  				=> array(getMobileOrContent($value->tx_conteudo,$value->tx_conteudo_mobile),'string'),
														'date'  				=> array($value->dt_conteudo,'date'),
														'contentCover'			=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value->im_capa.'_._185_._320','string'),
														'contentThumb' 			=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value->im_capa.'_._60_._105','string'),
														'gallery'  				=> array($value->id_galeria,'int'),
														'contentUrl'  			=> array($urlBase.$value->tx_url_completo,'string'),
														'topicForMobileSeoTag'	=> array($value->topicMobileTag,'string'),
														'topicForMobileType'  	=> array($value->topicForMobileType,'string'),
														'type' 					=> array($value->type,'string'),
														'typeMediaExt'			=> array($value->typeMediaExt,'string'),														
														'linkPodcast' 			=> array($value->linkPodcast,'string'),
														'linkVideocast'			=> array($value->linkVideocast,'string')
													),'struct');
					}
					
					$response = array($arr,'array');
				}
			}else{
					return $this->xmlrpc->send_response('none');	
			}
			
				return $this->xmlrpc->send_response($response);
				
			}elseif ($topicMobileTag != '' && $date != '') { // retorna noticias do assunto (ID) a partir da data enviada
				
				if ($page == '') $page 						= 0;
				if ($limit == '') $limit 					= 10;
				
				$returnArticleTopicBySeoTag						= $this->Article->getArticleTopicForMobileBySeoTag($site,$date,$topicMobileTag,$topicForMobileType,$page,$limit);

				if($returnArticleTopicBySeoTag){
					foreach($returnArticleTopicBySeoTag->result() as $value){
						$returnMediaCast 	= '';
						$returnMediaVideo 	= '';
												
						if ($site == 'trip'){
							$returnMediaCast			= $this->Article->getPodcastByContentId($value->id);
							$returnMediaVideo			= $this->Article->getVideoByContentId($value->id);	
						
						if($returnMediaVideo){ //videocast
								$typeMedia					= "Videocast";
								$returnVideoLink 			= $returnMediaVideo[0]['tx_link'];
								$returnAudioLink			= "";
								$typeMediaExtension			= getMediaExtension($returnVideoLink,-3);
							
							}elseif($returnMediaCast){ //verifica se tem audio, ou seja, podcast
								$typeMedia					= "Podcast";
								$returnAudioLink 			= $returnMediaCast[0]['tx_link01'];
								$returnVideoLink			= "";
								$typeMediaExtension			= "";
							}elseif($returnMediaVideo == TRUE && $returnMediaCast == TRUE){	//verifica se tem audio e video
								$typeMedia					= "Videocast/Podcast";
								$returnVideoLink 			= $returnMediaVideo[0]['tx_link'];
								$returnAudioLink 			= $returnMediaCast[0]['tx_link01'];
								$typeMediaExtension			= getMediaExtension($returnVideoLink,-3);								
							}else{ //default
								$typeMedia					= "default";
								$returnVideoLink			= "";
								$returnAudioLink			= "";
								$typeMediaExtension			= "";
							}
						}elseif($site == 'tpm'){
							$returnMediaVideo			= $this->Article->getVideoTpmByContentId($value->id);
								
							if($returnMediaVideo){
								//videocast
								$typeMedia					= "Videocast";
								$returnVideoLink 			= $returnMediaVideo[0]['tx_link'];
								$returnAudioLink			= "";
								$typeMediaExtension			= getMediaExtension($returnVideoLink,-3);
							}

							$value->tx_conteudo_mobile 	= "";
						}					
						
						$value->type			= $typeMedia;
						$value->linkPodcast 	= $returnAudioLink;
						$value->linkVideocast 	= $returnVideoLink;
						$value->typeMediaExt 	= $typeMediaExtension;
					
					}
				
				$returnArticleTopicByIdMedia = $returnArticleTopicBySeoTag;				
				
				if ($returnArticleTopicByIdMedia){
				
					$arr = array ();
					
					foreach($returnArticleTopicByIdMedia->result() as $value){
					
							$arr[]	=	array(
												array(
														'contentId'  			=> array($value->id,'int'),
														'title'  				=> array($value->tx_titulo,'string'),
														'author'  				=> array($value->tx_texto,'string'),
														'imageCredit'  			=> array($value->tx_foto,'string'),
														'lead'  				=> array($value->tx_olho,'string'),
														'leadMobile'			=> array($value->tx_chamada,'string'),														
														'content'  				=> array(getMobileOrContent($value->tx_conteudo,$value->tx_conteudo_mobile),'string'),
														'date'  				=> array($value->dt_conteudo,'date'),
														'contentCover'			=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value->im_capa.'_._185_._320','string'),
														'contentThumb' 			=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value->im_capa.'_._60_._105','string'),
														'contentUrl'  			=> array($urlBase.$value->tx_url_completo,'string'),														
														'gallery'  				=> array($value->id_galeria,'int'),
														'type' 					=> array($value->type,'string'),
														'typeMediaExt'			=> array($value->typeMediaExt,'string'),														
														'linkPodcast' 			=> array($value->linkPodcast,'string'),
														'linkVideocast'			=> array($value->linkVideocast,'string')
													),'struct');
					}
					
					$response = array($arr,'array');
				}
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

	} /* END getTopicArticle */
	
	
	/********************************* 	
	Created : 24/08/2011
	by 		: Raphael Oliveira
	Updated	: 24/11/2011 
	**********************************/
	function getLastArticlesByTopicForMobile($request){
		$parameters = $request->output_parameters();
		
		//$parameters['0'] | token "token=".md5(tripwebservice); //0d80f42a1a7928acf49f5f81de9b4a1a
		//$parameters['1'] | site
		//$parameters['2'] | data 
		//$parameters['3'] | Seotag
		//$parameters['4'] | tagType   = (string) (section/topic)

		
		if ($parameters[0] != ''){ //token
			$auth 			= $this->System_model->verifyToken($parameters[0]);
		}

		if ($auth){
		
				$site 						= $parameters['1'];
				$date						= $parameters['2'];
				$seotag						= $parameters['3'];
				$tagType					= $parameters['4'];

				if($site == 'trip') 		$urlBase 		= 'http://revistatrip.uol.com.br';
				elseif($site == 'tpm') 		$urlBase 		= 'http://revistatpm.uol.com.br';				
		
			if ($seotag != '' && $date != '' && $tagType != '') {
				
				//if ($date == '') $date 				= '2000-06-07'; // data ex de data atual
				//if ($date == '') $date 				= '2010-04-09'; // data ex de data atual videocast
				//if ($date == '') $date 				= '2010-05-28'; // data ex de data atual podcast
				if ($date == '') $date 				= date("Y-m-d"); // data atual
				$page 			= 0;
				$limit 			= 10;
				
				$returnLastArticles				= $this->Article->getLastArticles($site,$date,$seotag,$tagType,$page,$limit);		//model ok		

				if ($returnLastArticles){
					foreach($returnLastArticles->result() as $value){
						$returnMediaCast 	= '';
						$returnMediaVideo 	= '';
						
						if ($site == 'trip'){
							$returnMediaCast			= $this->Article->getPodcastByContentId($value->id);
							$returnMediaVideo			= $this->Article->getVideoByContentId($value->id);	
							
							if($returnMediaVideo){ //videocast
								$typeMedia					= "Videocast";
								$returnVideoLink 			= $returnMediaVideo[0]['tx_link'];
								$returnAudioLink			= "";
								$typeMediaExtension			= getMediaExtension($returnVideoLink,-3);
							}
							if($returnMediaCast){ //verifica se tem audio, ou seja, podcast
								$typeMedia					= "Podcast";
								$returnAudioLink 			= $returnMediaCast[0]['tx_link01'];
								$returnVideoLink			= "";
								$typeMediaExtension			= "";
							}
							if($returnMediaVideo == TRUE && $returnMediaCast == TRUE){	//verifica se tem audio e video
								$typeMedia					= "Videocast/Podcast";
								$returnVideoLink 			= $returnMediaVideo[0]['tx_link'];
								$returnAudioLink 			= $returnMediaCast[0]['tx_link01'];
								$typeMediaExtension			= getMediaExtension($returnVideoLink,-3);
							}
							if($returnMediaVideo == FALSE && $returnMediaCast == FALSE){ //default
								$typeMedia					= "default";
								$returnVideoLink			= "";
								$returnAudioLink			= "";
								$typeMediaExtension			= "";
							}
						}elseif($site == 'tpm'){
							$returnMediaVideo			= $this->Article->getVideoTpmByContentId($value->id);
							
							if($returnMediaVideo){
								//videocast
								$typeMedia					= "Videocast";
								$returnVideoLink 			= $returnMediaVideo[0]['tx_link'];
								$returnAudioLink			= "";
								$typeMediaExtension			= getMediaExtension($returnVideoLink,-3);
							}							
							$value->tx_conteudo_mobile 	= "";
						}					
						
						$value->type			= $typeMedia;
						$value->linkPodcast 	= $returnAudioLink;
						$value->linkVideocast 	= $returnVideoLink;
						$value->typeMediaExt 	= $typeMediaExtension;
						
					
					}
					$returnTopicArticleAllMedia = $returnLastArticles;
				
					if ($returnTopicArticleAllMedia){
					
						$arr = array();
						
						foreach($returnTopicArticleAllMedia->result() as $index => $value){
								
							$arr[]	=	array(
												array(
														'contentId'  			=> array($value->id,'int'),
														'title'  				=> array($value->tx_titulo,'string'),
														'author'  				=> array($value->tx_texto,'string'),
														'imageCredit'  			=> array($value->tx_foto,'string'),
														'lead'  				=> array($value->tx_olho,'string'),
														'leadMobile'			=> array($value->tx_chamada,'string'),
														'content'  				=> array(getMobileOrContent($value->tx_conteudo,$value->tx_conteudo_mobile),'string'),
														'date'  				=> array($value->dt_conteudo,'date'),
														'contentCover'			=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value->im_capa.'_._185_._320','string'),
														'contentThumb' 			=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value->im_capa.'_._60_._105','string'),
														'contentUrl'  			=> array($urlBase.$value->tx_url_completo,'string'),														
														'gallery'  				=> array($value->id_galeria,'int'),
														'type' 					=> array($value->type,'string'),
														'typeMediaExt'			=> array($value->typeMediaExt,'string'),														
														'linkPodcast' 			=> array($value->linkPodcast,'string'),
														'linkVideocast'			=> array($value->linkVideocast,'string')
													),'struct');
						
						}
						
						$response = array($arr,'array');
					}				

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

	} /* END getLastArticlesByTopicForMobile */
	
}

