<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modules extends Authenticated_controller {

	/****
	Tela que gera o menu
	**/

	var $default_json='{
"languages":["PT","EN","ES","ITA"],
  "primary_key": "id",
  "labels": {
    "name": "Nome",
    "text": "Texto",
    "link": "Link",
    "teste_id": "ie"
  },
  "remove_list": [
    "id"
  ],
  "possible_actions": [
    "c",
    "r",
    "u",
    "d"
  ],

  "components": {
    "out_of_form": {
      "onetomany": {
        "table": "ligacao",
		"texto_header":"Teste de vinculo",
        "fieldKey": "id_teste",
        "myfieldKey": "id"
      }
    }
  },

  "options": {
    "foreign_keys": {
      "teste_id": {
        "table": "teste",
        "field": "nome",
        "label": "Teste mesmo",
        "filter": "id"
      }
    },
    "fields": {
      "title":{
       "required":true,
       "mask":"WWWW",
       "tooltip":"so pra lembrar que isso existe"
      },
      "image": {
        "plugin": "single_upload",
        "size": "250x220"
      }
    }
  }
  
}';


	
	function index($id=false){
		$this->load->model(array("module_model","company_model","snippet_model"));
		
		$this->data["database_module"]   = $this->module_model->findByCompany($this->data["logged_user"]->company_id);
		$this->data["database_company"]  = $this->company_model->list_developers_companies();
		$this->data["database_snippets"] = $this->snippet_model->listAll();
		$this->data["module_icons"]		 = $this->module_model->list_possible_icons();
		$this->data["database_menus"]   = $this->module_model->list_menus($this->data["logged_user"]->company_id);
		$this->data["default_json"]	= $this->default_json;
		
		$this->data["documentation"] = $this->load->view('modules/documentation',false, TRUE);
		$this->data["client_database_tables"] = $this->module_model->list_tables();

		if($id){
			$this->data["module_obj"] = $this->module_model->get($id);
			$this->verify_module();
		}

		$this->top_menu_html = '<div class="pull-right"><a href="'.site_url("modules").'" class="btn btn-info" ><i class="fa fa-file-text"></i> Criar novo módulo</a></div>';

		$this->load_view('modules/list');
	}

	function delete_module($module_id){
		$this->load->model("module_model");
		$this->module_model->get($module_id);
		if($this->module_model->is_mine()){
			$this->module_model->delete_module();
			set_message("Módulo deletado com sucesso");
		}else{
			set_message("Operação negada, módulo não pertence á esta empresa",2);
		}
		redirect("modules");
	}

	function verify_module(){
		if(!$this->module_model->is_mine()){
			set_message("Este módulo não pertence á esta empresa",2);
			redirect("modules");
		}
	}

	function register(){
		$this->load->model("module_model");
	
		$post_data = $this->input->post();
		if($post_data['snippet_id'] != 0){
			$database_snippet = $this->module_model->db->get_where("snippet",array("id"=>$post_data['snippet_id']))->row();
			if(!empty($database_snippet->run_command)){
				list($lib_folder,$lib,$command) = explode("/", $database_snippet->run_command);
				$this->load->library("{$lib_folder}/{$lib}", array(), $lib);
				$this->$lib->$command();
			}
		}

		$this->module_model->retrive_post($this->input->post());
		$this->module_model->register();

		if($this->module_model->object->is_valid==1){
			set_message("Módulo ".$this->module_model->object->name." ".$this->module_model->operation_name." com sucesso");
			redirect("modules");
		}else{
			set_message("Falha ao ".$this->module_model->operation_name." módulo '".$this->module_model->object->name."',  ".$this->module_model->object->problem_message,2);
			redirect(site_url("modules/index/".$this->module_model->object->id));
		}
	}

	function create_menu(){
		$this->load->model("module_model");

		$data = array();
		$data["company_id"] = $this->data["logged_user"]->company_id;
		$data["label"]		= $this->input->post("label");
		$this->module_model->db->insert("menu",$data);

		redirect("modules/index");
	}

	function remove_menu($id){
		$this->load->model("module_model");

		$this->module_model->db->where("id",$id)->delete("menu");
		redirect("modules/index");

	}
	

	function set_menu(){
		$this->load->model("module_model");

		$menu_module_id = $this->input->post("menu_module_id");
		list($menu_id, $module_id) = explode("_", $menu_module_id);

		$data = array();
		$data["menu_id"] = $menu_id;

		$this->module_model->update($module_id,$data);
	}


	function run_query(){
		$this->load->model("screen_model");
		$query = $this->input->post("runquery");
		$this->screen_model->get_db()->query($query);
		redirect("modules/index");
	}

	function active_inactive(){
		$this->load->model("screen_model");

		$ativo = $this->input->post('active');
		$ativo = $ativo=="false"?0:1;
		$data = array("active"=>$ativo);
		$this->screen_model->db->update('module', $data, array("id"=>$this->input->post('post_id')));
	}

	function configure_json(){
		$this->load->model(array("module_model"));

		$tabela = $this->input->post('table');
		if(empty($tabela)){
			exit;
		}
		echo $this->module_model->populate_json($tabela);
		die();
		
	}

}
