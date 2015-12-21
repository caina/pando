<?php
/****
	CRIA FORMS USANDO OS DADOS DA TABELA NO BANCO
	E COMBINA COM OS DADOS DE CONFIGURAÇÃO DO MÓDULO


*/
class Active_box {
	
	 var $ci;
	 var $options;

	 public function __construct($options = false){
		$this->ci =&get_instance();
		$this->ci->load->helper("form_helper");
		if($options!==false){
			$this->set_options($options);
		}
	}

	

	public function show($options = array(),$data){
		// dump($data);die;
		$mysql_table =  end($this->ci->uri->segment_array());
		$path =  site_url("lib_generic/method/forms/active_box/save_action"); 
		// die($path);
		//todo tirar de alert e colocar num bonito
		$js = <<<EOT
			$(".active_box_plugin").click(function(){
				data_send = {
					"field_name" : $(this).attr("active_box_field"),
					"pk"		: $(this).attr("active_box_pk"),
					"pk_val"	  : $(this).attr("active_box_id"),
					"checked"	  : $(this).attr("checked")
				};

				$.getJSON('{$path}', data_send, function(data) {
					// alert(JSON.stringify(data));
				});

			});
					
EOT;
	
	// Assets::add_js($js,"inline",true);

		$data_send = array(
	    'value'       => $data['value'],
	    'checked'     => ($data['value'] == "1"),
	    "active_box_id"	  => $data['pk'],
	    "active_box_field"	  => $data['field_name'],
	    "active_box_pk"	  => $data['pk_name'],
	    "data_path"			=> $path,
	    "data_table"	=> $mysql_table,
	    "class"		  => "active_box_plugin"
	    );
		// dump($data);die;
		return form_checkbox($data_send);
	}

	function save_action(){
		$this->ci->load->model("screen_model");

		$is_checked = @$this->ci->input->get("checked");
		$is_checked = $is_checked=="false"?0:1;
		$input = array(
			"field_name" => $this->ci->input->get("field_name"),
			"pk"		 => $this->ci->input->get("pk"),
			"pk_val"	 => $this->ci->input->get("pk_val"),
			"is_checked" => (!empty($is_checked)),
			"table"		=> 	$this->ci->input->get("data_table")
			);

		$field_name = $input["field_name"];
		
		$update = array($input['field_name'] => $input['is_checked']);
		// dump($input);die;
		$this->ci->screen_model->database_client->where($input['pk'],$input['pk_val']);
		$this->ci->screen_model->database_client->update($input['table'],$update);
		// die($this->ci->screen_model->database_client->last_query());
	}



	public function list_act(){

	}

	public function generate($data,$value = ""){
		// $this->get_options();
		// $data["options"] = $this->options;
		// return form_checkbox_admin($data);
	}

	public function set_options($options){
		$this->options = $options;
	}

	private function get_options(){
		//TODO CRIAR PASTA SE NAO EXISTIR
	}

	

}

?>