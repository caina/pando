<?php
class Checkbox{
	var $ci;
	 var $options;
	 var $user_model;
	 var $config;
	 var $my_table;

	 public function __construct($config){
		$this->ci =&get_instance();
		$this->config = $config;
		$this->ci->load->model("screen_model");
		$this->my_table = @$this->config["to_table"]."_".@$this->config["table"]; 
	}

	public function show($options = array(),$data){
		
		return "";
	}

	public function save_action($field=""){

		return false;
	}

	function ajax_execution($field_id){
		$form_data = current($this->config['post_data']);
		
		$arr_data = array($form_data["field"]=>$form_data["val"],"mngr_token"=>$form_data["mngr_token"]);
		if($form_data['is_checked']=='true'){
			$this->ci->screen_model->get_db()->insert($form_data["table"], $arr_data);
		}else{
			$this->ci->screen_model->get_db()->delete($form_data["table"], $arr_data);
		}
	}

	public function list_act(){

	}

	public function generate(){
		// $this->ci->add_asset("assets/cube/css/libs/dropzone_base.css","css");
		// $this->ci->add_asset(("assets/cube/js/dropzone.min.js"));
		$this->ci->add_asset("assets/js/modules/checkbox.js");
		$this->create_table();
		$this->load_data();
		$url = site_url("screen/component_execute_ajax/".$this->config["to_table"]."/checkbox");
		$action_url = site_url("screen/galery_image_upload");

		$itens_split_collumn = round(count($this->data) /4);
		$ckbox = "";
		$i=1;
		foreach ($this->data as $checkbox) {
			$checked = $checkbox->checked >= 1?"checked='checked'":"";
			$title = $checkbox->my_key;
			$id = $checkbox->id;

			if($i%$itens_split_collumn==0 || $i==1){
				if($i!=1){
					$ckbox .= "</div>";
				}
				$ckbox .= "<div class='col-md-3'>";
			}
			
			$ckbox .= "
				<div class='checkbox-nice'>
					<input type='checkbox' name='multiselectbox[]' value='{$id}' id='ckbox_{$i}' {$checked} />
					<label for='ckbox_{$i}'>
						{$title}
					</label>
				</div>
			";

			$i++;
		}

		$m_table = $this->my_table;
		$table_field = $this->config["table"].'_id';
		$title = $this->config['title'];
		$result = <<<EOT
		</div>
		</div>
				<div class="main-box">
					<header class="main-box-header">
						<h2>{$title} <span class='status_ajax'></span></h2>
						
					</header>
					<div class="main-box-body clearfix">
						<form  class="component_checkbox" data-table="{$m_table}" data-field="{$table_field}" action="{$url}">
							{$ckbox}
						</form>
						<br/>
					</div>
				



EOT;

return $result;
	}

	function load_data(){
		$m_table = $this->my_table;
		$table_field = $this->config["table"].'_id';
		$this->data = $this->ci->screen_model->get_db()->
			select(
				$this->config["table"].".id"." as id,".
				$this->config["table"].".".$this->config["field_display"]." as my_key,
				(select count(*) from $m_table where mngr_token = '".$this->config["mngr_token"]."' and {$table_field} = id) as checked
				",FALSE)->from($this->config["table"])->get()->result();
		return $this;
	}

	public function set_options($options){
		$this->options = (object) $options;
	}

	private function get_options(){
		
	}

	function check_table(){
		return $this->ci->screen_model->get_db()->table_exists($this->my_table);
	}



	function create_table(){
		if(!$this->check_table()){
			$table_name = $this->my_table;
			$table_field = $this->config["table"].'_id';
			$query = "create table {$table_name}(mngr_token varchar(255) not null,{$table_field} int(255)null);";
			$this->ci->screen_model->get_db()->query($query);
		}
	}

}

?>
