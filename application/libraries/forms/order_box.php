<?php
/****
	CRIA FORMS USANDO OS DADOS DA TABELA NO BANCO
	E COMBINA COM OS DADOS DE CONFIGURAÇÃO DO MÓDULO


*/
class Order_box {
	
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

		$name = $data['field_name'];


		$path =  site_url("admin/".$data['options']->path."/ajax_execute/order_box");
		$js = <<<EOT
			$(".order_box").change(function(){
				data_send = {
					"field_name" : $(this).attr("order_box_field"),
					"pk"		: $(this).attr("order_box_pk"),
					"pk_val"	  : $(this).attr("order_box_id"),
					"value"	  : $(this).val()
				};

				$.getJSON('{$path}', data_send, function(data) {
					 // alert(JSON.stringify(data));
				});

			});
					
EOT;
	
	Assets::add_js($js,"inline",true);
		// dump($data);die;
		return "<input class='order_box' name='' value='{$data['value']}' order_box_id='{$data['pk']}' min='0' max='99' order_box_pk='{$data['pk_name']}' order_box_field='{$data['field_name']}' type='number' style='max-width: 30px;' />";
	}

	function execute_ajax($input){
		$update = array($input['field_name'] => $input['value']); 
		$this->ci->db->where($input['pk'],$input['pk_val']);
		$this->ci->db->update($this->options->table,$update);
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