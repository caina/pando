<?php
class Ad_model extends Dynamic_model {
	var $table_prefix = "";
	var $valid        = true;
	var $object;
	var $objects;
	var $object_listing;
	var $objects_listing;
	var $itens_per_page = 6;

	public function __construct() {
		parent::__construct();
	}

	function check_data($id = false) {
		if ($id) {
			$this->get($id);
		}
		if (empty($this->object)) {
			$this->get($id);
		}
		return !empty($this->object);
	}

	function is_valid_($id = false) {
		if ($this->is_mine()) {
			if ($this->object->is_aproved == 1) {
				return true;
			}
		}
		return false;
	}

	function is_mine($id = false) {
		if (!$this->check_data($id)) {
			return false;
		}

		if (@$this->object->user_id == unserialize(get_logged_user())->user_id) {
			return true;
		}

		return false;
	}

	function get($id) {
		$this->object = (object) $this->get_db()->get_where("ad", array("id" => $id, "is_deleted" => 0))->row();
		return $this;
	}

	function get_listing($id = false) {
		$CI = &get_instance();
		$CI->load->model(array(
				"../libraries/anuncios/model/ad_vehicle_model",
				"../libraries/anuncios/model/ad_image_model",
			));

		if (!$this->check_data($id)) {
			return false;
		}

		$this->object_listing                 = $this->object;
		$this->object_listing->formated_price = number_format($this->object_listing->price, 2, ',', '.');
		$this->object_listing->formated_date  = date("d/m/Y", strtotime($this->object->created_at));
		$this->object_listing->publish_time   = date("H:m", strtotime($this->object->created_at));

		$this->object_listing->vehicle = $CI->ad_vehicle_model->get_by_id($this->object->vehicle_id);
		$this->object_listing->images  = $CI->ad_image_model->get_by_ad($this->object->id);
		$this->object_listing->cover   = $CI->ad_image_model->get_ad_cover($this->object->id);

		return $this->object_listing;
	}

	function manage_list($page = 1) {
		$CI = &get_instance();
		$CI->load->model("../libraries/anuncios/model/ad_image_model");
		$CI->load->model("../libraries/anuncios/model/ad_version_model");

		$user_id       = unserialize(get_logged_user())->user_id;
		$this->objects = $this->get_db()->

		select("ad.*,ad_vehicle.ad_version_id as version_id")      ->
		from("ad")                                                 ->
		join("ad_vehicle", "ad_vehicle.id = ad.vehicle_id", "left")->
		where("ad.is_deleted", 0)                                  ->
		where("ad.user_id", $user_id)                              ->
		order_by('ad.created_at', 'DESC')                          ->
		limit($this->itens_per_page, $page*$this->itens_per_page)->get()->result();

		$this->objects_listing = $this->objects;
		foreach ($this->objects_listing as &$obj) {

			switch ($obj->is_aproved) {
				case 1:
					$obj->is_aproved    = true;
					$obj->aproved_text  = "Aprovado";
					$obj->aproved_badge = "success";
					$obj->aproved_bg    = "green-bg";
					break;
				case 0:
					$obj->is_aproved    = false;
					$obj->aproved_text  = "Pendente";
					$obj->aproved_badge = "warning";
					$obj->aproved_bg    = "gray-bg";

					break;
				case 2:
					$obj->is_aproved    = false;
					$obj->aproved_text  = "Cancelado";
					$obj->aproved_badge = "danger";
					$obj->aproved_bg    = "red-bg";

					break;
			}
			$obj->formated_price = number_format($obj->price, 2, ',', '.');
			$obj->formated_date  = date("d/m/Y", strtotime($obj->created_at));
			$obj->image_cover    = $CI->ad_image_model->get_ad_cover($obj->id);
			$obj->is_repasse     = $CI->ad_version_model->above_fipe($obj->version_id, $obj->price);
		}

		return $this->objects_listing;
	}

	function number_pager_display() {
		$user_id = unserialize(get_logged_user())->user_id;

		$result_set = $this->get_db()->from("ad")->where("is_deleted", 0)->where("user_id", $user_id)->order_by('created_at', 'DESC')->get();
		// dump($result_set->num_rows/10);
		return ceil($result_set->num_rows/$this->itens_per_page);
	}

	function format_verification($ad_pbject) {

	}

	function remove($ad_id = false) {
		if ($ad_id) {
			$this->get($ad_id);
		}
		$this->get_db()->update('ad', array("is_deleted" => 1), array("id" => $this->object->id));
	}

	function update($ad_id) {
		$this->get_db()->update('ad', $this->object, array("id" => $ad_id));
		return $this->get($ad_id);
	}

	function post_to_values($ad) {
		$this->object = $ad;

		//validacao vai aquyi

		$this->object["price"] = reaisToFloat($this->object["price"]);
		$this->object          = (object) $this->object;
		return $this->object;
	}

	function create_new($ad_object) {
		$this->get_db()->insert('ad', $ad_object);
		return $this->get($this->get_db()->insert_id());
	}

	function set_vehicle_id($vehicle) {
		$this->get_db()->update('ad', array("vehicle_id" => $vehicle), array("id" => $this->object->id));
		return $this->get($this->object->id);
	}

	function is_valid($ad_object) {
		dump($ad_object);
		die;
		if (empty($ad_object["category"])) {

		}
		if (empty($ad_object["price"])) {

		}
		if (empty($ad_object["description"])) {

		}

	}

	function getShippingAddress(){
		
	}

	function getSender(){

	}

}
?>
