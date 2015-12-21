<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends Authenticated_controller {
	
	// TODO esse id tem que ser validado ;)
	function index($id=false){
		$this->session->keep_flashdata('item');
		$this->load->model(array("company_model","user_model","permission_model"));

		$this->data["database_company"] = $this->company_model->listAll();
		$this->data["users"] 			= $this->user_model->get_by_company();
		$this->data['user_pages'] 		= $this->user_model->count_by_company();
		$this->data["permissions"]		= $this->permission_model->list_roles($id);

		// TODO rever essas variaveis
		$this->data["user_obj"] 		= $id?$this->user_model->get($id):new stdClass();
		$this->data["user"]				= $this->data["user_obj"];
		$this->data["states"] 			= $this->user_model->get_states();
		// dump($this->data["user"]);
		$this->load_view('users/create_edit');
	}

	function list_users(){
		$this->load->model(array("company_model","user_model","permission_model"));
		$page = $this->input->post('page', TRUE);
		$this->data["users"] 			= $this->user_model->get_by_company_with_permissions($page);
		$this->data["permissions"]		= $this->permission_model->list_roles();
		$html = $this->load->view('users/user_list_block', $this->data, TRUE);
		$this->output->set_content_type('text/plain', 'UTF-8')->set_output($html);
	}

	function remove($id){
		$this->load->model("company_model");

		if($this->data["logged_user"]->developer != 1 ){
			$user_to_delete = $this->company_model->db->get_where("users",array("id"=>$id))->row();
			if($user_to_delete->company_id != $this->data["logged_user"]->company_id){
				redirect(site_url("user"));
				exit;
			}
		} 

		$this->company_model->db->delete('users', array("id"=>$id));
		redirect(site_url("user"));
	}


	function register(){
		$this->load->model("user_model");

		$user_arr = $this->input->post();
		$this->user_model->insert_or_update($user_arr)->set_role($user_arr['role_id']);

		redirect(site_url("user"));
	}

}
?>