<?php

class Blogs_model extends CI_Model {

	/* getBlogs */
	function getPostBlogs($site,$page,$limit){
		$this->db->select($site.'_conteudo.id');
		$this->db->select($site.'_conteudo.tx_titulo');
		$this->db->select($site.'_conteudo.tx_texto');
		$this->db->select($site.'_conteudo.tx_conteudo');
		$this->db->select($site.'_conteudo.dt_conteudo');
		$this->db->select($site.'_conteudo.id_galeria');
		$this->db->select($site.'_conteudo.im_capa');
		$this->db->select($site.'_conteudo.tx_url_completo');
		$this->db->select($site.'_blog.id AS blog_id');
		$this->db->select($site.'_blog.tx_nome AS blog_name');
		$this->db->select($site.'_blog.tx_url AS blog_seo');
		$this->db->select($site.'_blog.tx_descricao AS blog_descricao');
	
		$this->db->join($site.'_conteudo_blog',$site.'_conteudo_blog.id_conteudo ='.$site.'_conteudo.id ','INNER');
		$this->db->join($site.'_blog',$site.'_blog.id ='.$site.'_conteudo_blog.id_blog ','INNER');

		$this->db->where($site.'_conteudo.in_ativo',1);
		$this->db->where($site.'_conteudo.in_tipo',1); // Blogs
		
		$this->db->limit($limit,$page);
		$this->db->order_by($site.'_conteudo.dt_conteudo','DESC');
		//$this->db->group_by($site.'_conteudo.id');		
		

		$q = $this->db->get($site.'_conteudo');
		//echo $this->db->last_query();
		if ($q->num_rows() > 0){
			return $q;
			//return $q->result_array();
		}else{
			return FALSE;
		}
	}
		
	/* getBlogs */
	function getPostByIdBlog($site,$blogId,$page,$limit){
		
		$this->db->select($site.'_conteudo.id');
		$this->db->select($site.'_conteudo.tx_titulo');
		$this->db->select($site.'_conteudo.tx_texto');
		$this->db->select($site.'_conteudo.tx_conteudo');
		$this->db->select($site.'_conteudo.dt_conteudo');
		$this->db->select($site.'_conteudo.id_galeria');
		$this->db->select($site.'_conteudo.im_capa');
		$this->db->select($site.'_conteudo.tx_url_completo');		
		$this->db->select($site.'_blog.id AS blog_id');
		$this->db->select($site.'_blog.tx_nome AS blog_name');
		$this->db->select($site.'_blog.tx_url AS blog_seo');
		$this->db->select($site.'_blog.tx_descricao AS blog_descricao');
	
		$this->db->join($site.'_conteudo_blog',$site.'_conteudo_blog.id_conteudo ='.$site.'_conteudo.id ','INNER');
		$this->db->join($site.'_blog',$site.'_blog.id ='.$site.'_conteudo_blog.id_blog ','INNER');

		$this->db->where($site.'_blog.id',$blogId);
		$this->db->where($site.'_conteudo.in_ativo',1);
		$this->db->where($site.'_conteudo.in_tipo',1); // Blogs
		
		$this->db->limit($limit,$page);
		$this->db->order_by($site.'_conteudo.dt_conteudo','DESC');
		//$this->db->group_by($site.'_conteudo.id');		
		

		$q = $this->db->get($site.'_conteudo');
		//echo $this->db->last_query();
		if ($q->num_rows() > 0){
			return $q;
			//return $q->result_array();
		}else{
			return FALSE;
		}
	}
		
	/* getBlogsList */
	function getBlogsListBySite($site){
		
		$this->db->select($site.'_blog.id AS blog_id');
		$this->db->select($site.'_blog.tx_nome AS blog_name');
		$this->db->select($site.'_blog.tx_url AS blog_seo');
		$this->db->select($site.'_blog.tx_descricao AS blog_descricao');
	
		$this->db->where($site.'_blog.in_ativo',1);
		
		$this->db->order_by($site.'_blog.tx_nome','ASC');
		//$this->db->group_by($site.'_conteudo.id');		
		

		$q = $this->db->get($site.'_blog');
		//echo $this->db->last_query();
		if ($q->num_rows() > 0){
			return $q;
			//return $q->result_array();
		}else{
			return FALSE;
		}
	}

	
		

}


?>