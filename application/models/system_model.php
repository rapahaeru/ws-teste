<?php

class System_model extends CI_Model {

	function verifyToken($token){
 		$this->db->where('ws_token.ws_token',$token);
		$q = $this->db->get('ws_token');
		if ($q->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
		
	

}


?>