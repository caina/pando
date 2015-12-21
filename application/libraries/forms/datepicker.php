<?php
/****
	CRIA FORMS USANDO OS DADOS DA TABELA NO BANCO
	E COMBINA COM OS DADOS DE CONFIGURAÇÃO DO MÓDULO


*/
class Datepicker {
	
	 var $ci;
	 var $options;

	 public function __construct($options = false ){
		$this->ci =&get_instance();
		$this->ci->load->helper("form_helper");
		if($options != false)
			$this->set_options($options);
	}

	public function show($options = array(),$data){
		// dump($data);die;
		if($data['value'] == "0000-00-00"){
			return "";
		}
		return date('d/m/Y',strtotime($data['value']));
	}
	public function save_action($field="",$options = array()){
		if(!empty($options['value'])){
			return date('Y-m-d',strtotime(str_replace("/","-",$options['value'])));
		}
	}

	public function list_act(){

	}

	public function generate($data){

		if($data['value'] != "0000-00-00" && !empty($data['value'])){

			$data['value'] = date('d/m/Y',strtotime($data['value']));
		}else if(empty($data['value'])){
			$data['value'] = date('d/m/Y');
		}
		$this->ci->add_asset("assets/cube/js/bootstrap-datepicker.js");
		$this->ci->add_asset("assets/cube/css/libs/datepicker.css",'css');

		$id = $data['id'];
		$js = "
		var options = {'format':'dd/mm/yyyy'}
		$('#{$id}').datepicker(options);
		";
		$this->ci->add_inline_asset($js);

		return form_input($data);
	}

	public function set_options($options){
		$this->options = (object) $options;

	}
 
}
?>