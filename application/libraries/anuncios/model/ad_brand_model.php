<?php
class Ad_brand_model extends Dynamic_model {

	var $table = "ad_brand";
	var $object;
	var $objects;

	public function __construct() {
		parent::__construct();
	}

	function get($id=false){
		if($id){
			$this->object = $this->get_db()->get_where($this->table, array("id"=>$id))->row();
		}
		return $this->object;
	}

	function listing(){
		$this->objects = $this->get_db()->get($this->table)->result();
		return $this->objects;
	}
}
?>