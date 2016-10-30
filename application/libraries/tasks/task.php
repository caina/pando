<?php
class Task extends Authenticated_controller {


	public function __construct() {
		parent::__construct();
		$this->set_white_list(array("index","register","list_samples","detail"));

		$this->load->model(array("../libraries/amostras/model/amostra_model","../libraries/amostras/model/client_model"));
	}

	function list(){

	}

	function interaction(){

	}

	function data_act(){

	}

	

}
