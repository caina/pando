<?php
class Client_model extends Dynamic_model {
	var $table_prefix = "";

	var $object;
	var $objects;
	var $ci;
	var $exists = true;

	public function __construct() {
		parent::__construct();
		$this->ci =&get_instance();
		$this->load->model('user_model');
	}

	function get($id=false){
		if($id){
			$this->object = $this->get_db()->get_where('client', array("id"=>$id))->row();
			$this->exists = true;
		}
		$this->exists = false;
		return $this;
	}

	function create($post_create){
		if(!empty($post_create["name"])){
			$this->get_db()->insert('client', $post_create);
			return $this->get($this->get_db()->insert_id());
		}
		return false;
	}
	


}
?>
