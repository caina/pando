<?php
class Form_creator {

	var $ci;
	var $db;
	var $html;
	var $form;
	
	var $data;
	var $user_obj;
	var $form_data;
	var $sql_table;
	var $mngr_token;
	var $module_name;
	var $module_data;
	var $configuration;
	var $submitasajax = false;
	var $table_structure = false;
	var $table_type = "data-table";
	var $blacklist = array("is_draft","mngr_token");
	var $primary_key_value=false;

	function __construct(){
		$this->ci =& get_instance();
		$session_user = get_logged_user();
		

		if(empty($session_user)){
			redirect('login/logout');
		}
		$this->ci->load->helper("html");
		$this->ci->load->helper("form_helper");
		$this->ci->load->library('table');
		$this->ci->load->model("screen_model");
		$this->user_obj = (unserialize($session_user));
	}

	function load_company_config($module){
		
		$this->module_name = $module;
		$this->module_data = $this->ci->screen_model->get_module_data($this->user_obj->company_id, $this->module_name);	
		$this->configuration = json_decode($this->module_data->json_config);

		$this->set_configuration($this->configuration);
		$this->validate_config();
		$this->sql_table = $this->module_data->mysql_table;
		$this->ci->screen_model->table_name = $this->sql_table;
		$this->ci->screen_model->primary_key = $this->configuration->primary_key;
	
	}

	public function set_configuration($configuration){
		$this->configuration = $configuration;
	}

	/***
		GENERATE LIST TABLE FOR INDEX
		ON BONFIRE STYLE
	*/
	public function generate_list_table($data=false){
		$this->ci->table->set_template($this->get_table_template());
		$this->ci->table->set_heading($this->get_table_headers());
		
		if($data !== FALSE){
			foreach ($data as $list_data) {
				$row = array();
				
				if($this->can_perform("u") || $this->can_perform("d")){
					$primary_key = $this->get_primary_key();
					$edit_anchor = "";
					if($this->can_perform("u")){
						$link_ = site_url("/screen/edit/{$this->sql_table}/{$list_data->$primary_key}");
						

						$edit_anchor = " &nbsp; <a href='{$link_}' class='btn btn-xs btn-warning'><i class='fa fa-pencil'></i> </a>";
						// $edit_anchor = anchor(site_url("/screen/edit/{$this->sql_table}/{$list_data->$primary_key}"), '<i class="icon-edit">&nbsp;</i>' .  $list_data->$primary_key);
					}
					$row[] = form_checkbox("checked[]", $list_data->$primary_key, FALSE).$edit_anchor;
				}
				//fields
				foreach ($list_data as $key => $value) {
					if(!$this->is_field_on_blacklist($key))
						$row[] = $this->list_field_value($key, $value);
				}

				$transient_row = $this->generate_transient("r",$list_data);
				if($transient_row){
					$row[] =  $transient_row;
				}
				
				$this->ci->table->add_row($row);
			}
		}

		$form_class = $this->submitasajax?"form_ajax form_ajax_clear":"";

		// table  table-hover table-bordered 
		$html = "";
		if($this->submitasajax){
			$html = "<div class='table-responsive'>";
		}
		$html .= form_open(site_url("screen/listing/".$this->sql_table), 'class="form-horizontal '.$form_class.'"');
		$html .= $this->ci->table->generate();
		$html .= form_close();
		if($this->submitasajax){
			$html .= "</div>";
		}

		return $html;
	}

	/*****
		CREATE THE FORM
		EDIT AND CREATE
	*/
	public function create_form($fields_data= false){

		$this->table_structure = $this->get_table_structure();
		$hiddens = array();
		$hiddens["mngr_token"] = isset($this->mngr_token)?$this->mngr_token: uniqid();
		$action = "create";
		$pos_url = "";
		//hiddens only on update
		if($fields_data !== FALSE){
			$action = "edit";
			$primary_key = $this->get_primary_key();

			if(isset($this->configuration->languages)){

				foreach ($this->configuration->languages as $language) {
					$hiddens["{$language}[{$primary_key}]"] = $fields_data[$language]->$primary_key;
				}
				$this->primary_key_value = $hiddens[$this->configuration->languages[0]."[{$primary_key}]"];
				$pos_url = "/".$hiddens[$this->configuration->languages[0]."[{$primary_key}]"];
				if(isset($fields_data->mngr_token)){
					$hiddens["mngr_token"] = $fields_data->mngr_token;
				}

			}else{
				$hiddens[$primary_key] = $fields_data->$primary_key;
				$this->primary_key_value = $hiddens[$primary_key];
				$pos_url = "/".$hiddens[$primary_key];
				if(isset($fields_data->mngr_token)){
					$hiddens["mngr_token"] = $fields_data->mngr_token;
				}
			}

		}



		$form_class = $this->submitasajax?"form_ajax":"";
		$form = "";
		$form .= form_open_multipart(site_url("screen/{$action}/{$this->sql_table}{$pos_url}"), 'class=" '.$form_class.' validade_form" data-toggle="validator" role="form" name="form_upload"', $hiddens);


		$alias_form = array("");
		if(isset($this->configuration->languages)){

			$alias_form = $this->configuration->languages;
			$form .='
				<h2>Idiomas</h2><p>Selecione a aba com o idioma que deseja traduzir</p>
				<div class="tabs-wrapper">
						<ul class="nav nav-tabs">';
			$active_seted = false; 						
			foreach ($alias_form as $alias) {
				$class_tmp = $active_seted?'':'active';
				$form .="<li class='{$class_tmp}'><a href='#tab-{$alias}' data-toggle='tab'>{$alias}</a></li>";
				$active_seted = true;
			}

			$form .="</ul><div class='tab-content'>";

		}

		$active_seted = false;
		foreach ($alias_form as $alias) {
			if(!empty($alias)){
				$class_tmp = $active_seted?'':'active';
				$form .= "<div class='tab-pane fade in {$class_tmp}' id='tab-{$alias}'>";
				$active_seted = true;
			}
			foreach ($this->table_structure as $field_info) {
				if($this->can_show_input($field_info)){
					// if($field_info->name=="title")
					$field_info->name_append = $alias;
					
					// isso vai selecionar apenas os dados do idioma ;)
					$field_data_ = !empty($alias)?$fields_data[$alias]:$fields_data;
					$form .= $this->generate_input($field_info, $field_data_);
				}
			}
			if(!empty($alias)){
				$form .= "</div>";
			}		
		}

		if(isset($this->configuration->languages)){
			$form .= '</div></div>';
		}

		// Before CREATE THE FORM, WE WILL INSERT HERE THE COMPONENTS
		if(isset($this->configuration->components)){
			foreach ($this->configuration->components as $component_name => $component_configurations) {
				//Only components on the form, out of form comes next ;)
				if($component_name == "out_of_form")
					continue;
				$component_configurations = $this->get_default_config($component_configurations);
				$component_configurations->information = $fields_data;
				$component_lib = $this->ci->load->library("components/{$component_name}",$component_configurations);
				
				$form .= $this->$component_name->generate();
			}
		}

		$cancel_link = anchor(site_url("screen/listing/{$this->sql_table}") , "Cancelar", "class='btn btn-warning'");
		

		$save_continue_button = "<button type='submit' name='save'  value='save_continue' class='btn btn-primary'> <span class='fa fa-angle-right'></span> Salvar e continuar</button>";
		if(isset($this->configuration->layout) && $this->configuration->layout != "default"){
			$save_continue_button = "";
		}

		$form .= "
			<div class='form-group'>
				<div class='col-lg-12'>
					<button type='submit' name='save'  value='Salvar' class='btn btn-success'>
					<span class='fa fa-plus'></span>
					Salvar</button>

					{$save_continue_button}
					
				</div>

				
			</div>
		";
		
		

		$form .= form_close();

		$html_outpu = $form;


		// BEFORE THE FORM, WE WILL PUT SOME EXTRA COMPONENTS, HELPFULL IF YOU WANNA CREATE SOMETHING OUT OF THE FORM
		if(isset($this->configuration->components->out_of_form)){
			foreach ($this->configuration->components->out_of_form as $component_name => $component_configurations) {
				$component_configurations = $this->get_default_config($component_configurations);
				$component_configurations->mngr_token = $hiddens["mngr_token"];
				$this->ci->load->library("components/{$component_name}",(array)$component_configurations);
				$html_outpu .= $this->ci->$component_name->generate();
			}
		}
		return $html_outpu;
	}

	public function teee(){
		return "testeee";
	}
	


	public function get_default_config($component_configurations){
		if(!isset($component_configurations->to_table)){
			$component_configurations->to_table = $this->sql_table;
		}
		if(!isset($component_configurations->primary_key_value)){
			$component_configurations->primary_key_value = $this->primary_key_value;
		}
		if(!isset($component_configurations->foreign_key)){
			$component_configurations->foreign_key = $this->configuration->primary_key;
		}
		if(!isset($component_configurations->module)){
			// $component_configurations->module = $this->configuration->module;
		}
		if(!isset($component_configurations->path)){
			// $component_configurations->path 	= SITE_AREA .$this->get_path();
		}
		return $component_configurations;
	}


	private function get_form_hiddens(){
		$this->table_structure = $this->get_table_structure();
		$hiddens = array();
		foreach ($this->table_structure as $field_info) {
			if((isset($this->configuration->primary_key) && @$this->configuration->primary_key == $field_info->name) || $field_info->primary_key == 1){
				// $hiddens[$field_info->name] = 
			}
		}
	}

	private function generate_input($field_info, $value = false){
		$this->ci->load->helper("form_helper");	
		$html="";
		//pk?
		if((isset($this->configuration->primary_key) && @$this->configuration->primary_key == $field_info->name) || $field_info->primary_key == 1){
			return false;
		}else{

			$name = $field_info->name;

			if($value !== FALSE){
				if(is_object($value)){
					$value = $value->$name;
				}
			}	

			if(!empty($field_info->name_append)){
				$name = $field_info->name_append."[{$name}]";
			}
			$data = array(
              'name'        => $name,
              (!empty($field_info->required)?'required':'')    => true,
              'mask'		=> @$field_info->mask,
              'id'          => $name,
              'value'       => ($value!==null) ? $value : '',
              'maxlength'   => $field_info->max_length,
              'size'        => '150',
              "label"		=> $this->get_table_label($field_info->name),
              "tooltip"		=> (isset($this->configuration->options->fields->$name->tooltip)?$this->configuration->options->fields->$name->tooltip:"")
            );

			if($this->is_field_foreign_key($name)){
				$fk_lib = new stdClass();
				// echo $name;
				//da pra fazer melhor né?
				$field_info->name 					= $name;
				$field_info->value 				    = $value;
				$field_info->label 					= $this->get_table_label($name);
				$construct_data 					= array();
				$construct_data["database_data"] 	= $this->foreign_key_search($this->configuration->options->foreign_keys->$name,false);
				$construct_data["config"] 			= $this->configuration;
				$construct_data['field_info'] 		= $field_info;
				$construct_data['database']			= $this->ci->screen_model;
				
				$this->ci->load->library("forms/foreign_key",$construct_data);
				$this->ci->foreign_key->set_config($construct_data);
				
				//usando versão basica, deve ser mudada para algo mais inteligente
				$html = $this->ci->foreign_key->basic_generate();
			}else{
				$imput = $this->get_imput_by_options($name,$data);
				if($imput !== FALSE){
					$html = $imput;
				}else{
					if(isset($imput->span_add_on)){
						$imput = $imput->span_add_on;
					}

					$html = $this->basic_html_creator($field_info, $data);
				}
			}

			return $html;
		}

		// dump($field_info);die;
	}

	function basic_html_creator($form_data, $data){
					// dump($form_data);
		switch ($form_data->type) {
			case 'text':
				return form_textarea($data, '', '', '', '','');
				break;
			
			default:
				return form_input($data, '', '', '', '','');
				break;
		}
		
	}

	function element_clear_name($name){
		$matches = array();
		$t = preg_match('/\[(.*?)\]/s', $name, $matches);
		return !empty($matches[1])?$matches[1]:$name;
	}

	/****
	DISCOVER WHAT TYPE OF IMPUT IS THIS
	*/
	private function get_imput_by_options($name,$data = array()){
		//TODO COLOCAR OS CAMPOS DEFAULT
		// dump($this->configuration->options->fields);

		$lib_name = "";
		$name = $this->element_clear_name($name);

		if(isset($this->configuration->options->fields->$name)){
			$optObj = $this->configuration->options->fields->$name;
			// dump($optObj);die;
			if(isset($optObj->plugin)){
				$this->ci->load->library("forms/".$optObj->plugin);
				$lib_name = $optObj->plugin;

				if(method_exists($this->ci->$lib_name,"set_options")){
					$this->ci->$lib_name->set_options($optObj);
				}
				return $this->ci->$lib_name->generate($data);
				// return $lib->generate($data);
			}
			// TODO verificar se isso nao era util pra algo
			// else{
			// 	return $optObj;
			// }
		}
		return false;
	}

	/***
	*/
	private function get_table_headers(){
		$this->table_structure = $this->get_table_structure();
		$header;

		if($this->can_perform("u") || $this->can_perform("d"))
			$header[] = "<input class='check-all' type='checkbox' /> Op&ccedil;&otilde;es ";
			
		foreach ($this->table_structure as $table) {
			if(!$this->is_field_on_blacklist($table->name)){
				$header[] = $this->get_table_label($table->name);
			}
		}

		if(isset($this->configuration->transient)){
			foreach ($this->configuration->transient as $transients) {
				foreach ($transients as $key => $value) {
					$can_perform = true;
					if(isset($value->view)){
						$can_perform_options = explode("|", $value->view);
						$can_perform = in_array("r", $can_perform_options);
					}
					if($can_perform){
						if(isset($value->label)){
							$header[] = $value->label;
						}
					}
				}
			}
		}

		return $header;
	}

	/***

	*/
	private function list_field_value($key, $value){
		if(isset($this->configuration->options)){
			if($this->is_field_foreign_key($key)){
				$this->configuration->options->foreign_keys->$key->value = $value;
				return $this->foreign_key_search($this->configuration->options->foreign_keys->$key, 1);
			}
		}
		return $value;
	}

	private function is_field_foreign_key($field){
		if(isset($this->configuration->options->foreign_keys)){
			return isset($this->configuration->options->foreign_keys->$field);
		}
		return false;
	}

	/****
		JSON NEEDED
	 "foreign_keys": {
	      "user_id": {
	        "table": "users",
	        "field": "username",
	        "filter": "id"
	      }
	    },
	*/
	private function foreign_key_search($object,$limit=1){
		
		if(isset($object->value)){
			$filter = "id";
			if(isset($object->filter)){
				$filter = $object->filter;
			}
			$this->ci->screen_model->database_client->where($filter,$object->value);
		}

		$this->ci->screen_model->database_client->from($object->table);
		if($limit === FALSE){
			return $this->ci->screen_model->database_client->get()->result();	
		}
		$this->ci->screen_model->database_client->limit($limit);
		$result = $this->ci->screen_model->database_client->get()->row();

		$field = $object->field;
		if(isset($result->$field)){
			return $result->$field;
		}
		return $object->value;
	}

	private function can_show_input($field){
		if(isset($this->configuration->blacklist)){
			if(is_array($this->configuration->blacklist)){
				$this->blacklist = array_merge($this->blacklist,$this->configuration->blacklist);
			}
		}
		$this->blacklist = array_merge($this->blacklist,array("mngr_languages_id",$this->sql_table."_id"));
		return !in_array($field->name, $this->blacklist);
	}

	/****
	*/
	private function is_field_on_blacklist($index){
		if(isset($this->configuration->remove_list)){
			$hide_elements = array("mngr_languages_id",$this->sql_table."_id");
			return in_array($index,array_merge($this->configuration->remove_list,$this->blacklist,$hide_elements));
		}
		return false;
	}
	/***
		FIND VARIABLES ON LABEL CONFIG OBJECT,
		IF IS SET, USE HIS LABEL
	*/
	private function get_table_label($index){
		if(isset($this->configuration->labels)){
			if(isset($this->configuration->labels->$index)){
				return $this->configuration->labels->$index;
			}
		}
		return $index;
		// if(isset($this->config->))
	}
	/****
		CARREGA OS DADOS DA ESTRUTURA DA TABELA, APENAS ACESSIVEL DE DENTRO
	*/
	private function get_table_structure(){
		$table = isset($this->configuration->table)?$this->configuration->table:$this->sql_table;
		$this->table_structure = $this->ci->screen_model->database_client->field_data($table);
		foreach ($this->table_structure as &$field_info) {
			$field_name = $field_info->name;
			$field_info->required = @$this->configuration->options->fields->$field_name->required;
			$field_info->mask = @$this->configuration->options->fields->$field_name->mask;
		}
		return $this->table_structure;
	}

	function validate_config(){
		// if(!isset($this->configuration->table)){
		// 	echo("ERRO! TABLE NO CONFIG NAO FOI SETADA");
		// 	dump($this->configuration);
			
		// }
		// if(!isset($this->configuration->module)){
		// 	echo("ERRO! MODULE NO CONFIG NAO FOI SETADA");
		// 	dump($this->configuration);
			
		// }
		if(!isset($this->configuration->possible_actions)){
			$this->configuration->possible_actions = array("c","r","u","d");
		}
	}

	private function get_table_template(){
		$delete_button = "";
		if($this->can_perform("d")){
			$delete_button = '
			<tfoot>
				<tr>
					<td colspan="99999">
					<input type="submit" name="delete" id="delete-me" class="btn btn-danger " value="Deletar" onclick="return confirm(\' Realmente deseja deletar \')">
					</td>
				</tr>
			</tfoot>';
		}
		$table_id = $this->table_type;
		$table_class = $this->submitasajax?"table  table-hover table-bordered ":"";

		
		return array (
                    'table_open'          => '<table id="'.$table_id.'" class="table table-hover '.$table_class.'">',

                    'heading_row_start'   => '<tr>',
                    'heading_row_end'     => '</tr>',
                    'heading_cell_start'  => '<th>',
                    'heading_cell_end'    => '</th>',

                    'row_start'           => '<tr>',
                    'row_end'             => '</tr>',
                    'cell_start'          => '<td>',
                    'cell_end'            => '</td>',

                    'row_alt_start'       => '<tr>',
                    'row_alt_end'         => '</tr>',
                    'cell_alt_start'      => '<td>',
                    'cell_alt_end'        => '</td>',

                    'table_close'         => '
                    	
									'.$delete_button.'
								
                    </table>'
              );
	}

	public function get_restriction(){
		if(isset($this->configuration->permission)){
			return $this->configuration->permission;
		}else{
			if(isset($this->config->path)){
				$permission = explode("/", $this->configuration->path);
				return ucfirst($permission[1]).".".ucfirst($permission[0]);
			}
			die("ADICIONE AS PERMISSOES");
		}
	}

	public function get_path(){
		if(isset($this->configuration->path)){
			return "/".$this->configuration->path;
		}
		return "";
	}

	private function get_primary_key(){
		$primary_key = "id";
		if(isset($this->configuration->primary_key)){
			$primary_key = $this->configuration->primary_key;
		}
		return $primary_key;
	}


	public function check_draft_id(){
		$model = $this->ci->load->model($this->config->module, null, true);
		$this->table_structure = $this->get_table_structure();

		$is_draft = false;
		foreach($this->table_structure as $structure){
			if($structure->name == "is_draft"){
				$is_draft = true;
			}
		}

		if(!$is_draft){
			$fields = array(
				"is_draft"=>array(
               	 'type' => 'INT',
                 'constraint' => 1, 
                 'unsigned' => FALSE,
                 'auto_increment' => FALSE,
                 'default' => '0'
					)
			);
			$model->dbforge->add_column($this->config->table, $fields);
		}
	}

	function can_perform($action){
		return in_array($action, $this->configuration->possible_actions);
	}

	/****
	GET POST DATA THAT MATCH WITH THE DATABASE
	*/
	public function convert_post_data($post_data,$lang=false){
		// $_FILES["PT"]["icon"] = array("name"=>"rescue_32x32.png","type"=>"image/png");
		// dump($_FILES);
		if($_FILES){
			//maneira para n remover na hora de dar update, ficou meio bosta...
			if(!isset($this->configuration->languages)){
				foreach ($_FILES as $key => $file) {
					// dump($file);
					if(empty($file['name'])){
						unset($_FILES[$key]);
					}
				}
			}else{
				// ficou uns nomes meio estranhos aqui :/
				if($_FILES[$lang]){
					$files_redone = array();
					foreach ($variable as $key => $value) {
						# code...
					}	
				}


			}
			$post_data = array_merge($post_data, $_FILES);
		}

		$this->table_structure = $this->get_table_structure();
		
		$data=array();
		foreach ($post_data as $key => $value) {
			foreach ($this->table_structure as $structure) {
				if($key == $structure->name){
					if(empty($value)){
						$data[$key] = $this->get_default_value($structure);
					}else{
						$data[$key] = $value;
					}
				}
			}
		}
		return $data;
	}

	/***
	converte o dado em brando do input em algo, no caso do inteiro, e pro post nao mandar
	o valor como uma string
	
	TODO fazer com que se tiver plugin, valide no proprio plugin
	*/
	private function get_default_value($structure_obj){
		if($structure_obj->type=="int"){
			if(isset($structure_obj->default)){
				return $structure_obj->default;
			}else{
				return 0;
			}
		}
	}

	private function generate_transient($current_view,$database_data = false){
		if(!isset($this->config->transient))
			return false;
		
		$this->config->crud_position = $current_view;
		$transient = $this->ci->load->library("components/transient",$this->config);
		return $transient->generate($database_data);
	}

}

?>