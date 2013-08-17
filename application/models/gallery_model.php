<?php

class Gallery_model extends CI_Model {

			
	function getArticleGalleryById($idGallery,$site){
		$this->db->select($site.'_galeria.id');
		$this->db->select($site.'_galeria.tx_titulo');
		$this->db->select($site.'_galeria.tx_olho');
		$this->db->select($site.'_galeria.tx_fotos');
		
		$this->db->where($site.'_galeria.id',$idGallery);
		
		$q = $this->db->get($site.'_galeria');
		//echo $this->db->last_query();

 		if ($q->num_rows() > 0){
			return $q->result_array();
		}else{
			return FALSE;
		}
	}

	function getArticleGalleryImageById($images, $site){
		$this->db->select($site.'_imagem.id');
		$this->db->select($site.'_imagem.tx_legenda');
		$this->db->select($site.'_imagem.tx_credito');
		$this->db->select($site.'_imagem.tx_imagem');
		
		$this->db->where($site.'_imagem.id in ('.$images.')');
		

		$q = $this->db->get($site.'_imagem');
		//echo $this->db->last_query();

 		if ($q->num_rows() > 0){
			return $q->result_array();
		}else{
			return FALSE;
		}		
		
	}
	
}


?>