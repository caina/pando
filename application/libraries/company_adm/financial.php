<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Financial extends Authenticated_controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->model("../libraries/company_adm/clients_model");
		$this->load->library("form_creator");
    	$this->load->helper("html");
		$this->load->helper("form_helper");
		$this->top_menu_html = $this->load->view("../libraries/company_adm/views/financial/menu_financial",'',true);
		$this->add_asset("application/libraries/company_adm/Assets/js/basic.js");

	}

	function list_all(){
		$this->data["menu_lateral"] = $this->load->view("../libraries/company_adm/views/financial/lateral_menu",'',true);
		$this->data["main_content"] =  $this->load->view("../libraries/company_adm/views/financial/graphycs",'',true);

		$this->load_view("../libraries/company_adm/views/financial/default");

	}

}

/* End of file clients.php */
/* Location: ./application/controllers/clients.php */ ?>