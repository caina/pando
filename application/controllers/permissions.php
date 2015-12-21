<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissions extends Authenticated_controller {

	var $permissions_hmtl_list;

	function set_role(){
		$this->load->model('permission_model', 'permission');
		$name = $this->input->post('name', TRUE);
		$this->permission->create($name);
	}	

	function change_role(){
		$this->load->model('permission_model', 'permission');
		list($user_id,$role_id) = @explode("-", $this->input->post('role', TRUE));
		$this->permission->set_role($user_id,$role_id);
	}

	function list_roles(){
		$this->load->model('permission_model', 'permission');
		$roles = $this->permission->list_roles();
		foreach ($roles as $role) {
			$data["role"] = $role;
			$data['permissions'] = $this->permission->list_selected_permissions($role->id);
			$this->permissions_hmtl_list .= $this->load->view('users/permission_block', $data, TRUE);
		}
		$this->output->set_content_type('text/plain', 'UTF-8')->set_output($this->permissions_hmtl_list);
	}

	function update_role($role_id){
		$this->load->model('permission_model', 'permission');

		if($this->permission->is_mine_role($role_id)){
			$this->permission->update_permissions($this->input->post('permission', TRUE));
		}
	}
}

?>