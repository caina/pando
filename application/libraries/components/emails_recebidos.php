<?php

class Emails_recebidos{

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

	}

	public function notifications(){
		$this->ci->load->model('user_model');
		$this->ci->load->model('../libraries/email/email_model',"email");
		// dump($this->ci->user_model->user);
		$data["emails"] = $this->ci->email->get_nonreadded_emails();

		return $this->ci->load->view("../libraries/email/views/top_widget", $data, TRUE);
	} 
}

?>