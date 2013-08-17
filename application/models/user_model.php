<?php

class User_model extends CI_Model {

	function getUserById($userID){
		$this->db->where('usd_userdata.usd_id',$userID);
		$q = $this->db->get('usd_userdata');
		if ($q->num_rows() > 0){
			return $q->row();
		}else{
			return FALSE;
		}
	}
		
	function getUsers(){
		$q = $this->db->get('usd_userdata');
		if ($q->num_rows() > 0){
			return $q->result_array();		
		}else{
			return FALSE;
		}	
		
	}
	
	function insertUser($post){
		//var_dump($post);

		$exists_mail 	= $this->User_model->verifyRepeatedUserByMail($post['mail']);
		$exists_login 	= $this->User_model->verifyRepeatedUserByLogin($post['login']);
		
		if ($exists_mail || $exists_login) {
			return 'error';
		}else{
			$data = array (
								'usd_name'	 	=> $post['nome'] ,
								'usd_login' 	=> $post['login'] ,
								'usd_pass' 		=> md5($GLOBALS['hash'].$post['pass']) ,
								'usd_mail' 		=> $post['mail'] ,
								'usd_status' 	=> $post['status'] 
							); 

			$this->db->insert('usd_userdata',$data);
		}
	}
	
	function verifyRepeatedUserByMail($post){
		$this->db->where('usd_userdata.usd_mail',$post);	
		$q = $this->db->get('usd_userdata');
		if ($q->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}		

	}
	
	function verifyRepeatedUserByLogin($post){
		$this->db->where('usd_userdata.usd_login',$post);	
		$q = $this->db->get('usd_userdata');
		if ($q->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}		
	}	

	function update($post){
	
		$exists_mail 	= $this->User_model->verifyRepeatedUserByMailOnUpdate($post['mail'],$post['user_id']);
		$exists_login 	= $this->User_model->verifyRepeatedUserByLoginOnUpdate($post['login'],$post['user_id']);	

		
		if ($exists_mail || $exists_login) {
			return 'error';
		}else{		
			if($post['pass'] != ''){
				$data = array (
									'usd_name'	 	=> $post['nome'] ,
									'usd_login' 	=> $post['login'] ,
									'usd_pass' 		=> md5($GLOBALS['hash'].$post['pass']) ,
									'usd_mail' 		=> $post['mail'] ,
									'usd_status' 	=> $post['status'] 
								); 			
			}else{
				$data = array (
									'usd_name'	 	=> $post['nome'] ,
									'usd_login' 	=> $post['login'] ,
									'usd_mail' 		=> $post['mail'] ,
									'usd_status' 	=> $post['status'] 
								); 			
			}
		}

		//var_dump($data);
		$this->db->where('usd_userdata.usd_id', $post['user_id']);
		$this->db->update('usd_userdata',$data);
	
	}
	
	function verifyRepeatedUserByMailOnUpdate($post, $id){
		$this->db->where('usd_userdata.usd_mail',$post);
		$this->db->where('usd_userdata.usd_id !=',$id);
		$q = $this->db->get('usd_userdata');
		if ($q->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}			
	
	}
	
	function verifyRepeatedUserByLoginOnUpdate($post, $id){
		$this->db->where('usd_userdata.usd_login',$post);	
		$this->db->where('usd_userdata.usd_id !=',$id);
		$q = $this->db->get('usd_userdata');
		if ($q->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}		
	}		
	
	function deleteUserById($user_id){
		
		$this->db->where('usd_id', $user_id);
		$this->db->delete('usd_userdata');
		return true;
	}
}


?>