<?php 
class Foreign_key {
	
	var $ci;
	var $database_data;
	var $field_info;
	var $config;
	var $field_config;
	var $database;

	public function __construct(){
		$this->ci =&get_instance();
	}

	function set_config($configuration = false){
		if($configuration){
			$this->database_data 	= $configuration["database_data"];
			$this->config 			= $configuration['config'];
			$this->field_info 		= $configuration['field_info'];
			$this->database 		= $configuration['database'];
		}
	}


	/***
	gerador da tela
	*/
	public function basic_generate(){
		$options = array();
		$name = $this->field_info->name;

		$this->field_config = $this->config->options->foreign_keys->$name;
		$field = $this->config->options->foreign_keys->$name->field;
		$filter = isset($this->config->options->foreign_keys->$name->filter)?$this->config->options->foreign_keys->$name->filter:"id";
		
		$has_insert = false;
		if(isset($this->config->options->foreign_keys->$name->insertable)){
			$has_insert = $this->config->options->foreign_keys->$name->insertable;
		}

		$options[0] = "Selecione";
		// dump($this->database_data);
		// dump($field);
		foreach ($this->database_data as $data) {
			$options[$data->$filter] = $this->get_data_field($data,$field);
		}

		// dump($options);die;
		$button_html = "";
		if($has_insert){
			$button_html = $this->create_insert_button();
		}

		$extra = "";
		if(isset($this->field_config->watch)){
			$this->add_watch_method();
			$extra.=" data-watch='".$this->field_config->watch."'";
		}
		
		$html = form_dropdown($name, $options, $this->field_info->value,$this->field_info->label,$extra,'',$button_html);

		return $html;
	}


	private function create_insert_button(){

		// echo "<pre>";
		// print_r($this->field_config);
		$this->add_insert_method();

		$label = isset($this->field_config->label)?$this->field_config->label:$this->field_config->table;
		$table = $this->field_config->table;
		$field = $this->field_config->field;

		$watch = isset($this->field_config->watch)?$this->field_config->watch:null;

		$button = <<<EOT
		<!-- Button to trigger modal -->
		<a href="#form_{$table}" role="button" class="btn btn-success" data-toggle="modal"><i class="icon-plus icon-white"></i></a>
		 
		<!-- Modal -->
		<div id="form_{$table}" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel">Adicionar novo registro: {$label}</h3>
		  </div>
		  <div class="modal-body">
			  <fieldset>
			    <label>{$label}</label>
			    <input type="text" id="input_{$table}" placeholder="{$label}" data-field="{$field}" data-table="{$table}" class="save_fk_external"/>
			  </fieldset>
		  </div>
		  <div class="modal-footer">
		    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancelar</button>
		    <a href="#" id="{$table}" data-watch="{$watch}" class="fk_table_modal_save btn btn-success" >Salvar</a>
		  </div>
		</div>
EOT;

	return $button;
	}

	private function add_insert_method(){
		
		$path = site_url("admin/".$this->config->path."/ajax_execute/foreign_key");
		$field = $this->field_config->field;
		$table = $this->field_config->table;
		$watch_table = isset($this->field_config->watch)?$this->field_config->watch:null;

		$js = <<<EOT

		$(".fk_table_modal_save").click(function(){
			var modal_id = $(this).attr('id');
			var table_input = $("#input_"+$(this).attr('id'));

			var watch_field = $(this).attr("data-watch");

			data_send = {
					"value" : table_input.val(),
					"table"		: table_input.attr('data-table'),
					"field"	  : table_input.attr('data-field'),
					"watch"	  : watch_field,
					watch_value: $('[name="'+watch_field+'_id"] option:selected').val()
				};

			$.getJSON('{$path}', data_send, function(data) {
					// alert(JSON.stringify(data));
					$("#form_"+modal_id).modal('hide');

					$('[name="'+modal_id+'_id"] option').remove();
					$.each(data,function(index, value){
						$('[name="'+modal_id+'_id"]').append($("<option>").attr('data-text',value.{$field}).val(value.id).text(value.{$field}));
					});	
					
					$('[name="'+modal_id+'_id"] option[data-text="'+data_send.value+'"]').attr('selected',true);

			});
		});
		
		
EOT;
		Assets::add_js($js,"inline",true);
	}


	private function add_watch_method(){
		
		$watch_table = $this->field_config->watch;
		$path = site_url("admin/".$this->config->path."/ajax_execute/foreign_key");
		$filter = $this->field_config->filter;
		$field = $this->field_config->field;
		$table = $this->field_config->table;


		$js = <<<EOT

		$('[name="{$watch_table}_id"]').live("change",function(){

			data_send = {
					"value" : $('[name="{$watch_table}_id"] option:selected').val(),
					"filter"	:"{$filter}",
					"table"	: "{$table}",
					"filter_table"		: "{$watch_table}",
					"list_data"	  : true
				};

			$.getJSON('{$path}', data_send, function(data) {

				$('[name="{$table}_id"] option').remove();
				$.each(data,function(index, value){
					$('[name="{$table}_id"]').append($("<option>").attr('data-text',value.{$field}).val(value.id).text(value.{$field}));
				});	
			});
		});

EOT;

		Assets::add_js($js,"inline",true);
	}


	public function execute_ajax($post){

		if(isset($post['list_data'])){
			$query = "select a.* from {$post['table']} a join {$post['filter_table']} b on a.{$post['filter_table']}_id = b.{$post['filter']} where b.{$post['filter']} = '{$post['value']}' ";
			// return $this->ci->db->query($query)->result();		
			return $this->database->database_client->query($query)->result();
		}else{
			$watch_value="";
			$watch_field="";
			if(isset($post['watch'])){
				if(!empty($post['watch'])){
					$watch_value = ", '{$post['watch_value']}'";
					$watch_field =",{$post['watch']}_id";
				}
			}
			$query = "insert into {$post['table']} ({$post['field']} {$watch_field}) values ('{$post['value']}' {$watch_value})";
			// $this->ci->db->query($query);
			$this->database->database_client->query($query);

			$query = "select * from {$post['table']}";
			return $this->database->database_client->query($query)->result();
			// return $this->ci->db->query($query)->result();		
		}

	}

	private function get_data_field($data,$field){
		$field_value = "";
		if(!is_array($field)){
			$field_value = $data->$field;
		}else{
			foreach ($field as $f) {
				//isso vai ser uma merda de gambiarra.... JUDGE ME!
				$field_arr = explode("_", $f);
				if(in_array("id", $field_arr)){
					
					// $row_value = $this->ci->db->from($field_arr[0])->where("id",$data->$f)->get()->row();
					$row_value = $this->database->database_client->from($field_arr[0])->where("id",$data->$f)->get()->row();
					$keys = array_keys((array) $row_value);
					if(in_array("name", $keys)){
						$field_value .= $row_value->name. " - ";
					}else{
						$element = $keys[1];
						$field_value .= $row_value->$element." - ";
					}
				}
			}
		}
		return $field_value;
	}

}

?>