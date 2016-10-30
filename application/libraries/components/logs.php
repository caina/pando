<?php

class Logs{

	var $ci;
	var $options;
	var $user_model;
	var $config;
	var $data;

	public function __construct($config) {
		$this->ci     = &get_instance();
		$this->config = $config;
	}

	public function show($options = array(), $data) {

		return "";
	}

	public function save_action($field = "") {
		return "";
	}

	public function ajax_save() {
	}

	public function list_act() {

	}

	public function generate() {
		return "";
	}

	public function set_options($options) {

	}

	private function get_options() {

	}

	public function dashboard_display() {
		
		$this->ci->load->model("screen_model");
		$this->ci->load->model("user_model");

		$this->ci->screen_model->db->select("users.name, company.name as company_name, users_log.login_date");
		$this->ci->screen_model->db->from("users_log");
		$this->ci->screen_model->db->join("users", "users.id = users_log.users_id");
		$this->ci->screen_model->db->join("company", "company.id = users.company_id");
		if ($this->ci->user_model->user->developer == 0) {
			$this->ci->screen_model->db->where("users.company_id", $this->ci->user_model->user->company_id);
		}

		$this->ci->screen_model->db->order_by("users_log.login_date", "desc");
		$this->ci->screen_model->db->limit(10);
		$this->data["logs"] = $this->ci->screen_model->db->get()->result();

		return $this->ci->load->view('libraries_view/logs', $this->data, true);

	}
}

?>