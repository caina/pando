<?php
class Ad_price_model extends Dynamic_model {
	
	var $object;
	var $objects;
	var $object_listing;
	var $objects_listing;

	public function __construct() {
		parent::__construct();
	}

	function get($id=false){
		if($id){
			$this->object = (object) $this->get_db()->get_where('ad_price',array("id"=>$id))->row();
		}
		return $this;
	}

	function exists(){
		return !empty( (array)$this->object);
	}

	function listing(){
		$this->objects = $this->get_db()->get('ad_price')->result();
		return $this->objects;
	}

}

?>