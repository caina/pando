<?php
class Ad_model_model extends Dynamic_model {

	var $table = "ad_model";
	var $object;
	var $objects;

	public function __construct() {
		parent::__construct();
	}

	function get($id = false) {
		if ($id) {
			$this->object = $this->get_db()->get_where($this->table, array("id" => $id))->row();
		}
		return $this;
	}

	function exists($id=false) {
		if($id){
			$this->get($id);
		}
		return !empty( (array)$this->object);
	}

	function listing($brand_id) {
		$this->objects = $this->get_db()->get_where($this->table, array("ad_brand_id" => $brand_id))->result();
		return $this->objects;
	}

	function populate($data, $brand_id) {
		foreach ($data as $element) {
			if (!$this->exists($element->Id)) {

				$object                = new stdClass();
				$object->id            = $element->Id;
				$object->title         = $element->Nome;
				$object->friendly_name = $element->NomeAmigavel;
				$object->ad_brand_id   = $brand_id;

				$this->db->insert($this->table, $object);
			}
		}
	}
}
?>