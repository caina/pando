<?php
class Ad_image_model extends Dynamic_model {
	var $table_prefix = "";
	var $object;
	var $objects;

	public function __construct() {
		parent::__construct();
	}

	public function save_image($image_name) {
		return $this->get_db()->insert('ad_image', array("image_name" => $image_name,"user_id"=>unserialize(get_logged_user())->user_id));
	}

	function set_ad($image_name, $ad_id) {

	}

	function get($image_name){
		$this->object = $this->db->get_where('ad_image', array("image_name"=>$image_name))->row();
		return $this->object;
	}

	function is_mine($id=false){
		if($id){
			$this->get($id);
		}
		return $this->object->user_id == unserialize(get_logged_user())->user_id;
	}

	function delete($id=false){
		if($id){
			$this->get($id);
		}
		
		$this->db->delete('ad_image', array("image_name"=>$this->object->image_name,"user_id"=>unserialize(get_logged_user())->user_id));
	}

	function remove_cover($image_name){
		$image = $this->db->get_where('ad_image', array("image_name"=>$image_name,"user_id"=>unserialize(get_logged_user())->user_id))->row();
		if(!empty($image->ad_id)){
			$this->db->update('ad_image', array("is_cover"=>0), array("ad_id"=>$image->ad_id));
		}
	}

	function set_cover($image_name){
		$this->db->update('ad_image', array("is_cover"=>1), array("image_name"=>$image_name,"user_id"=>unserialize(get_logged_user())->user_id));
	}

	function set_ad_images($images, $ad) {
		foreach ($images as $image) {
			$this->get_db()->update('ad_image', array("ad_id" => $ad), array("image_name" => $image,"user_id"=>unserialize(get_logged_user())->user_id));
		}
	}

	/**
	 * atualiza os ad_id das imagens caso nao tenha
	 * checa antes pra saber se estas imagens ja nao estavam setadas
	 **/
	function update_ad($images_add, $ad_id) {

	}

	function get_by_ad($ad_id) {
		$this->object = $this->get_db()->get_where("ad_image", array("ad_id" => $ad_id))->result();
		return $this->object;
	}

	function get_to_json($ad_id) {
		$this->get_by_ad($ad_id);
		foreach ($this->object as &$obj) {
			$obj->image_path = site_url("images/".$obj->image_name."?width=100&height=100&force=true"); //unserialize(get_logged_user())->upload_path."/".$obj->image_name;
			$obj->image      = $obj->image_name;
		}
		return $this->object;
	}

	function get_ad_cover($ad_id) {
		$this->object =  $this->get_db()->get_where('ad_image', array("ad_id" => $ad_id,"is_cover"=>1))->row();
		
		if(empty($this->object)){
			$this->object = $this->get_db()->get_where('ad_image', array("ad_id" => $ad_id))->row();
		}

		return $this->object;
	}



}
?>
