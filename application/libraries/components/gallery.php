<?php
class Gallery{
	var $ci;
	 var $options;
	 var $user_model;
	 var $config;

	 public function __construct($config){
		$this->ci =&get_instance();
		$this->config = $config;
		$this->ci->load->model("screen_model");
	}

	public function show($options = array(),$data){
		
		return "";
	}

	public function save_action($field=""){

		// dump($this->config);
		// dump($this->ci->input->post("component_gallery"));
		if(!$this->check_table()){
			$this->create_table();
		}
		$this->ci->screen_model->get_db()->delete($this->config["to_table"].'_gallery', array($this->config["to_table"].'_id'=>$field));
		
		$images = $this->ci->input->post("component_gallery");
		foreach ($images as $image) {
			$arr_insert = array();
			$arr_insert["image_name"] = $image;
			$arr_insert[$this->config["to_table"].'_id'] = $field;
			$this->ci->screen_model->get_db()->insert($this->config["to_table"].'_gallery', $arr_insert);
		}


		return "";
	}

	function ajax_execution($field_id){
		return $this->ci->screen_model->get_db()->get_where($this->config["to_table"].'_gallery',array($this->config["to_table"].'_id'=>$field_id))->result();
	}

	public function list_act(){

	}

	public function generate(){
		$this->ci->add_asset("assets/cube/css/libs/dropzone_base.css","css");
		$this->ci->add_asset(("assets/cube/js/dropzone.min.js"));
		$this->ci->add_asset("assets/js/modules/gallery.js");

		$url = site_url("screen/component_execute_ajax/".$this->config["to_table"]."/gallery");
		$session_user = unserialize(get_logged_user());
		$image_path = $session_user->upload_path;
		$action_url = site_url("screen/galery_image_upload");
		$result = <<<EOT
		</div>
		</div>
			<input type="hidden" id="ajax_url" value="{$url}" />
			<input type="hidden" id="image_path" value="{$image_path}" />
				<div class="main-box">
					<header class="main-box-header">
						<h2>GALERIA DE IMAGENS</h2>
						<p>
						<i class="fa fa-info-circle"></i> Clique na caixa cinza, ou arraste imagens para cadastrar imagens para a galeria
						</p>
					</header>
					<div class="main-box-body">
						<div id="dropzone">
							<form id="demo_upload" class="dropzone2 dz-clickable" action="{$action_url}">
								<div class="dz-default dz-message">
									<span>Arraste imagens aqui</span>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="main-box">
					<header class="main-box-header">
						<h2><i class="fa fa-camera"></i>  Imagens cadastradas, clique nelas pare deletar</h2>
					</header>
					
					<div class="main-box-body">
						<div id="gallery-photos-wrapper">
							<ul id="gallery-photos" class="clearfix gallery-photos gallery-photos-hover">
								
							</ul>
						</div>



EOT;

return $result;
	}

	public function set_options($options){
		$this->options = (object) $options;
	}

	private function get_options(){
		
	}

	function check_table(){
		return $this->ci->screen_model->get_db()->table_exists($this->config["to_table"].'_gallery');
	}

	function create_table(){
		$table_name = $this->config["to_table"].'_gallery';
		$table_field = $this->config["to_table"].'_id';
		$query = "create table {$table_name}(image_name varchar(255) null,{$table_field} int(255)null);";
		$this->ci->screen_model->get_db()->query($query);
	}


}

?>
