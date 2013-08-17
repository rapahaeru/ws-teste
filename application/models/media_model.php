<?php
class Media_model extends CI_Model {

	// 	getLastMediaByDate
	function getLastMedias($site,$date,$mediaTag,$page,$limit){
		
		if ($mediaTag == 'video'){
			$this->db->select($site.'_video.id');
			$this->db->select($site.'_video.dt_publicacao');
			$this->db->select($site.'_video.tx_titulo');
			$this->db->select($site.'_video.tx_olho');
			$this->db->select($site.'_video.tx_tag');
			$this->db->select($site.'_video.tx_linktube');
			$this->db->select($site.'_video.tx_duracao');
			$this->db->select($site.'_video.tx_edicaor');
			$this->db->select($site.'_video.tx_obs');
			$this->db->select($site.'_video.im_capa');
			$this->db->select($site.'_video.id');
			
			$this->db->where($site.'_'.$mediaTag.'.tx_linktube != ""');
			
		}else if($mediaTag == 'audio'){
			$this->db->select($site.'_audio.id');
			$this->db->select($site.'_audio.dt_publicacao');
			$this->db->select($site.'_audio.tx_titulo');
			$this->db->select($site.'_audio.tx_olho');
			$this->db->select($site.'_audio.tx_tag');
			$this->db->select($site.'_audio.tx_link01');
			$this->db->select($site.'_audio.tx_link02');
			$this->db->select($site.'_audio.tx_duracao');
			$this->db->select($site.'_audio.tx_edicaor');
			$this->db->select($site.'_audio.tx_obs');
			$this->db->select($site.'_audio.im_capa');
						
		}
		 
		
		$this->db->where($site.'_'.$mediaTag.'.dt_publicacao <=',$date);
		$this->db->where($site.'_'.$mediaTag.'.in_ativo',1);
		$this->db->limit($limit,$page);
		
		$this->db->order_by($site.'_'.$mediaTag.'.dt_publicacao','DESC');
		$this->db->group_by($site.'_'.$mediaTag.'.id');
		
		$q = $this->db->get($site.'_'.$mediaTag);
		//echo $this->db->last_query();
		
		if ($q->num_rows() > 0){
			return $q;
		}else{
			return FALSE;
		}
		
	
	}
}
?>
