<?php
/****
	CRIA FORMS USANDO OS DADOS DA TABELA NO BANCO
	E COMBINA COM OS DADOS DE CONFIGURAÇÃO DO MÓDULO


*/
class Onetomany {
	
	 var $ci;
	 var $options;
	 var $max_list_size = 50;
	 var $user_model;
	 var $config;
	 public function __construct($configuration){
		$this->ci =&get_instance();
		$this->config = $configuration;
		$this->ci->load->helper("form_helper");
		dump($configuration);
	}

	public function show($options = array(),$data){
		
		return "";
	}

	public function save_action($field="",$options = array()){
		return "";
			
	}

	public function list_act(){

	}

	public function generate(){
		dump($this->config);
		return "";
	}

	public function set_options($options){
		$this->options = (object) $options;

	}

	private function get_options(){
		
	}

	


}

?>