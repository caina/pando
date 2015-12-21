<?php
class Ad_category_model extends Dynamic_model {
	var $table_prefix = "";

	public function __construct() {
		parent::__construct();
	}

	function get_categories() {
		$this->check_table("anuncios", "ad_catego");
		return $this->get_db()->get_where("ad_category", array("is_active" => 1))->result();
	}

	// busca as dependencias desta categoria
	function get_dependences($category) {
		return $this->get_db()->get_where('ad_category_dependences', array("ad_category_id" => $category))->result();
	}

	// usado principalmente para pegar os valores dos combo box das categorias
	// eh bem generico
	function get_values($element_obj) {
		return $this->get_db()->get($element_obj->element)->result();
	}

	function get_category_dependences_values($table, $field, $value, $vehicle = false) {

		$category = $this->get_db()->get_where($table, array($field => $value))->result();
		foreach ($category as &$cat) {
			$cat->selected  = false;
			$element_select = $table."_id";
			if ($cat->id == @$vehicle->$element_select) {
				$cat->selected = true;
			}
		}
		return $category;
	}
}
?>