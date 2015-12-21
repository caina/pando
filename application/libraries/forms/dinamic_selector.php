<?php
/****
*/
class Dinamic_selector {
	
	 var $ci;
	 var $options;

	 public function __construct($options = false ){
		$this->ci =&get_instance();
		$this->ci->load->helper("form_helper");
		if($options != false)
			$this->set_options($options);
	}

	public function show($options = array(),$data){
		$v = $data['value'];
		return $options->options->$v;
	}

	public function list_act(){
	}

	public function generate($data){
		$opt = array("name"=>$data['name']);
		return form_dropdown($opt, ((array)$this->options->options),$data['value'],$this->options->label);
	}

	public function set_options($options){
		$this->options = (object) $options;

	}

}

?>