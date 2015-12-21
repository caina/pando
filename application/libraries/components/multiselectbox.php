<?php
/****
	cria uma lista

	elementos necessarios

	uma tabela de n pra n
	thats it!


*/
class Multiselectbox {
	
	 var $ci;
	 var $options;
	 var $user_model;
	 var $config;

	 public function __construct($config){
		$this->ci =&get_instance();
		$this->config = $config;
	}

	public function show($options = array(),$data){
		
		return "";
	}

	public function save_action($field=""){
		// dump($this->config);die;
		$table = $this->config["record_on"]->table;
		$field_key = $this->config["record_on"]->my_field;
		// dump($field);die;
		$this->ci->load->model("screen_model");
		$this->ci->screen_model->database_client->update($table , array($field_key=> $field), array("mngr_token"=>$this->config["form_data"]["mngr_token"]));
		return "";
	}

	public function ajax_save(){
		
		$form = $this->ci->input->post();
		$this->ci->load->model("screen_model");
		$this->ci->screen_model->database_client->delete($form["table"], array("mngr_token"=>$form["mngr_token"]));

		foreach ($form['multiselectbox'] as $select) {
			$arr = array($form["key_element_to"]=>$select,"mngr_token"=>$form["mngr_token"]);
			$this->ci->screen_model->database_client->insert($form["table"], $arr);
		}
		//dump($form);die;
	}

	public function list_act(){

	}

	public function generate(){
		// dump($this->config);
		$this->ci->load->model("screen_model");

		$select_field = $this->config["field_display"];
		$sql_table = $this->config["table"];
		$action = site_url("lib_generic/method/components/multiselectbox/ajax_save");

		$record_table =  $this->config["record_on"]->table;
		$my_key_element = $this->config["record_on"]->my_field;
		$foreign_element = $this->config["record_on"]->foreign_element;
		$pk_value = $this->config["primary_key_value"];
		$pk_value = !$pk_value?0:$pk_value;

		$mngr_token = $this->config["mngr_token"];
		$vinculo_texto = @$this->config["text_header"];

		$query = "
		SELECT
			id,
			{$select_field} as title,
			(
				SELECT

				IF(
					EXISTS(
						SELECT
							*
						FROM
							{$record_table}
						WHERE
							{$my_key_element} = {$pk_value}
						AND {$foreign_element} = {$sql_table}.id
					),
					1,
					0
				)
			)AS checked
		FROM
			{$sql_table}
		";
		$query_data = $database_result = $this->ci->screen_model->database_client->query($query);
		$itens_split_collumn = round($query_data->num_rows /4);
		$database_result = $database_result->result();
		// die($this->ci->screen_model->database_client->last_query());
		// dump($itens_split_collumn);die;
		

		$ckbox = "";
		$i=1;
		foreach ($database_result as $checkbox) {
			$checked = $checkbox->checked == 1?"checked='checked'":"";
			$title = $checkbox->title;
			$id = $checkbox->id;

			if($i%$itens_split_collumn==0 || $i==1){
				if($i!=1){
					$ckbox .= "</div>";
				}
				$ckbox .= "<div class='col-md-3'>";
			}
			
			$ckbox .= "
				<div class='checkbox-nice'>
					<input type='checkbox' name='multiselectbox[]' value='{$id}' id='ckbox_{$i}' {$checked}>
					<label for='ckbox_{$i}'>
						{$title}
					</label>
				</div>
			";

			$i++;
		}
		// die("aa".$i);
		$html="
		</div>
			<div class='col-md-12'
				<div class='form-group'>
					<label>{$vinculo_texto}</label>
					<div class='row'>
						{$ckbox}
				</div>
			</div>
			";





			$admin_html = "


		<div class='row'>

			<div class='col-lg-12'>

				<div class='main-box'>
					<header class='main-box-header clearfix'>
				        <h2> {$vinculo_texto} </h2>
				      </header>
					<div class='main-box-body clearfix'>
						<div class='row'>
							<div class='col-md-12'>
								<div class='row'>
									<div class='form-group col-md-12'>
										<form action='{$action}' class=' form_ajax not_clear'  method='post' accept-charset='utf-8'>
					                 		<input type='hidden' name='mngr_token' value='{$mngr_token}' />
					                 		<input type='hidden' name='key_element_to' value='{$foreign_element}' />
					                 		<input type='hidden' name='table' value='{$record_table}' />
					                 		

					                 		{$html}
					                 		
											<button type='submit' name='save'  value='Salvar' class='btn btn-success'>
											<span class='fa fa-plus'></span>
											Salvar</button>
												
					                 	</form>
										
									</div>
								</div>
							</div>
							
							
						</div>
					</div>
				</div>
			</div>
		</div>


		";
		return $admin_html;
	}

	public function set_options($options){
		$this->options = (object) $options;

	}

	private function get_options(){
		
	}

	


}

?>