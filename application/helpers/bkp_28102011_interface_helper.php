<?php
class StaticResource{
	function src($src = FALSE){
		if($src){
			$RTR =& load_class('Router');
			$class  = $RTR->fetch_class();
			$CI = new $class();
			return $CI->config->config['domain'].$CI->config->config['base_path'].$CI->config->config['application']."/views/".$src;
		}
	}
}

function CurrentController(){
	$arrUrl = explode('/',str_replace(site_url(),'',current_url()));
	//echo sizeof($arrUrl);
	if (sizeof($arrUrl) == 1){
		return $arrUrl[0];
	}elseif($arrUrl[0]==""){
		return "home";
	}else{
		return $arrUrl[1];
	}
}

function CurrentFunction(){
	$arrUrl = explode('/',str_replace(site_url(),'',current_url()));
	if(!isset($arrUrl[1])){
		return "";
	}else{
		return $arrUrl[1];
	}
}

	function htmlDecode($content){
		$chaves 			= array("&ldquo;", "&rdquo;", "&raquo;", "&laquo;","&i","&ea ","&lt;","&gt;","&ndash;");
		
		$contentReplaced 	= html_entity_decode(trim($content));
		$contentUTF			= utf8_encode($contentReplaced);
		//$contentTags		= strip_tags($contentUTF);
		$contentFinal		= str_replace($chaves,"", $contentUTF);

		return $contentFinal;

	}

?>