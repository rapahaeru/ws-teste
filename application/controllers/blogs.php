<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blogs extends CI_Controller {
	public function __construct()
       {
			parent::__construct();
			$this->load->model('Blogs_model', 'Blogs');
			$this->load->model('Article_model', 'Article');	
			$this->load->model('System_model');	
       }

	public function index(){
		$config['functions']['getBlogs'] 						= array('function' => 'Blogs.getBlogs');
		$config['functions']['getBlogsList'] 						= array('function' => 'Blogs.getBlogsList');

		$config['object'] 										= $this;

		$this->xmlrpcs->initialize($config);
		$this->xmlrpcs->serve();

	}
	
	
	function getBlogs($request){
		$parameters = $request->output_parameters();
		
		//var_dump($parameters);
				//$parameters['0'] | token "token=".md5(tripwebservice); //0d80f42a1a7928acf49f5f81de9b4a1a
				//$parameters['2'] | site
				//$parameters['2'] | BlogId
				//$parameters['3'] | page
				//$parameters['4'] | limit
				
		
		if ($parameters[0] != ''){ //token
			$auth = $this->System_model->verifyToken($parameters[0]);
		}

		if ($auth){
			if ($parameters['1'] != '' && $parameters['2'] == '') {
				//$contentId 					= $parameters['2'];
				$site 						= $parameters['1'];
				$page						= $parameters['3'];
				$limit						= $parameters['4'];
				//indices
				
				//cria base url dos respectivos sites
				if($site == 'trip') 		$urlBase 		= 'http://revistatrip.uol.com.br';
				elseif($site == 'tpm') 		$urlBase 		= 'http://revistatpm.uol.com.br';
				
				if ($page == '') $page = 0;
				if ($limit == '') $limit = 10;
				
				
				$returnBlogs 					= $this->Blogs->getPostBlogs($site,$page,$limit);
				
				//var_dump($return->result());
				if ($returnBlogs){
					foreach($returnBlogs->result() as $value){
						$returnMediaCast 	= '';
						$returnMediaVideo 	= '';
						
						if ($site == 'trip'){
							$returnMediaCast			= $this->Article->getPodcastByContentId($value->id);
							$returnMediaVideo			= $this->Article->getVideoByContentId($value->id);	
						
							if($returnMediaVideo){ //videocast
								$returnMediaString 			= $returnMediaVideo[0]['tx_link'];
								$typeMedia					= "Videocast";
								$returnMediaLink 			= $returnMediaVideo[0]['tx_link'];
							
							}elseif($returnMediaCast){ //verifica se tem audio, ou seja, podcast
								$returnMediaString 			= $returnMediaCast[0]['tx_link01'];
								$returnMediaLink 			= $returnMediaCast[0]['tx_link01'];
								$typeMedia					= "Podcast";
							
							}else{ //default
								$returnMediaLink 			= "";
								$typeMedia					= "default";
							}
							
						}elseif($site == 'tpm'){
							$returnMediaLink 			= "";
							$typeMedia					= "";
							
						}					
						
						$value->type	= $typeMedia;
						$value->link 	= $returnMediaLink;
					
					}
					$return = $returnBlogs;
				
					if ($return){
					
						$arr = array();
						
						foreach($return->result() as $index => $value){
								
							$arr[]	=	array(
											array(
													'blogId'  			=> array($value->blog_id,'int'),
													'blogName'  		=> array($value->blog_name,'string'),
													'blogSeo' 			=> array($value->blog_seo,'string'),
													'blogDescription' 	=> array($value->blog_descricao,'string'),											
													'postId'  			=> array($value->id,'int'),
													'postTitle'  		=> array($value->tx_titulo,'string'),
													'postContent'  		=> array(htmlDecode($value->tx_conteudo),'string'),
													'postAuthor'  		=> array($value->tx_texto,'string'),
													'postDate'  		=> array($value->dt_conteudo,'date'),
													'contentCover' 		=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value->im_capa.'_._185_._320','string'),
													'contentThumb' 		=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value->im_capa.'_._110_._115','string'),
													'postUrl'  			=> array($urlBase.$value->tx_url_completo,'string'),													
													'postGallery'  		=> array($value->id_galeria,'int'),
													'type' 				=> array($typeMedia,'string'),
													'link' 				=> array($returnMediaLink,'string')
											), 'struct');				
						
						}
						
						$response = array($arr,'array');

					}				
				
				}else{
					return $this->xmlrpc->send_response('none');
				}
				
				return $this->xmlrpc->send_response($response);
				
			}elseif ($parameters['1'] != '' && $parameters['2'] != '') {
				$blogId 					= $parameters['2'];
				$site 						= $parameters['1'];	
				$page						= $parameters['3'];
				$limit						= $parameters['4'];
				//indices
				
				//cria base url dos respectivos sites
				if($site == 'trip') 		$urlBase 		= 'http://revistatrip.uol.com.br';
				elseif($site == 'tpm') 		$urlBase 		= 'http://revistatpm.uol.com.br';
				
				if ($page == '') $page = 0;
				if ($limit == '') $limit = 10;
				
				
				$returnBlogs 					= $this->Blogs->getPostByIdBlog($site,$blogId,$page,$limit);
				
				//var_dump($return->result());
				if ($returnBlogs){
					foreach($returnBlogs->result() as $value){
						$returnMediaCast 	= '';
						$returnMediaVideo 	= '';
						
						if ($site == 'trip'){
							$returnMediaCast			= $this->Article->getPodcastByContentId($value->id);
							$returnMediaVideo			= $this->Article->getVideoByContentId($value->id);	
						
							if($returnMediaVideo){ //videocast
								$returnMediaString 			= $returnMediaVideo[0]['tx_link'];
								$typeMedia					= "Videocast";
								$returnMediaLink 			= $returnMediaVideo[0]['tx_link'];
							
							}elseif($returnMediaCast){ //verifica se tem audio, ou seja, podcast
								$returnMediaString 			= $returnMediaCast[0]['tx_link01'];
								$returnMediaLink 			= $returnMediaCast[0]['tx_link01'];
								$typeMedia					= "Podcast";
							
							}else{ //default
								$returnMediaLink 			= "";
								$typeMedia					= "default";
							}
							
						}elseif($site == 'tpm'){
							$returnMediaLink 			= "";
							$typeMedia					= "";
							
						}					
						
						$value->type	= $typeMedia;
						$value->link 	= $returnMediaLink;
					
					}
					$return = $returnBlogs;
				
					if ($return){
					
						$arr = array();
						
						foreach($return->result() as $index => $value){
								
							$arr[]	=	array(
											array(
													'blogId'  			=> array($value->blog_id,'int'),
													'blogName'  		=> array($value->blog_name,'string'),
													'blogSeo' 			=> array($value->blog_seo,'string'),
													'blogDescription' 	=> array($value->blog_descricao,'string'),											
													'postId'  			=> array($value->id,'int'),
													'postTitle'  		=> array($value->tx_titulo,'string'),
													'postContent'  		=> array(htmlDecode($value->tx_conteudo),'string'),
													'postAuthor'  		=> array($value->tx_texto,'string'),
													'postDate'  		=> array($value->dt_conteudo,'date'),
													'contentCover' 		=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value->im_capa.'_._185_._320','string'),
													'contentThumb' 		=> array($urlBase.'/_lib/common/imgCrop.php?params='.$value->im_capa.'_._110_._115','string'),
													'postUrl'  			=> array($urlBase.$value->tx_url_completo,'string'),																										
													'postGallery'  		=> array($value->id_galeria,'int'),
													'type' 				=> array($typeMedia,'string'),
													'link' 				=> array($returnMediaLink,'string')
											), 'struct');				
						
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
	
	} /* FIM getBlogs() */
	
	function getBlogsList($request){
		$parameters = $request->output_parameters();
		
		//var_dump($parameters);
		//$parameters['0'] | token "token=".md5(tripwebservice); //0d80f42a1a7928acf49f5f81de9b4a1a
		//$parameters['2'] | site

		if ($parameters[0] != ''){ //token
			$auth = $this->System_model->verifyToken($parameters[0]);
		}

		if ($auth){
			if ($parameters['1'] != '' ) {
				$site 						= $parameters['1'];

				//cria base url dos respectivos sites
				if($site == 'trip') 		$urlBase 		= 'http://revistatrip.uol.com.br';
				elseif($site == 'tpm') 		$urlBase 		= 'http://revistatpm.uol.com.br';
				
				$returnBlogList					= $this->Blogs->getBlogsListBySite($site);
				
				//var_dump($return->result());
				if ($returnBlogList){
				
						$arr = array();
						
						foreach($returnBlogList->result() as $index => $value){
								
							$arr[]	=	array(
											array(
													'blogId'  			=> array($value->blog_id,'int'),
													'blogName'  		=> array($value->blog_name,'string'),
													'blogSeo' 			=> array($value->blog_seo,'string'),
													'blogDescription' 	=> array($value->blog_descricao,'string')
											), 'struct');				
						
						}
						
						$response = array($arr,'array');

				}else{
					return $this->xmlrpc->send_response('none');
				}
				
				return $this->xmlrpc->send_response($response);
				

		}else{
			return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		}
	
	}
	
}

	
	
 
}

