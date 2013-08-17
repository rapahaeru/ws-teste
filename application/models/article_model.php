<?php

class Article_model extends CI_Model {

	function getArticleById($contentId, $site){
		$this->db->select($site.'_conteudo.id');
		$this->db->select($site.'_conteudo.tx_titulo');
		$this->db->select($site.'_conteudo.tx_texto');
		$this->db->select($site.'_conteudo.tx_olho');
		$this->db->select($site.'_conteudo.tx_chamada');
		$this->db->select($site.'_conteudo.tx_conteudo');
		$this->db->select($site.'_conteudo.tx_conteudo_mobile');
		$this->db->select($site.'_conteudo.dt_conteudo');
		$this->db->select($site.'_conteudo.im_capa');
		$this->db->select($site.'_conteudo.id_galeria');
		$this->db->select($site.'_assunto.id AS assunto_id');
		$this->db->select($site.'_assunto.tx_assunto');			
	
 		$this->db->where($site.'_conteudo.id',$contentId);

		$this->db->join($site.'_conteudo_assunto',$site.'_conteudo_assunto.id_conteudo ='.$site.'_conteudo.id ','LEFT');
		$this->db->join($site.'_assunto',$site.'_assunto.id ='.$site.'_conteudo_assunto.id_assunto ','LEFT');
		
		$q = $this->db->get($site.'_conteudo');
		if ($q->num_rows() > 0){
			//die ($q->result_array());
			//return $q;
			return $q->result_array();
		}else{
			return FALSE;
		}
	}
		
	function getKeywordsByContentId($contentId,$site){
	 	$this->db->select($site.'_tag.tx_tag');
		$this->db->join($site.'_conteudo_tag',$site.'_conteudo_tag.id_conteudo = '.$site.'_conteudo.id','inner');
		$this->db->join($site.'_tag',$site.'_tag.id = '.$site.'_conteudo_tag.id_tag','inner');
		$this->db->where($site.'_conteudo.id',$contentId);
		$q = $this->db->get($site.'_conteudo');
		if ($q->num_rows() > 0){
			return $q->result_array();
		}else{
			return FALSE;
		}
	}

	function getPodcastByContentId($contentId){
		$this->db->select('trip_audio.tx_link01,trip_audio.tx_link02');
		$this->db->join('trip_audio','trip_audio.id = trip_conteudo.id_audio','inner');
		$this->db->where('trip_conteudo.id',$contentId);
		$q = $this->db->get('trip_conteudo');
		//echo $this->db->last_query();
		if ($q->num_rows() > 0){
			return $q->result_array();
		}else{
			return FALSE;
		}
	
	}
	
	function getVideoByContentId($contentId){
		$this->db->select('trip_video.tx_link');
		$this->db->join('trip_video','trip_video.id = trip_conteudo.id_video','inner');
		$this->db->where('trip_conteudo.id',$contentId);
		$q = $this->db->get('trip_conteudo');
		//echo $this->db->last_query();
		
		if ($q->num_rows() > 0){
			return $q->result_array();
		}else{
			return FALSE;
		}
	
	}
	
	function getVideoTpmByContentId($contentId){
		$this->db->select('tpm_video.tx_link');
		$this->db->join('tpm_video','tpm_video.id = tpm_conteudo.id_video','inner');
		$this->db->where('tpm_conteudo.id',$contentId);
		$q = $this->db->get('tpm_conteudo');
		//echo $this->db->last_query();
	
		if ($q->num_rows() > 0){
			return $q->result_array();
		}else{
			return FALSE;
		}
	
	}	
	
	function getArticleBySearchWords($searchWords,$site,$page,$limit,$order){
			
		//var_dump($searchWords);
	
		$this->db->select($site.'_conteudo.id');
		$this->db->select($site.'_conteudo.tx_titulo');
		$this->db->select($site.'_conteudo.tx_texto');
		$this->db->select($site.'_conteudo.tx_olho');
		$this->db->select($site.'_conteudo.tx_conteudo');
		$this->db->select($site.'_conteudo.dt_conteudo');
		$this->db->select($site.'_conteudo.im_capa');
		$this->db->select($site.'_conteudo.id_galeria');
		$this->db->select($site.'_assunto.id AS assunto_id');
		$this->db->select($site.'_assunto.tx_assunto');				

		$this->db->join($site.'_video',$site.'_video.id = '.$site.'_conteudo.id_video','left');
		$this->db->join($site.'_conteudo_tag',$site.'_conteudo_tag.id_conteudo = '.$site.'_conteudo.id','left');
		$this->db->join($site.'_tag',$site.'_tag.id = '.$site.'_conteudo_tag.id_tag','left');
		$this->db->join($site.'_conteudo_assunto',$site.'_conteudo_assunto.id_conteudo ='.$site.'_conteudo.id ','LEFT');
		$this->db->join($site.'_assunto',$site.'_assunto.id ='.$site.'_conteudo_assunto.id_assunto ','LEFT');
		
		if (sizeof($searchWords) > 1){
			$this->db->like($site.'_conteudo.tx_titulo',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_olho',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_conteudo',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_texto',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_foto',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_filme',$searchWords[0]);
			$this->db->or_like($site.'_tag.tx_tag',$searchWords[0]);

			$this->db->or_like($site.'_conteudo.tx_titulo',$searchWords[1]);
			$this->db->or_like($site.'_conteudo.tx_olho',$searchWords[1]);
			$this->db->or_like($site.'_conteudo.tx_conteudo',$searchWords[1]);
			$this->db->or_like($site.'_conteudo.tx_texto',$searchWords[1]);
			$this->db->or_like($site.'_conteudo.tx_foto',$searchWords[1]);
			$this->db->or_like($site.'_conteudo.tx_filme',$searchWords[1]);
			$this->db->or_like($site.'_tag.tx_tag',$searchWords[1]);
			
		}else{
			$this->db->like($site.'_conteudo.tx_titulo',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_olho',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_conteudo',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_texto',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_foto',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_filme',$searchWords[0]);
			$this->db->or_like($site.'_tag.tx_tag',$searchWords[0]);		
		}
		


		$this->db->where($site.'_conteudo.in_ativo',1);
		$this->db->where($site.'_conteudo.in_tipo',0); // materia
		
		$this->db->limit($limit,$page);
		
		if ($order == 'date') $this->db->order_by($site.'_conteudo.dt_conteudo','DESC');
		elseif ($order == 'title') $this->db->order_by($site.'_conteudo.tx_titulo','ASC');
		else $this->db->order_by($site.'_conteudo.id','RANDOM');
		
		$q = $this->db->get($site.'_conteudo');
		//echo $this->db->last_query();

  		if ($q->num_rows() > 0){
			return $q->result_array();
		}else{
			return FALSE;
		}

	}
	
	function getCountArticleBySearchWords($searchWords,$site){
		$this->db->select('COUNT('.$site.'_conteudo.id) AS total');

		$this->db->join($site.'_video',$site.'_video.id = '.$site.'_conteudo.id_video','left');
		$this->db->join($site.'_conteudo_tag',$site.'_conteudo_tag.id_conteudo = '.$site.'_conteudo.id','left');
		$this->db->join($site.'_tag',$site.'_tag.id = '.$site.'_conteudo_tag.id_tag','left');
		$this->db->join($site.'_conteudo_assunto',$site.'_conteudo_assunto.id_conteudo ='.$site.'_conteudo.id ','LEFT');
		$this->db->join($site.'_assunto',$site.'_assunto.id ='.$site.'_conteudo_assunto.id_assunto ','LEFT');		
		
		if (sizeof($searchWords) > 1){
			$this->db->like($site.'_conteudo.tx_titulo',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_olho',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_conteudo',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_texto',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_foto',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_filme',$searchWords[0]);
			$this->db->or_like($site.'_tag.tx_tag',$searchWords[0]);

			$this->db->or_like($site.'_conteudo.tx_titulo',$searchWords[1]);
			$this->db->or_like($site.'_conteudo.tx_olho',$searchWords[1]);
			$this->db->or_like($site.'_conteudo.tx_conteudo',$searchWords[1]);
			$this->db->or_like($site.'_conteudo.tx_texto',$searchWords[1]);
			$this->db->or_like($site.'_conteudo.tx_foto',$searchWords[1]);
			$this->db->or_like($site.'_conteudo.tx_filme',$searchWords[1]);
			$this->db->or_like($site.'_tag.tx_tag',$searchWords[1]);
			
		}else{
			$this->db->like($site.'_conteudo.tx_titulo',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_olho',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_conteudo',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_texto',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_foto',$searchWords[0]);
			$this->db->or_like($site.'_conteudo.tx_filme',$searchWords[0]);
			$this->db->or_like($site.'_tag.tx_tag',$searchWords[0]);		
		}
		
		$this->db->where($site.'_conteudo.in_ativo',1);
		$this->db->where($site.'_conteudo.in_tipo',0); // materia		

		
		$q = $this->db->get($site.'_conteudo');
		//echo $this->db->last_query();

 		if ($q->num_rows() > 0){
			return $q->result_array();
		}else{
			return FALSE;
		}
	
	}
	
	/* getTopicArticle */
	function getArticleByTopicAll($site,$date,$page,$limit){
	
		$this->db->select($site.'_conteudo.id');
		$this->db->select($site.'_conteudo.tx_titulo');
		$this->db->select($site.'_conteudo.tx_texto');
		$this->db->select($site.'_conteudo.tx_olho');
		$this->db->select($site.'_conteudo.tx_conteudo');
		$this->db->select($site.'_conteudo.tx_conteudo_mobile');
		$this->db->select($site.'_conteudo.dt_conteudo');
		$this->db->select($site.'_conteudo.im_capa');
		$this->db->select($site.'_conteudo.id_galeria');
		$this->db->select($site.'_conteudo.dt_conteudo');
		$this->db->select($site.'_assunto.id AS assunto_id');
		$this->db->select($site.'_assunto.tx_assunto');		
	
		$this->db->join($site.'_conteudo_assunto',$site.'_conteudo_assunto.id_conteudo ='.$site.'_conteudo.id ','INNER');
		$this->db->join($site.'_assunto',$site.'_assunto.id ='.$site.'_conteudo_assunto.id_assunto ','INNER');
		
		$this->db->where($site.'_conteudo.dt_conteudo',$date);
		$this->db->where($site.'_conteudo.in_ativo',1);
		$this->db->where($site.'_conteudo.in_tipo',0); // materia

		$this->db->limit($limit,$page);
		
		$this->db->order_by($site.'_conteudo.dt_conteudo','DESC');
		$this->db->group_by($site.'_conteudo.id');
		
		$q = $this->db->get($site.'_conteudo');
		//echo $this->db->last_query();

 		if ($q->num_rows() > 0){
			return $q;
		}else{
			return FALSE;
		}		
		
	}

	/* getTopicArticle */
	function getArticleByTopicId($site,$date,$idTopic,$page,$limit){
		$this->db->select($site.'_conteudo.id');
		$this->db->select($site.'_conteudo.tx_titulo');
		$this->db->select($site.'_conteudo.tx_texto');
		$this->db->select($site.'_conteudo.tx_olho');
		$this->db->select($site.'_conteudo.tx_conteudo');
		$this->db->select($site.'_conteudo.tx_conteudo_mobile');
		$this->db->select($site.'_conteudo.dt_conteudo');
		$this->db->select($site.'_conteudo.im_capa');
		$this->db->select($site.'_conteudo.id_galeria');
		$this->db->select($site.'_conteudo.dt_conteudo');
		$this->db->select($site.'_assunto.id AS assunto_id');
		$this->db->select($site.'_assunto.tx_assunto');		
	
		$this->db->join($site.'_conteudo_assunto',$site.'_conteudo_assunto.id_conteudo ='.$site.'_conteudo.id ','INNER');
		$this->db->join($site.'_assunto',$site.'_assunto.id ='.$site.'_conteudo_assunto.id_assunto ','INNER');
		
		$this->db->where($site.'_conteudo.dt_conteudo',$date);
		$this->db->where($site.'_assunto.id',$idTopic);
		$this->db->where($site.'_conteudo.in_ativo',1);
		$this->db->where($site.'_conteudo.in_tipo',0); // materia

		$this->db->limit($limit,$page);
		
		$this->db->order_by($site.'_conteudo.dt_conteudo','DESC');
		$this->db->group_by($site.'_conteudo.id');
		
		$q = $this->db->get($site.'_conteudo');
		//echo $this->db->last_query();

 		if ($q->num_rows() > 0){
			return $q;
		}else{
			return FALSE;
		}		
	
	}
	
	/* getTopicArticle */
	function getTopicArticleByDate($site,$date,$page,$limit){
		$this->db->select($site.'_conteudo.id');
		$this->db->select($site.'_conteudo.tx_titulo');
		$this->db->select($site.'_conteudo.tx_texto');
		$this->db->select($site.'_conteudo.tx_olho');
		$this->db->select($site.'_conteudo.tx_conteudo');
		$this->db->select($site.'_conteudo.tx_conteudo_mobile');
		$this->db->select($site.'_conteudo.dt_conteudo');
		$this->db->select($site.'_conteudo.im_capa');
		$this->db->select($site.'_conteudo.id_galeria');
		$this->db->select($site.'_conteudo.dt_conteudo');
		$this->db->select($site.'_assunto.id AS assunto_id');
		$this->db->select($site.'_assunto.tx_assunto');		
	
		$this->db->join($site.'_conteudo_assunto',$site.'_conteudo_assunto.id_conteudo ='.$site.'_conteudo.id ','INNER');
		$this->db->join($site.'_assunto',$site.'_assunto.id ='.$site.'_conteudo_assunto.id_assunto ','INNER');
		
		$this->db->where($site.'_conteudo.dt_conteudo',$date);
		$this->db->where($site.'_conteudo.in_ativo',1);
		$this->db->where($site.'_conteudo.in_tipo',0); // materia

		$this->db->limit($limit,$page);
		
		$this->db->order_by($site.'_conteudo.dt_conteudo','DESC');
		$this->db->group_by($site.'_conteudo.id');		
		
		$q = $this->db->get($site.'_conteudo');
		//echo $this->db->last_query();

 		if ($q->num_rows() > 0){
			return $q;
		}else{
			return FALSE;
		}		
	
	}

	function getIdArticlesHome($site){

		$this->db->select($site.'_home.id_dg');
		$this->db->select($site.'_home.id_dm');
		$this->db->select($site.'_home.id_dp1');
		$this->db->select($site.'_home.id_dp2');
		$this->db->select($site.'_home.id_dp3');
		$this->db->select($site.'_home.id_dp4');
	
		
	
		//$this->db->join($site.'_conteudo_assunto',$site.'_conteudo_assunto.id_conteudo ='.$site.'_conteudo.id ','INNER');
		//$this->db->join($site.'_assunto',$site.'_assunto.id ='.$site.'_conteudo_assunto.id_assunto ','INNER');
		
		$this->db->where($site.'_home.in_ativo',1);
		$this->db->order_by($site.'_home.dt_home','DESC');
		$this->db->limit(1);

		$q = $this->db->get($site.'_home');
		//echo $this->db->last_query();

 		if ($q->num_rows() > 0){
			return $q->result_array();
		}else{
			return FALSE;
		}		
	
	}

	/* getArticlesHome */
	function getArticlesHome($site,$string){

		$this->db->select($site.'_conteudo.id');
		$this->db->select($site.'_conteudo.tx_titulo');
		$this->db->select($site.'_conteudo.tx_texto');
		$this->db->select($site.'_conteudo.tx_foto');
		$this->db->select($site.'_conteudo.tx_olho');
		$this->db->select($site.'_conteudo.tx_conteudo');
		$this->db->select($site.'_conteudo.tx_conteudo_mobile');
		$this->db->select($site.'_conteudo.dt_conteudo');
		$this->db->select($site.'_conteudo.im_capa');
		$this->db->select($site.'_conteudo.id_galeria');
		$this->db->select($site.'_conteudo.tx_url_completo');				
		$this->db->select($site.'_assunto.id AS assunto_id');
		$this->db->select($site.'_assunto.tx_assunto');				

		$this->db->join($site.'_conteudo_assunto',$site.'_conteudo_assunto.id_conteudo ='.$site.'_conteudo.id ','LEFT');
		$this->db->join($site.'_assunto',$site.'_assunto.id ='.$site.'_conteudo_assunto.id_assunto ','LEFT');
		
		$this->db->where($site.'_conteudo.in_ativo',1);
		$this->db->where($site.'_conteudo.id in ('.$string.')');
		$this->db->group_by($site.'_conteudo.id');
		$this->db->order_by($site.'_conteudo.dt_conteudo','DESC');

		$q = $this->db->get($site.'_conteudo');
		//echo $this->db->last_query();

 		if ($q->num_rows() > 0){
			return $q->result_array();
		}else{
			return FALSE;
		}		
	
	}
	
	function getTopics($site){

		$this->db->select($site.'_assunto.id');
		$this->db->select($site.'_assunto.tx_assunto');
		$this->db->select($site.'_assunto.id_assunto');
		$this->db->select($site.'_assunto.tx_cor');
		$this->db->select($site.'_assunto.tx_seo');
	
		$this->db->where($site.'_assunto.in_ativo',1);
		$this->db->order_by($site.'_assunto.tx_assunto','asc');


		$q = $this->db->get($site.'_assunto');
		//echo $this->db->last_query();

 		if ($q->num_rows() > 0){
			return $q;
			
		}else{
			return FALSE;
		}		
	
	}
	
	function getTopicById($site,$topicId){

		$this->db->select($site.'_assunto.id');
		$this->db->select($site.'_assunto.tx_assunto');
		$this->db->select($site.'_assunto.id_assunto');
		$this->db->select($site.'_assunto.tx_cor');
		$this->db->select($site.'_assunto.tx_seo');
	
		$this->db->where($site.'_assunto.in_ativo',1);
		$this->db->where($site.'_assunto.id',$topicId);
		$this->db->order_by($site.'_assunto.tx_assunto','asc');

		$q = $this->db->get($site.'_assunto');
		//echo $this->db->last_query();

 		if ($q->num_rows() > 0){
			return $q;
			
		}else{
			return FALSE;
		}		
	
	}
	
	/* Articles.getTopicsForMobile */
	function getTopicBySeo($site,$topicSeo){

		$this->db->select($site.'_assunto.id as id');
		$this->db->select($site.'_assunto.tx_assunto as name');
		$this->db->select($site.'_assunto.tx_seo as seo');
	
		$this->db->where($site.'_assunto.in_ativo',1);
		$this->db->where($site.'_assunto.tx_seo',$topicSeo);
		$this->db->limit(1);

		$q = $this->db->get($site.'_assunto');
		//echo $this->db->last_query();

 		if ($q->num_rows() > 0){
			return $q->result_array();
			
		}else{
			return FALSE;
		}		
	
	}

	/* Articles.getTopicsForMobile */	
	function getSectionBySeo($site,$SectionSeo){

		$this->db->select($site.'_secao.id as id');
		$this->db->select($site.'_secao.tx_secao as name');
		$this->db->select($site.'_secao.tx_seo as seo');
	
		$this->db->where($site.'_secao.in_ativo',1);
		$this->db->where($site.'_secao.tx_seo',$SectionSeo);
		$this->db->limit(1);
		
		$q = $this->db->get($site.'_secao');
		//echo $this->db->last_query();

 		if ($q->num_rows() > 0){
			return $q->result_array();
			
		}else{
			return FALSE;
		}		
	
	}
		
		
		
	/* getArticleTopicsForMobile */
 	function getArticleTopicForMobileAll($site,$date,$str_topicsForMobile,$page,$limit){
	
//  		$topicsForMobileCulturaPop 		= array();
//  		$topicsForMobileCulturaPop[0] 	= array('seo' => 'artes','type' => 'assunto');
//  		$topicsForMobileCulturaPop[1] 	= array('seo' => 'cinema','type' => 'assunto');
//  		$topicsForMobileCulturaPop[2] 	= array('seo' => 'design','type' => 'assunto');
//  		$topicsForMobileCulturaPop[3] 	= array('seo' => 'fotografia','type' => 'assunto');
//  		$topicsForMobileCulturaPop[4] 	= array('seo' => 'livros','type' => 'assunto');
//  		$topicsForMobileCulturaPop[5] 	= array('seo' => 'musica','type' => 'assunto');
//  		$topicsForMobileCulturaPop[6] 	= array('seo' => 'quadrinho','type' => 'assunto');
 			
 		
		$this->db->select($site.'_conteudo.id');
		$this->db->select($site.'_conteudo.tx_titulo');
		$this->db->select($site.'_conteudo.tx_texto');
		$this->db->select($site.'_conteudo.tx_foto');
		$this->db->select($site.'_conteudo.tx_olho');
		$this->db->select($site.'_conteudo.tx_chamada');
		$this->db->select($site.'_conteudo.tx_conteudo');
		if ($site == 'trip') $this->db->select($site.'_conteudo.tx_conteudo_mobile');
		$this->db->select($site.'_conteudo.dt_conteudo');
		$this->db->select($site.'_conteudo.im_capa');
		$this->db->select($site.'_conteudo.id_galeria');
		$this->db->select($site.'_conteudo.dt_conteudo');
		$this->db->select($site.'_conteudo.tx_url_completo');
		$this->db->select($site.'_assunto.id AS assunto_id');
		$this->db->select($site.'_assunto.tx_assunto');
		$this->db->select($site.'_assunto.tx_seo AS assunto_seo');
		$this->db->select($site.'_secao.id AS secao_id');
		$this->db->select($site.'_secao.tx_secao');
		$this->db->select($site.'_secao.tx_seo AS section_seo');
	
		$this->db->join($site.'_conteudo_assunto',$site.'_conteudo_assunto.id_conteudo ='.$site.'_conteudo.id ','LEFT');
		$this->db->join($site.'_assunto',$site.'_assunto.id ='.$site.'_conteudo_assunto.id_assunto ','LEFT');
		$this->db->join($site.'_conteudo_secao',$site.'_conteudo_secao.id_conteudo ='.$site.'_conteudo.id ','LEFT');
		$this->db->join($site.'_secao',$site.'_secao.id ='.$site.'_conteudo_secao.id_secao ','LEFT');
		
		//$this->db->where($site.'_conteudo.dt_conteudo',$date);
		//$this->db->where($site.'_conteudo.in_ativo',1);
		//$this->db->where($site.'_conteudo.in_tipo',0); // materia
		$w = '';
		$w = $site.'_conteudo.dt_conteudo = "'.$date.'" AND '.$site.'_conteudo.in_ativo = 1 AND '.$site.'_conteudo.in_tipo = 0';
		$w .= ' AND (';
		
		for($c=0; $c<sizeof($str_topicsForMobile); $c++){
			
			if ($str_topicsForMobile[$c]['type'] == 'secao') {
				$w .= $site.'_secao.tx_seo = "'.$str_topicsForMobile[$c]['seo'].'"';
				$w .= ' OR ';
			}elseif ($str_topicsForMobile[$c]['type'] == 'assunto'){
				$w .= $site.'_assunto.tx_seo = "'.$str_topicsForMobile[$c]['seo'].'"';
				$w .= ' OR ';					
			}
		}
		$w = substr($w,0,-4);
		$w.= ")";

		$this->db->where($w);
		$this->db->limit($limit,$page);
		
		$this->db->order_by($site.'_conteudo.dt_conteudo','DESC');
		$this->db->group_by($site.'_conteudo.id');
		
		$q = $this->db->get($site.'_conteudo');
		//echo $this->db->last_query();

 		if ($q->num_rows() > 0){
			return $q;
		}else{
			return FALSE;
		}		
		
	}
	
	/* getArticleTopicsForMobile */
	function getInfoBySectionId($site,$secion_id,$article_id){
		$this->db->select($site.'_conteudo.id');
		$this->db->select($site.'_secao.tx_seo');
		
		$this->db->join($site.'_conteudo_secao',$site.'_conteudo_secao.id_conteudo ='.$site.'_conteudo.id ','INNER');
		$this->db->join($site.'_secao',$site.'_secao.id ='.$site.'_conteudo_secao.id_secao ','INNER');
		
		$this->db->where($site.'_conteudo.in_ativo',1);
		$this->db->where($site.'_conteudo.in_tipo',0); // materia
		$this->db->where($site.'_secao.tx_seo',$secion_id);
		$this->db->where($site.'_conteudo.id',$article_id);
		
		$this->db->limit(1);
		$q = $this->db->get($site.'_conteudo');
		//echo $this->db->last_query();

 		if ($q->num_rows() > 0){
			return $q->result_array();
		}else{
			return FALSE;
		}		
		
	}

	/* getArticleTopicsForMobile */
 	function getArticleTopicForMobileBySeoTag($site,$date,$topicMobileTag,$topicForMobileType,$page,$limit){
		
		$this->db->select($site.'_conteudo.id');
		$this->db->select($site.'_conteudo.tx_titulo');
		$this->db->select($site.'_conteudo.tx_texto');
		$this->db->select($site.'_conteudo.tx_foto');		
		$this->db->select($site.'_conteudo.tx_olho');
		$this->db->select($site.'_conteudo.tx_chamada');
		$this->db->select($site.'_conteudo.tx_conteudo');
		if ($site == 'trip') $this->db->select($site.'_conteudo.tx_conteudo_mobile');		
		$this->db->select($site.'_conteudo.dt_conteudo');
		$this->db->select($site.'_conteudo.im_capa');
		$this->db->select($site.'_conteudo.id_galeria');
		$this->db->select($site.'_conteudo.dt_conteudo');
		$this->db->select($site.'_conteudo.tx_url_completo');		
		
		if ($topicForMobileType == 'section'){
			$this->db->select($site.'_secao.id AS secao_id');
			$this->db->select($site.'_secao.tx_secao');
			$this->db->select($site.'_secao.tx_seo AS section_seo');
			
			$this->db->join($site.'_conteudo_secao',$site.'_conteudo_secao.id_conteudo ='.$site.'_conteudo.id ','INNER');
			$this->db->join($site.'_secao',$site.'_secao.id ='.$site.'_conteudo_secao.id_secao ','INNER');		
			$this->db->where($site.'_secao.tx_seo',$topicMobileTag);
			$this->db->where($site.'_conteudo.dt_conteudo',$date);
			$this->db->where($site.'_conteudo.in_ativo',1);
			$this->db->where($site.'_conteudo.in_tipo',0); // materia			
						
		}elseif ($topicForMobileType == 'topic'){
			$this->db->select($site.'_assunto.id AS assunto_id');
			$this->db->select($site.'_assunto.tx_assunto');
			$this->db->select($site.'_assunto.tx_seo AS assunto_seo');
		
			$this->db->join($site.'_conteudo_assunto',$site.'_conteudo_assunto.id_conteudo ='.$site.'_conteudo.id ','INNER');
			$this->db->join($site.'_assunto',$site.'_assunto.id ='.$site.'_conteudo_assunto.id_assunto ','INNER');


			//$w .= ' OR (';			
			if ($topicMobileTag == 'cultura-pop'){
				$w = '';
				$w = $site.'_conteudo.dt_conteudo = "'.$date.'" AND '.$site.'_conteudo.in_ativo = 1 AND '.$site.'_conteudo.in_tipo = 0 AND (';
				$w .= ' '.$site.'_assunto.tx_seo = "artes"';
				$w .= ' OR '.$site.'_assunto.tx_seo = "cinema"';
				$w .= ' OR '.$site.'_assunto.tx_seo = "design"';
				$w .= ' OR '.$site.'_assunto.tx_seo = "fotografia"';
				$w .= ' OR '.$site.'_assunto.tx_seo = "livros"';
				$w .= ' OR '.$site.'_assunto.tx_seo = "musica"';
				$w .= ' OR '.$site.'_assunto.tx_seo = "quadrinhos"';
				$w .= ')';
				$this->db->where($w);				
			}else{
				$this->db->where($site.'_assunto.tx_seo',$topicMobileTag);
				$this->db->where($site.'_conteudo.dt_conteudo',$date);
				$this->db->where($site.'_conteudo.in_ativo',1);
				$this->db->where($site.'_conteudo.in_tipo',0); // materia				
			}
		}
		

		$this->db->limit($limit,$page);
		
		$this->db->order_by($site.'_conteudo.dt_conteudo','DESC');
		$this->db->group_by($site.'_conteudo.id');
		
		$q = $this->db->get($site.'_conteudo');
		//echo $this->db->last_query();

 		if ($q->num_rows() > 0){
			return $q;
		}else{
			return FALSE;
		}		
	
	}

	
	
	/* getLastArticlesByTopicForMobile */
 	function getLastArticles($site,$date,$seotag,$mobileType,$page,$limit){
	
		$this->db->select($site.'_conteudo.id');
		$this->db->select($site.'_conteudo.tx_titulo');
		$this->db->select($site.'_conteudo.tx_texto');
		$this->db->select($site.'_conteudo.tx_foto');
		$this->db->select($site.'_conteudo.tx_olho');
		$this->db->select($site.'_conteudo.tx_chamada');
		$this->db->select($site.'_conteudo.tx_conteudo');
		if ($site == 'trip') $this->db->select($site.'_conteudo.tx_conteudo_mobile');
		$this->db->select($site.'_conteudo.dt_conteudo');
		$this->db->select($site.'_conteudo.im_capa');
		$this->db->select($site.'_conteudo.id_galeria');
		$this->db->select($site.'_conteudo.dt_conteudo');
		$this->db->select($site.'_conteudo.tx_url_completo');		
	
		if($mobileType == 'section'){
			$this->db->join($site.'_conteudo_secao',$site.'_conteudo_secao.id_conteudo ='.$site.'_conteudo.id ','LEFT');
			$this->db->join($site.'_secao',$site.'_secao.id ='.$site.'_conteudo_secao.id_secao ','LEFT');		
		}elseif ($mobileType == 'topic'){
			$this->db->join($site.'_conteudo_assunto',$site.'_conteudo_assunto.id_conteudo ='.$site.'_conteudo.id ','LEFT');
			$this->db->join($site.'_assunto',$site.'_assunto.id ='.$site.'_conteudo_assunto.id_assunto ','LEFT');		
		}	
		

		$w = '';
		$w = $site.'_conteudo.dt_conteudo <= "'.$date.'" AND '.$site.'_conteudo.in_ativo = 1 AND '.$site.'_conteudo.in_tipo = 0';
		$w .= ' AND (';
			
		if($mobileType == 'section'){
			$w .= $site.'_secao.tx_seo = "'.$seotag.'"';
			$w .= ' OR ';
		}elseif ($mobileType == 'topic'){
			if ($seotag == 'cultura-pop'){
				$w .= ' '.$site.'_assunto.tx_seo = "artes"';
				$w .= ' OR '.$site.'_assunto.tx_seo = "cinema"';
				$w .= ' OR '.$site.'_assunto.tx_seo = "design"';
				$w .= ' OR '.$site.'_assunto.tx_seo = "fotografia"';
				$w .= ' OR '.$site.'_assunto.tx_seo = "livros"';
				$w .= ' OR '.$site.'_assunto.tx_seo = "musica"';
				$w .= ' OR '.$site.'_assunto.tx_seo = "quadrinhos"';
				$w .= ' OR ';
			}else{
				$w .= $site.'_assunto.tx_seo = "'.$seotag.'"';
				$w .= ' OR ';
			}
		}

		$w = substr($w,0,-4);
		$w.= ")";

		$this->db->where($w);
		$this->db->limit($limit,$page);
		
		$this->db->order_by($site.'_conteudo.dt_conteudo','DESC');
		$this->db->group_by($site.'_conteudo.id');
		
		$q = $this->db->get($site.'_conteudo');
		//echo $this->db->last_query();

 		if ($q->num_rows() > 0){
			return $q;
		}else{
			return FALSE;
		}		
		
	}	
	
		

}


?>