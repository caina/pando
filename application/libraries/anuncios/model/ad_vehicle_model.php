<?php
class Ad_vehicle_model extends Dynamic_model {
	var $table_prefix = "";
	var $valid        = true;
	var $object;
	var $object_listing;

	public function __construct() {
		parent::__construct();
	}

	function get($vehicle_id) {
		$this->object = (object) $this->get_db()->get_where("ad_vehicle", array("id" => $vehicle_id))->row();
		return $this->object;
	}

	function get_listing($ad_id) {
		// $CI =& get_instance();
		// $CI->load->model('vehicle_model');

	}

	function get_by_id($vehicle_id) {
		$this->get_db()->select("
          ad_brand.title as brand,
          ad_model.title as model,
          ad_version.title as version,
          ad_year.title as year,
          ad_gearbox.title as gearbox,
          ad_fuel_type.title as fuel_type",
			"ad_door.title as door "
		);

		$this->get_db()->from("ad_vehicle");
		$this->get_db()->join("ad_brand", "ad_vehicle.ad_brand_id = ad_brand.id", "left");
		$this->get_db()->join("ad_model", "ad_vehicle.ad_model_id = ad_model.id", "left");
		$this->get_db()->join("ad_version", "ad_vehicle.ad_version_id = ad_version.id", "left");
		$this->get_db()->join("ad_year", "ad_vehicle.ad_year_id = ad_year.id", "left");
		$this->get_db()->join("ad_gearbox", "ad_vehicle.ad_gearbox_id = ad_gearbox.id", "left");
		$this->get_db()->join("ad_fuel_type", "ad_vehicle.ad_fuel_type_id = ad_fuel_type.id", "left");
		$this->get_db()->join("ad_door", "ad_vehicle.ad_door_id = ad_door.id", "left");
		$this->get_db()->where("ad_vehicle.id", $vehicle_id);

		return $this->get_db()->get()->row();
	}

	function post_to_values($post_data) {
		$this->object = (object) $post_data;
		// validar aquyi
		return $this->object;
	}

	function create_or_update($id = false) {

		if ($id) {
			$this->get_db()->update('ad_vehicle', $this->object, array("id" => $id));
		} else {

			$this->get_db()->insert("ad_vehicle", $this->object);
			$id = $this->get_db()->insert_id();
		}

		return $this->get($id);
	}

	function is_valid() {
		return true;
	}

}
?>
