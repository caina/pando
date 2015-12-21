<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Screen extends Authenticated_controller {
	
	var $module_data;
	var $module_name;
	var $sql_table;
	var $ci;
	//json

	public function __construct(){
    	parent::__construct(); 
    	$this->ci =& get_instance(); 
    	$this->load->model("screen_model");
    	// $this->screen_model->configure($this->data["logged_user"]->company_id);
    	$this->load->library("form_creator");
    	$this->load->helper("html");
		$this->load->helper("form_helper");
	}

	function load_company_config($module){
		$this->form_creator->load_company_config($module);
		
		$this->data["permissions"] = @$this->form_creator->configuration->possible_actions;
		$this->data["mysql_table"] = $this->form_creator->module_data->mysql_table;

	}

	public function download_csv($table)
	{
		$this->load->helper('download');
		$this->load_company_config($table);
		$this->load->dbutil();
		$query = $this->screen_model->get_db()->query("SELECT * FROM {$table}");	
		
		$delimiter = ",";
		$newline = "\r\n";	
		$date = date("d_m_y_h_s");
		force_download("{$table}_{$date}_csv.csv", $this->dbutil->csv_from_result($query, $delimiter, $newline));
	}

	function listing_ajax(){
		$table = $this->input->post('table');
		$token = $this->input->post('token');
		$this->load_company_config($table);

		$records = $this->screen_model->find_by_token($table,$token);
		// dump($records);
		if(!empty($records)){
			foreach ($records as &$record) {
				foreach ($record as $field => &$value) {

					//todo se for pro dynamic creator da pra melhorar isso
					$pk = $this->form_creator->configuration->primary_key;

					$data = array("options" => $this->form_creator->configuration,"value"=>$value,"field_name"=>$field, "pk"=>$record->$pk,"pk_name"=>$pk);
					$new_value = $this->exec_method($field,"show",$data);
					if($new_value!== FALSE){
						$value = $new_value;
					}
				}
				
			}
		}
		$this->form_creator->table_type="";
		$this->form_creator->submitasajax = true;
		//TODO colocar um metodo de retorno descente
		die($this->form_creator->generate_list_table($records));
	}

	function listing($module){
		$this->module_name = $module;
		$this->load_company_config($module);
		if (isset($_POST['delete']))
		{	
			$checked = $this->input->post('checked');
			if (is_array($checked) && count($checked))
			{
				$result = FALSE;
				foreach ($checked as $pid)
				{
					$result = $this->screen_model->delete($this->form_creator->configuration->primary_key, $pid);
				}
				set_message(count($checked)." Registros deletados");
			}
		}

		// se usar idiomas, busca filtrando pelo primeiro idioma
		if(!empty($this->form_creator->configuration->languages)){
			$language_id = $this->screen_model->get_db()->get_where("mngr_languages",array("identification"=>$this->form_creator->configuration->languages[0]))->row()->id;
			$records = $this->screen_model->find_all_by_language($this->form_creator->sql_table,$language_id);
		}else{
			$records = $this->screen_model->find_all($this->form_creator->sql_table);
		}
		// essa parte do codigo eh pra quando vai ter apenas 1 registro
		// faz insert de 1 registro caso nao tenha e redireciona pro mesmo
		if(!in_array("c", $this->data["permissions"])){
			if(empty($records)){
				$table = $this->data["mysql_table"];
				$query = "INSERT INTO `{$table}` (`".$this->form_creator->configuration->primary_key."`) VALUES (NULL);";
				$this->screen_model->database_client->query($query);
				$records = $this->screen_model->find_all($this->form_creator->sql_table);
			}
			$id = current($records)->id;
			redirect("screen/edit/{$this->form_creator->sql_table}/{$id}");
		}

		// checa se esta no layout certo
		if(isset($this->form_creator->configuration->layout) && $this->form_creator->configuration->layout != "default"){
			redirect("screen/".$this->form_creator->configuration->layout."/{$this->form_creator->sql_table}/{$id}");
		}

		if(!empty($records)){
			foreach ($records as &$record) {
				foreach ($record as $field => &$value) {

					//todo se for pro dynamic creator da pra melhorar isso
					$pk = $this->form_creator->configuration->primary_key;

					$data = array("options" => $this->form_creator->configuration,"value"=>$value,"field_name"=>$field, "pk"=>$record->$pk,"pk_name"=>$pk);
					$new_value = $this->exec_method($field,"show",$data);
					if($new_value!== FALSE){
						$value = $new_value;
					}
				}
				
			}
		}
		$this->data['table'] = $this->form_creator->generate_list_table($records);
		$this->load_view('screen/list');
	}

	function split($module, $id=false){
		$this->module_name = $module;
		$this->load_company_config($module);
		$rescords_find=false;
		if($id){
			$rescords_find = $this->screen_model->find($id);
		}

		$this->data['form'] = $this->form_creator->create_form($rescords_find);

		
		// se usar idiomas, busca filtrando pelo primeiro idioma
		if(!empty($this->form_creator->configuration->languages)){
			$language_id = $this->screen_model->get_db()->get_where("mngr_languages",array("identification"=>$this->form_creator->configuration->languages[0]))->row()->id;
			$records = $this->screen_model->find_all_by_language($this->form_creator->sql_table,$language_id);
		}else{
			$records = $this->screen_model->find_all($this->form_creator->sql_table);
		}

		if(!empty($records)){
			foreach ($records as &$record) {
				foreach ($record as $field => &$value) {

					//todo se for pro dynamic creator da pra melhorar isso
					$pk = $this->form_creator->configuration->primary_key;

					$data = array("options" => $this->form_creator->configuration,"value"=>$value,"field_name"=>$field, "pk"=>$record->$pk,"pk_name"=>$pk);
					$new_value = $this->exec_method($field,"show",$data);
					if($new_value!== FALSE){
						$value = $new_value;
					}
				}
				
			}
		}

		
		$this->data['table'] = $this->form_creator->generate_list_table($records);
		$this->load_view('screen/'.$this->form_creator->configuration->layout);
		
	}

	


	function create($module){
		$this->module_name = $module;
		$this->load_company_config($module);

		$action_method = @$this->input->post('save', TRUE);
		if ($this->input->post('save'))
		{
			if ($insert_id = $this->save())
			{
				set_message("Registro salvo com sucesso");

				if($action_method=="Salvar"){
					redirect(site_url("screen/listing/".$this->data["mysql_table"]));
				}
				// checa se esta no layout certo
				if(isset($this->form_creator->configuration->layout) && $this->form_creator->configuration->layout != "default"){
					redirect("screen/".$this->form_creator->configuration->layout."/{$this->form_creator->sql_table}/{$id}");
				}else{
					redirect(site_url("screen/create/{$this->form_creator->sql_table}"));
				}
			}
			else
			{
				set_message("Erro ao salvar". $this->model->error, 2);
			}

		}

		$this->data['form'] = $this->form_creator->create_form();
		
		$this->load_view('screen/create');
	}



	public function edit($module, $id=false){
		$this->module_name = $module;
		$this->load_company_config($module);

		$action_method = @$this->input->post('save', TRUE);
		if (isset($_POST['save'])){
			if ($this->save('update', $id)){
				set_message("Registro editado com sucesso");
				if($action_method=="Salvar"){
					redirect(site_url("screen/listing/".$this->data["mysql_table"]));
				}
				redirect("screen/edit/{$this->form_creator->sql_table}/$id");
			}
			else{
				set_message("Erro ao editar: " . $this->model->error, 2);
			}
		}
		else if (isset($_POST['delete']))
		{
			$this->auth->restrict($this->dynamic_creator->get_restriction().'.Delete');

			if ($this->model->delete($id))
			{
				// $this->activity_model->log_activity($this->current_user->id, lang('guide_act_delete_record').': ' . $id . ' : ' . $this->input->ip_address(), 'guide');
				set_message("Deletado com sucesso", 1); 
				redirect(SITE_AREA . $this->dynamic_creator->get_path());
			} else
			{
				set_message("Erro ao deletar: " . $this->model->error, 2);
			}
		}

		// checa se esta no layout certo
		if(isset($this->form_creator->configuration->layout) && $this->form_creator->configuration->layout != "default"){
			redirect("screen/".$this->form_creator->configuration->layout."/{$this->form_creator->sql_table}/{$id}");
		}

		if(!empty($this->form_creator->configuration->languages)){
			$this->data['form'] = $this->form_creator->create_form($this->screen_model->find_by_language($id,$this->form_creator->sql_table.'_id'));
		}else{
			$this->data['form'] = $this->form_creator->create_form($this->screen_model->find($id));
			
		}
		$this->load_view('screen/create');
	}



	private function save($type='insert', $id=0)
	{
		// $form = $this->input->post();
		// dump($form);
		// dump($this->form_creator->configuration);
		$post_data=array();
		if(isset($this->form_creator->configuration->languages)){

			if(!$this->screen_model->get_db()->field_exists('mngr_languages_id', $this->form_creator->sql_table)){
				$this->screen_model->get_db()->query("alter table ".$this->form_creator->sql_table." add COLUMN mngr_languages_id int(11) null REFERENCES mngr_languages(id)");
			}
			if(!$this->screen_model->get_db()->field_exists($this->form_creator->sql_table.'_id', $this->form_creator->sql_table)){
				$this->screen_model->get_db()->query("alter table ".$this->form_creator->sql_table." add COLUMN ".$this->form_creator->sql_table."_id int(11) null REFERENCES ".$this->form_creator->sql_table."(".$this->form_creator->configuration->primary_key.")");
			}

			foreach ($this->form_creator->configuration->languages as $language) {
				$post_data[$language] = $this->input->post($language);	
			}
		}else{
			$post_data[] = $this->input->post();
			// dump($post_data);
		}

		// Explode os idiomas aqui
		// entra em loop de idiomas
		// verifica se a tabela tem opcão pra idiomas
		// cria os campos
		// vincula os ids
		// remover toda busca por post nativa
		$vinculate_id=false;
		foreach ($post_data as $lang =>$p_data) {
			// dump($p_data);
			if ($type == 'update') {
				$id = $p_data[$this->form_creator->configuration->primary_key];
				$_POST['id'] = $id;
			}

			$data = array();
			$data = $this->form_creator->convert_post_data($p_data,$lang);
			foreach ($data as $field => &$value) {
				$val = $this->exec_method($this->form_creator->element_clear_name($field),"save_action", array("value"=>$value,"name"=>$this->form_creator->element_clear_name($field)));
				if($val !== FALSE){
					$value = $val;
				} 
			}
			// dump($data);
			if ($type == 'insert'){

				if(isset($this->form_creator->configuration->languages)){
					$data[$this->form_creator->sql_table."_id"] = $vinculate_id;
					$data["mngr_languages_id"] = $this->screen_model->get_db()->get_where("mngr_languages",array("identification"=>$lang))->row()->id;
				}

				$id = $this->screen_model->insert($data);
				if (is_numeric($id))
				{
					$return = $id;
				} else
				{
					$return = FALSE;
				}

				if(!$vinculate_id){
					$vinculate_id = $id;
				}

			}
			else if ($type == 'update')
			{
				$return = $this->screen_model->update($id, $data);
			}



			if(isset($this->form_creator->configuration->components->out_of_form)){
				foreach ($this->form_creator->configuration->components->out_of_form as $component_name => $component_configurations) {
					$component_configurations = $this->form_creator->get_default_config($component_configurations);
					$component_configurations->form_data = $data;
					$component_configurations->table_config = $this->form_creator->configuration;
					$this->load->library("components/{$component_name}",(array)$component_configurations);
					$this->$component_name->save_action($id);
				}
			}
		}
		// foreach ($components_insert as $component => $value) {
		// 	$config = $this->get_default_config($this->configuration->components->$component);
		// 	// $component_name = $component;
		// 	$this->load->library("components/".$component,$config);
		// 	$this->$component->insert($id, $value);
		// }

		return $return;
	}

	function component_execute_ajax($table, $component_name){
		$this->load_company_config($table);
		$id = @$this->input->post('id');
		$this->form_creator->configuration->to_table = $table;
		$this->form_creator->configuration->post_data =  $this->input->post();
		
		$this->load->library("components/{$component_name}",(array)$this->form_creator->configuration);
		$result = $this->$component_name->ajax_execution($id);
		$this->output->set_content_type('application/json')->set_output(json_encode($result));
		
		// dump($this->form_creator->configuration);
	}

	private function exec_method($field_name,$method,$data_send=array()){
		if(isset($this->form_creator->configuration->options->fields)){
			$fields =$this->form_creator->configuration->options->fields;
			foreach ($fields as $name=>$field) {
				if($name == $field_name){
					if(isset($field->plugin)){
						$lib = $field->plugin;
						$this->load->library("forms/".$field->plugin);
						if(method_exists($this->$lib,$method)){
							$data_send = array_merge((array)$field,$data_send);
							$action_result = $this->$lib->$method($field,$data_send);
							if($action_result !== FALSE){
								//todo verificar se eh do mesmo tipo que o field o retorno
								return $action_result;
							}
						}
					}
				}
			}
		}
		return false;
	}


	//TODO isto eh uma porcaria no modo automatico, removi por enquanto
	private function get_validation_rules($field){

		//check on config
		if(isset($this->config->options->fields->$field->validation)){
			return $this->config->options->fields->$field->validation;
		}

		return "";
		//check on database
		$validation = "";
		foreach ($this->table_structure as $table) {
			if($table->name == $field){
				if($table->primary_key==1){
					return false;
				}
				
				if($table->default ==""){
					$validation.="required|";
				}
				switch ($table->type) {
					case 'varchar':
					// pode ser qualquer coisa? hmm
						$validation.="";
						break;
					case "tinyint":
						$validation .= "is_natural|";
					break;
					case "int":
						$validation .= "integer|";
					break;
				}
				//corrigir isso algum dia, tem que terminar com algum caracter
				if(!in_array($table->type, array("text"))){
					$validation .="max_length[{$table->max_length}]";
				}else{

					$validation .="min_length[1]";
				}
			}
		}


		if(!empty($validation))
			return $validation;


		return false;
	}

	public function validate_post(){
		$form_validation = $this->ci->load->library('form_validation');
		
		$this->table_structure = $this->get_table_structure();
		foreach ($this->table_structure as $table) {
			$validation = $this->get_validation_rules($table->name);
			if($validation !== FALSE && !$this->is_field_on_blacklist($table->name)){
				$form_validation->set_rules($table->name,$this->get_table_label($table->name),$validation);
			}
			
		}

		if ($form_validation->run() === FALSE){
			return FALSE;
		}

		return true;
	}

	public function clean_insert(){
		$model = $this->ci->load->model($this->config->module, null, true);
		$this->check_draft_id();

		$data = array("is_draft"=>1);
		$post = $this->convert_post_data($data);
		$model->insert($post);
		// dump($post);die;
		
	}


	function galery_image_upload(){
		if(isset($_FILES)){
			foreach ($_FILES as $key => $file) {
				if(empty($file["name"])){
					continue;
				}
				$image = send_image_to_client($key);
			}
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($image));
	}


}

?>