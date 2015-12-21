<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clients extends Authenticated_controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->model("../libraries/company_adm/clients_model");
		$this->load->library("form_creator");
    	$this->load->helper("html");
		$this->load->helper("form_helper");
	}

	public function list_clients()
	{	
		$this->add_asset("assets/css/bootstrap-colorpicker.min.css",'css');
		$this->add_asset("assets/js/bootstrap-colorpicker.min.js");
		$this->add_asset("application/libraries/company_adm/Assets/js/list_client.js");

		$this->data["adm_client_category"] = $this->clients_model->get_categories();
			
		$this->data["records"] = $this->clients_model->list_full();
		$this->data["avaliable_states"] = $this->clients_model->get_db()->get("admin_state")->result();
		$this->load_view("../libraries/company_adm/views/list_client");
	}

	function register_category(){
		$post_save = $this->input->post();
		$this->clients_model->add_category($post_save);
		redirect("lib_generic/method/company_adm/clients/list_clients");
	}

	function client_detail_edit($client_id){
		$client = $this->clients_model->get_client($client_id);
		$link = site_url("lib_generic/method/company_adm/clients/list_clients");
		$html = " <i class='fa fa-angle-right'></i> <a href='{$link}' class=''> Clientes </a> ";

		$link2= site_url("lib_generic/method/company_adm/clients/client_detail/".$client->id);
		$html .= " <i class='fa fa-angle-right'></i> <a href='{$link2}' class=''>{$client->company_name} </a>";
		$this->top_menu_html = $html;

		$this->data["client"] = $client;
		$this->load_view("../libraries/company_adm/views/client_detail");
	}

	function client_detail($client_id){
		if(!$client_id){
			redirect("lib_generic/method/company_adm/clients/list_clients");
		}
		$link = site_url("lib_generic/method/company_adm/clients/list_clients");
		$this->top_menu_html = "<i class='fa fa-angle-right'></i> <a href='{$link}' class=''> Clientes</a>";
		$this->data["client"] = $this->clients_model->get_client($client_id);
		$this->load_view("../libraries/company_adm/views/client_detail");
	}

  public function register(){
    $save = $this->input->post();
    if(!empty($_FILES)){
	    $image = send_image_to_client("logo");
    	$save["logo"] = $image;
    }

    $this->clients_model->save_data($save);
    redirect("lib_generic/method/company_adm/clients/list_clients");
  }

  function list_cities(){
  	$state_id = $this->input->post('state_id', TRUE);
  	$cities = $this->clients_model->get_db()->get_where("admin_city",array("admin_state_id"=>$state_id))->result();
  	$this->output->set_content_type('application/json')->set_output(json_encode($cities));
  }
}

/* End of file clients.php */
/* Location: ./application/controllers/clients.php */ ?>