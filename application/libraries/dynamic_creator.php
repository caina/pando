<?php
/****
	CRIA FORMS USANDO OS DADOS DA TABELA NO BANCO
	E COMBINA COM OS DADOS DE CONFIGURAÇÃO DO MÓDULO
*/
class Dynamic_creator {
	
	var $ci;
	var $db;
	var $html;
	var $form;
	var $config;
	var $form_data;
	var $table_structure = false;


	public function __construct($configuration){
		$this->set_configuration($configuration);

		$this->ci->load->helper("html");
		$this->ci->load->helper("form_helper");
	}

	public function set_configuration($configuration){
		$this->config = $configuration;
		$this->ci =&get_instance();
		$this->db = $this->ci->db;
		$this->validate_config();
	}


	/***
		GENERATE LIST TABLE FOR INDEX
		ON BONFIRE STYLE
	*/
	public function generate_list_table($data=false){
		$table = $this->ci->load->library('table');

		$table->set_template($this->get_table_template());
		$table->set_heading($this->get_table_headers());
		
		if($data !== FALSE){
			//lines
			foreach ($data as $list_data) {
				$row = array();
				
				if($this->can_perform("u") || $this->can_perform("d")){
					$primary_key = $this->get_primary_key();
					$edit_anchor = "";
					if($this->can_perform("u")){
						$edit_anchor = anchor(SITE_AREA . $this->get_path() .'/edit/'. $list_data->$primary_key, '<i class="icon-edit">&nbsp;</i>' .  $list_data->$primary_key);
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
				// if(isset($list_data->is_draft)){
				// 	$row[] = "<i class='".($list_data->is_draft==1?"icon-eye-close":"icon-eye-open")."'>&nbsp;</i>";
				// }
				$table->add_row($row);
			}
		}

		$html  = form_open($this->ci->uri->uri_string());
		$html .= $table->generate();
		$html .= form_close();
		
		return $html;
	}

	/*****
		CREATE THE FORM
		EDIT AND CREATE
	*/
	public function create_form($fields_data= false){
		$this->table_structure = $this->get_table_structure();

		Assets::add_js("jasny-bootstrap.min.js");
		Assets::add_js("modernizr-2.5.3.js");
		Assets::add_css("jasny-bootstrap.min.css");


		$hiddens = array();
		//hiddens only on update
		if($fields_data !== FALSE){
			$primary_key = $this->get_primary_key();
			$hiddens = array($primary_key => $fields_data->$primary_key);
		}

		$form = "";
		$form .= form_open_multipart($this->ci->uri->uri_string(), 'class="form-horizontal" name="form_upload"', $hiddens);
		
		foreach ($this->table_structure as $field_info) {
			if($this->can_show_input($field_info)){
				$form .= $this->generate_input($field_info, $fields_data);
			}
		}		

		// AFTER CREATE THE FORM, WE WILL INSERT HERE THE COMPONENTS
		if(isset($this->config->components)){
			foreach ($this->config->components as $component_name => $component_configurations) {
				$component_configurations = $this->get_default_config($component_configurations);
				$component_lib = $this->ci->load->library("components/{$component_name}",$component_configurations);
				$form .= $component_lib->generate();
			}
		}

		$cancel_link = anchor(SITE_AREA .$this->get_path(), "Cancelar", "class='btn btn-warning'");
		$form .= "
		    <fieldset>
		        <div class='form-actions'>
		            <br/>
		            <input type='submit' name='save' class='btn btn-success' value='Salvar' />
		            <input type='submit' name='save' class='btn btn-success' value='Salvar e continuar' />
		            <!--<input type='submit' name='draft' class='btn btn-primary' value='Rascunho' />-->
		            {$cancel_link}
		        </div>
		    </fieldset>
		";
		
		$form .= form_close();

		return $form;
	}

	private function can_show_input($field){
		$blacklist = array("is_draft");
		if(isset($this->config->blacklist)){
			if(is_array($this->config->blacklist)){
				$blacklist = array_merge($blacklist,$this->config->blacklist);
			}
		}
		return !in_array($field->name, $blacklist);
	}
	public function get_default_config($component_configurations){
		if(!isset($component_configurations->to_table)){
			$component_configurations->to_table = $this->config->table;
		}
		if(!isset($component_configurations->foreign_key)){
			$component_configurations->foreign_key = $this->config->primary_key;
		}
		if(!isset($component_configurations->module)){
			$component_configurations->module = $this->config->module;
		}
		if(!isset($component_configurations->path)){
			$component_configurations->path = SITE_AREA .$this->get_path();
		}

		//TODO ?? precisa disso?
		if(!isset($component_configurations->size)){
			$component_configurations->size = "300x200";
		}
		return $component_configurations;
	}


	private function get_form_hiddens(){
		$this->table_structure = $this->get_table_structure();
		$hiddens = array();
		foreach ($this->table_structure as $field_info) {
			if((isset($this->config->primary_key) && @$this->config->primary_key == $field_info->name) || $field_info->primary_key == 1){
				// $hiddens[$field_info->name] = 
			}
		}
	}

	private function generate_input($field_info, $value = false){
		$this->ci->load->helper("form_helper");	
		$html="";
		//pk?
		if((isset($this->config->primary_key) && @$this->config->primary_key == $field_info->name) || $field_info->primary_key == 1){
			return false;
		}else{

			$name = $field_info->name;

			if($value !== FALSE){
				if(is_object($value)){
					$value = $value->$name;
				}
			}	



			$data = array(
              'name'        => $name,
              'id'          => $name,
              'value'       => ($value!==null) ? $value : '',
              'maxlength'   => $field_info->max_length,
              'size'        => '150',
              "label"		=> $this->get_table_label($field_info->name),
              "tooltip"		=> (isset($this->config->options->fields->$name->tooltip)?$this->config->options->fields->$name->tooltip:"")
            );

			if($this->is_field_foreign_key($name)){
				$fk_lib = new stdClass();
				// echo $name;
				//da pra fazer melhor né?
				$field_info->name 					= $name;
				$field_info->value 				    = $value;
				$field_info->label 					= $this->get_table_label($name);
				$construct_data 					= array();
				$construct_data["database_data"] 	= $this->foreign_key_search($this->config->options->foreign_keys->$name,false);
				$construct_data["config"] 			= $this->config;
				$construct_data['field_info'] 		= $field_info;
				$fk_lib = $this->ci->load->library("forms/foreign_key",$construct_data);
				$fk_lib->set_config($construct_data);
				//usando versão basica, deve ser mudada para algo mais inteligente
				$html = $fk_lib->basic_generate();
			}else{
				$imput = $this->get_imput_by_options($name,$data);
				if($imput !== FALSE){
					$html = $imput;
				}else{
					if(isset($imput->span_add_on)){
						$imput = $imput->span_add_on;
					}
					$html = form_input($data, '', '', '', '','');
				}
			}

			return $html;
		}

		// dump($field_info);die;
	}

	/****
	DISCOVER WHAT TYPE OF IMPUT IS THIS
	*/
	private function get_imput_by_options($name,$data = array()){
		//TODO COLOCAR OS CAMPOS DEFAULT
		if(isset($this->config->options->fields->$name)){
			$optObj = $this->config->options->fields->$name;
			// dump($optObj);die;
			if(isset($optObj->plugin)){
				$lib = $this->ci->load->library("forms/".$optObj->plugin);
				if(method_exists($lib,"set_options")){
					$lib->set_options($optObj);
				}
				return $lib->generate($data);
			}else{
				return $optObj;
			}
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

		if(isset($this->config->transient)){
			foreach ($this->config->transient as $transients) {
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


		//is_draft header?
		// foreach ($this->table_structure as $tb) {
		// 	if($tb->name=="is_draft"){
		// 		$header[] = "Status";
		// 	}
		// }

		return $header;
	}

	/***

	*/
	private function list_field_value($key, $value){
		if(isset($this->config->options)){
			if($this->is_field_foreign_key($key)){
				$this->config->options->foreign_keys->$key->value = $value;
				return $this->foreign_key_search($this->config->options->foreign_keys->$key, 1);
			}
		}
		return $value;
	}

	private function is_field_foreign_key($field){
		if(isset($this->config->options->foreign_keys)){
			return isset($this->config->options->foreign_keys->$field);
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
			$this->db->where($filter,$object->value);
		}

		$this->db->from($object->table);
		if($limit === FALSE){
			return $this->db->get()->result();	
		}

		$this->db->limit($limit);
		$result = $this->db->get()->row();

		$field = $object->field;
		if(isset($result->$field)){
			return $result->$field;
		}
		return $object->value;
	}

	/****
	*/
	private function is_field_on_blacklist($index){
		if(isset($this->config->remove_list)){
			return in_array($index,array_merge($this->config->remove_list,array("is_draft")));
		}
		return false;
	}
	/***
		FIND VARIABLES ON LABEL CONFIG OBJECT,
		IF IS SET, USE HIS LABEL
	*/
	private function get_table_label($index){
		if(isset($this->config->labels)){
			if(isset($this->config->labels->$index)){
				return $this->config->labels->$index;
			}
		}
		return $index;
		// if(isset($this->config->))
	}
	/****
		CARREGA OS DADOS DA ESTRUTURA DA TABELA, APENAS ACESSIVEL DE DENTRO
	*/
	private function get_table_structure(){
		if($this->table_structure === FALSE){
			$this->table_structure = $this->db->field_data($this->config->table);
			// dump(json_encode($this->table_structure));die;
		}
		return $this->table_structure;
	}

	private function validate_config(){
		if(!isset($this->config->table)){
			dump($this->config);
			die("ERRO! TABLE NO CONFIG NAO FOI SETADA");
		}
		if(!isset($this->config->module)){
			dump($this->config);
			die("ERRO! MODULE NO CONFIG NAO FOI SETADA");
		}
		if(!isset($this->config->possible_actions)){
			$this->config->possible_actions = array("c","r","u","d");
		}
	}

	private function get_table_template(){

		$delete_button = "";
		if($this->can_perform("d")){
			$delete_button = '
			<tfoot>
				<tr>
					<td colspan="99999">
					<input type="submit" name="delete" id="delete-me" class="btn btn-danger" value="Deletar" onclick="return confirm(\' Realmente deseja deletar \')">
					</td>
				</tr>
			</tfoot>';
		}

		return array (
                    'table_open'          => '<table id="table-example" class="table table-hover" >',

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
		if(isset($this->config->permission)){
			return $this->config->permission;
		}else{
			if(isset($this->config->path)){
				$permission = explode("/", $this->config->path);
				return ucfirst($permission[1]).".".ucfirst($permission[0]);
			}
			die("ADICIONE AS PERMISSOES");
		}
	}

	public function get_path(){
		if(isset($this->config->path)){
			return "/".$this->config->path;
		}
		return "";
	}

	private function get_primary_key(){
		$primary_key = "id";
		if(isset($this->config->primary_key)){
			$primary_key = $this->config->primary_key;
		}
		return $primary_key;
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
					// $validation.="required|";
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
		dump($post);die;
		
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

	private function can_perform($action){
		return in_array($action, $this->config->possible_actions);
	}

	/****
	GET POST DATA THAT MATCH WITH THE DATABASE
	*/
	public function convert_post_data($post_data){
		// dump($post_data);die;
		$this->table_structure = $this->get_table_structure();
		// dump($this->table_structure);die;
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
		// dump($data);die;
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