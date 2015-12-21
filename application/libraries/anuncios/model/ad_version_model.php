<?php
class Ad_version_model extends Dynamic_model {

	var $table = "ad_version";
	var $object;
	var $objects;

	public function __construct() {
		parent::__construct();
	}

	function get($id = false) {
		if ($id) {
			$this->object                  = $this->get_db()->get_where($this->table, array("id" => $id))->row();
			@$this->object->formated_price = !empty($this->object->price)?number_format($this->object->price, 2, ',', '.'):0;
		}
		return $this->object;
	}

	function exists($id) {
		$this->object = $this->get($id);
		return !empty($this->object);
	}

	function listing($year_id) {
		$this->objects = $this->get_db()->get_where($this->table, array("ad_year_id" => $year_id))->result();
		return $this->objects;
	}

	function populate($data, $year_id) {
		foreach ($data as $element) {
			if (!$this->exists($element->Id)) {
				$object                = new stdClass();
				$object->Id            = $element->Id;
				$object->title         = $element->Nome;
				$object->friendly_name = $element->NomeAmigavel;
				$object->ad_year_id    = $year_id;
				$this->get_db()->insert($this->table, $object);
			}
		}
	}

	function update() {
		$this->db->update($this->table, $this->object, array("id" => $this->object->id));
	}

	function above_fipe($version_id, $price) {
		$this->get($version_id);
		if (!empty((array) $this->object)) {
			$price = reaisToFloat($price);
			return $price > $this->object->price;
		}
		return false;
	}
}
?>