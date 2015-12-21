<?php
class Ad_year_model extends Dynamic_model {

	var $table = "ad_year";
	var $object;
	var $objects;

	public function __construct() {
		parent::__construct();
	}

	function get($id = false) {
		if ($id) {
			$this->object = $this->get_db()->get_where($this->table, array("id" => $id))->row();
		}
		return $this->object;
	}

	function exists($year, $model_id) {
		$this->object = $this->get_db()->get_where($this->table, array("title" => $year, "ad_model_id" => $model_id))->row();
		return !empty($this->object);
	}

	function listing($model_id) {
		$this->objects = $this->get_db()->get_where($this->table, array("ad_model_id" => $model_id))->result();
		return $this->objects;
	}

	function populate($data, $model_id) {
		foreach ($data as $element) {
			if (!$this->exists($element, $model_id)) {
				$object              = new stdClass();
				$object->title       = $element;
				$object->ad_model_id = $model_id;
				$this->get_db()->insert($this->table, $object);
			}
		}
	}
}
?>