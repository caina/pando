<?php

/**
 *controlador de sessao
 */

class Default_controller extends CI_Controller {
	var $data;

	public function __construct() {
		parent::__construct();
		$this->load_assets();
	}

	function add_inline_asset($asset,$type='js'){
		$this->data["asset_inline_".$type][] = $asset;
	}

	function add_asset($js, $type = "js") {
		if (empty($this->data["assets_".$type])) {
			$this->data["assets_".$type] = array();
		}

		if (!in_array($js, $this->data["assets_".$type])) {
			$this->data["assets_".$type][] = $js;
		}
	}

	function load_assets() {

		$default_js = array(
			"assets/cube/js/jquery.js",
			"assets/cube/js/bootstrap.js",
			"assets/js/validator.min.js",
			"assets/js/jasny-bootstrap.min.js",
			"assets/cube/js/jquery.nanoscroller.min.js",
			"assets/cube/js/moment.min.js",
			"assets/cube/js/jquery-jvectormap-1.2.2.min.js",
			"assets/cube/js/jquery-jvectormap-world-merc-en.js",
			"assets/cube/js/gdp-data.js",
			"assets/cube/js/flot/jquery.flot.min.js",
			"assets/cube/js/flot/jquery.flot.resize.min.js",
			"assets/cube/js/flot/jquery.flot.time.min.js",
			"assets/cube/js/flot/jquery.flot.threshold.js",
			"assets/cube/js/flot/jquery.flot.axislabels.js",
			"assets/cube/js/jquery.sparkline.min.js",
			"assets/cube/js/skycons.js",
			"assets/cube/js/scripts.js",
			"assets/cube/js/select2.min.js",
			"assets/cube/js/pace.min.js",
			"assets/ckeditor/ckeditor.js",
			"assets/cube/js/jquery.dataTables.js",
			"assets/cube/js/dataTables.fixedHeader.js",
			"assets/cube/js/dataTables.tableTools.js",
			"assets/js/jquery.form.min.js",
			"assets/cube/js/jquery.dataTables.bootstrap.js",
			"assets/js/filestyle.min.js",

			"assets/cube/js/modernizr.custom.js",
			"assets/cube/js/classie.js",
			"assets/cube/js/modalEffects.js",

			"assets/js/bootstrap-select.min.js"

		);

		$default_css = array(
			"assets/cube/css/bootstrap/bootstrap.min.css",
			"assets/css/jasny-bootstrap.min.css",
			"assets/css/font-awesome4.min.css",
			"assets/cube/css/libs/nanoscroller.css",
			"assets/cube/css/compiled/theme_styles.css",
			"assets/cube/css/libs/select2.css",
			"assets/cube/css/libs/daterangepicker.css",
			"assets/cube/css/libs/jquery-jvectormap-1.2.2.css",
			"assets/cube/css/compiled/theme_styles.css",
			"assets/cube/css/libs/weather-icons.css",

			"assets/cube/css/libs/nifty-component.css",
			"assets/css/bootstrap-select.min.css"

			
		);

		foreach ($default_css as $css) {
			$this->add_asset($css, "css");
		}

		foreach ($default_js as $js) {
			$this->add_asset($js);
		}

	}
}

class Authenticated_controller extends Default_controller {

	var $top_menu_html;
	var $permissions_white_list;

	public function __construct() {
		parent::__construct();

		$this->load->model(array("user_model","permission_model"));
		@session_start();

		$this->user_check();
		if ($this->user_model->user->developer == 1) {
			$this->administrator_functions();
		}

	}	

	private function user_check() {
		if (!$this->user_model->is_user_logged()) {
			redirect("login");
		} else {
			$this->data["logged_user"] = $this->user_model->user;
		} 

		$this->data["_adm_user"] = $this->user_model->user;
	}

	function administrator_functions() {
		$this->load->model("company_model");
		$this->data["session_user_companies_select"] = $this->company_model->list_developers_companies();
	}

	public function load_menu() {
		$this->load->library("sidebarmenu");
		$this->data['view_menu'] = $this->sidebarmenu->load_menu($this->data);

	}

	public function add_message($message, $type = "success") {
		$this->session->set_flashdata('message', array("message" => $message, "type" => $type));
	}

	/***
	funcoes que nao vao ser validadas pelas permissoes
	*/
	function set_white_list($arr){
		$this->permissions_white_list = $arr;
	}

	function get_top_widgets(){
		$modules = $this->user_model->user->modules;
		$this->load->helper('directory');
		$map = directory_map('./application/libraries/components', FALSE, TRUE);
		$html="";
		foreach ($modules as $module) {
			if(in_array($module->module_name.".php", $map)){
				$lib = $module->module_name;
				$this->load->library("components/{$lib}",array());
				$html .= $this->$lib->notifications();
			}
		}
		return $html;
	}

	public function load_view($view) {
		if(!$this->permission_model->user_is_allowed($this->permissions_white_list)){
			redirect(site_url("dashboard"));
		}
		$this->load_menu();

		//TODO colocar aqui o metodo que monta o menu default
		$this->data["top_menu"] = $this->top_menu_html;
		$this->data["top_widget"] = $this->get_top_widgets();
		
		$server_messages              = @$this->session->flashdata('message');
		$this->data["server_message"] = $server_messages;

		$this->data["view_content"] = $this->load->view($view, $this->data, true);

		$this->load->view('partial/head', $this->data);
		$this->load->view('partial/defaultcontent', $this->data);
		$this->load->view('partial/footer', $this->data);
	}

}

class Nomauthenticated_controller extends Default_controller {

	public function __construct() {
		parent::__construct();
	}
}
?>