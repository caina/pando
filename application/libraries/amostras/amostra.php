<?php
class Amostra extends Authenticated_controller {


	public function __construct() {
		parent::__construct();
		$this->set_white_list(array("index","register","list_samples","detail"));

		$this->load->model(array("../libraries/amostras/model/amostra_model","../libraries/amostras/model/client_model"));

	}

	function index($code=false){
		$this->add_asset("application/libraries/amostras/Assets/js/amostra.js");

		$this->data["sample_fields"] = $this->amostra_model->get($code)->object;
		// dump($this->data["sample_fields"]);
		$this->load_view("../libraries/amostras/views/home");
	}

	function register(){
		$post_client = $this->input->post('client', TRUE);
		$post_sample = $this->input->post('samples', TRUE);
		if(empty($post_sample["client_id"])){
			if($this->client_model->create($post_client)){
				$post_sample["client_id"] = $this->client_model->object->id;
			}

		}
		$this->amostra_model->create_or_edit($post_sample);

		set_message("Operação realizada com sucesso");
		redirect(site_url("act/amostras/amostra/index"));
	}

	function list_samples(){
		$page = $this->input->post('page', TRUE);
		$code = $this->input->post('code', TRUE);
		$client_id = $this->input->post('client_id', TRUE);
		$user_id = $this->input->post('user_id', TRUE);
		$data["samples_array"] = $this->amostra_model->find_by_params($page,$code,$client_id,$user_id)->objects;
		$data["pages"] = $this->amostra_model->itens_found / $this->amostra_model->itens_per_page;
		$html = $this->load->view("../libraries/amostras/views/list", $data, TRUE);

		$this->output->set_content_type('text/plain', 'UTF-8')->set_output($html);
	}

	function detail($code){
		$this->data["sample"] = $this->amostra_model->get_display($code)->object;
		$this->load_view("../libraries/amostras/views/detail");
	}

	function run_basic(){
		$this->amostra_model->run_basic();
	}

}

?>
