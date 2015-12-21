<?php
/****
	Create the CKEditor 
*/
class CKEditor{
	
	 var $ci;

	 public function __construct(){
		$this->ci =&get_instance();
		$this->ci->load->helper("form_helper");
	}

	public function show($options = array(),$data){
		$this->ci->load->helper('text');
		return word_limiter(strip_tags($data['value']),3);
	}

	public function generate($data,$value = ""){
		// variavel de config para achar a base do site, usado no kcfinder
		if($this->ci->config->item('assets.js_opener')=="$(document).ready(function(){\n"){
			Assets::add_js("});var site_url = '".base_url()."'; $(document).ready(function(){","inline",true);
		}else{
			Assets::add_js("var site_url = '".base_url()."'; ","inline",true);
		}

		$value = empty($value)?(isset($data['value'])?$data['value']:""):$value;

		Assets::add_js("../plugins/ckeditor/ckeditor.js");
		$data["class"] = "ckeditor";
		return form_textarea($data,$value);
	}

}

?>