<?php
class clientes extends Authenticated_controller {


	public function __construct() {
		parent::__construct();
		$this->set_white_list(array("cadastro","cadastro_action"));
		$this->load->model(array("../libraries/financeiro/models/financeiro_model"));
	}

	function gerenciar(){
		$this->add_asset("application/libraries/financeiro/Assets/js/financeiro_clientes.js");

		$this->data["clientes_totais"] = $this->financeiro_model->get_companies_total();
		// dump($this->data["clients"]);
		$this->load_view("../libraries/financeiro/views/clientes");
	}

	function cadastro($id=false){
		if($id){
			// o is mine vai pesquisar o cliente e disponibilziar no objeto ;)
			if(!$this->financeiro_model->is_mine($id)){
				set_message("Este cliente não lhe pertence",2);
				redirect(site_url("act/financeiro/clientes/gerenciar"));
			}
		}else{
			$this->financeiro_model->create_dummy();
		}
		$this->data["company"] = $this->financeiro_model->object;
		$this->load_view("../libraries/financeiro/views/cadastrar_cliente");
	}

	function cadastro_action($id){

		if($this->financeiro_model->is_mine($id)){
			$this->financeiro_model->update_data($this->input->post("client"));
		}
		set_message("Os dados do cliente : ".$this->financeiro_model->object->name." forma alterados");
		redirect(site_url("act/financeiro/clientes/gerenciar"));
	}

	function list_clients(){
		$data;
		$page 	= $this->input->post('page', TRUE);
		$serch 	= $this->input->post('search', TRUE);
		$type 	= $this->input->post('type', TRUE);

		$this->financeiro_model->get_by_query($page,$serch,$type);
		$data["pages"] = $this->financeiro_model->founded_itens / $this->financeiro_model->itens_per_page;
		$data["clientes"] = $this->financeiro_model->objects;
		$html = $this->load->view("../libraries/financeiro/views/cadastro_listagem",$data,TRUE);

		$this->output->set_content_type('text/plain', 'UTF-8')->set_output($html);
	}

	function inativar($id){
		if(!$this->financeiro_model->is_mine($id)){
			set_message("Este cliente não lhe pertence",2);
		}else{
			$data["status"]=3;
			$this->financeiro_model->update_data($data);
		}
		redirect(site_url("act/financeiro/clientes/gerenciar"));
	}

}