<?php
class task_model extends Dynamic_model {
	var $table_prefix = "";

	var $object;
	var $post_data;
	var $objects;
	var $ci;
	var $exists;

	var $itens_found;

	public function __construct() {
		parent::__construct();
		$this->ci =&get_instance();
		$this->ci->load->model('user_model');
	}

	
	function get(){

	}

	function list(){

	}

	function add_iteraction(){

	}

}
?>