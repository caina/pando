<?php
/****
	CRIA FORMS USANDO OS DADOS DA TABELA NO BANCO
	E COMBINA COM OS DADOS DE CONFIGURAÇÃO DO MÓDULO


*/
class Single_upload {
	
	 var $ci;
	 var $options;
	 var $max_list_size = 50;
	 var $user_model;
	 public function __construct(){
		$this->ci =&get_instance();
		$this->ci->load->helper("form_helper");
		
	}

	public function show($options = array(),$data){
		$this->set_options((array)$data["options"]);
		$this->get_options();

		if(trim($data['value'])==""){
			return img("http://www.placehold.it/".$this->max_list_size."x".$this->max_list_size."/EFEFEF/AAAAAA");
		}

		// $image = "";
		// $value = explode(".",$data["value"]);
		// $ext = end($value);
		// $i=0;
		// foreach ($value as $val) {
		// 	if($i == (count($value)-1)){
		// 		$image .= "_thumb.".$val;
		// 	}else{
		// 		$image .= "{$val}";
		// 	}
		// 	$i++;
		// }

		$session_user = unserialize(get_logged_user());
		// dump($session_user);
		$options = array(
			"src"=> $session_user->upload_path.$data["value"],
			"width"=>$this->max_list_size."px",
			"height"=>$this->max_list_size."px"
		);
		return img($options);
	}

	public function save_action($field="",$options = array()){
		$this->set_options($options);
		$this->get_options();
		// dump($field);
		// dump($this->options);
		$upload_folder = $this->options->image_path;
		
		// if(!is_dir($upload_folder)){
		// 	mkdir($upload_folder, 0777);
		// 	chmod ($upload_folder, 0777);
		// }
		$file_name = $this->options->name;
		// dump($field);
			// dump($this->options);
		list($w,$h) = explode("x",$this->options->size);

		$config = array();
		$config['upload_path'] = $upload_folder;
		$config['allowed_types'] = $this->options->allowed_types;
		// $config['image_width']  = $w;
		// $config['image_height']  = $h;
		$config['encrypt_name']  = true;
		$config['overwrite']  = false;

		$files_uploades = array();
		$file_keys = array_keys($_FILES);
		
		// ENVIAR POR CURL PARA O SERVIDOR DE VERDADE DELE
		return send_image_to_client($file_name);
			
	}

	public function list_act(){

	}

	public function generate($data,$value = ""){
		$this->get_options();
		$data["options"] = $this->options;
		return form_single_upload($data);
	}

	public function set_options($options){
		$this->options = (object) $options;

	}

	private function get_options(){
		if(!isset($this->options->size)){
			$this->options->size = "200x200";
		}
		if(!isset($this->options->image_path)){
			$this->options->image_path = "./assets/upload/";
		}
		if(!isset($this->options->allowed_types)){
			$this->options->allowed_types = "gif|jpg|png|jpeg";
		}
		//TODO CRIAR PASTA SE NAO EXISTIR
	}

	private function upload_act($file_key, $config){
		$this->ci->load->library('upload', $config);
		
		if ( ! $this->ci->upload->do_upload($file_key)){
			
			return array("error"=>true, "response"=>$this->ci->upload->display_errors());
		}
		else {

			// $thumb_path = $this->ci->upload->upload_path."resized";
			// if(!is_dir($thumb_path)){
			// 	mkdir($thumb_path, 0777);
			// 	chmod($thumb_path, 0777);
			// }
			

			$config2['image_library'] = 'gd2';
	        $config2['source_image'] = $this->ci->upload->upload_path.$this->ci->upload->file_name;
	        $config2['new_image'] = $this->ci->upload->upload_path.$this->ci->upload->file_name;
	        $config2['maintain_ratio'] = TRUE;
	        $config2['create_thumb'] = TRUE;
	        $config2['thumb_marker'] = '_thumb';
	        $config2['width'] = $config['image_width'];
	        $config2['height'] = $config['image_height'];

	        $this->ci->load->library('image_lib',$config2); 
            if(!$this->ci->image_lib->resize()){
            	dump( $this->image_lib->display_errors('', ''));die("falha");
            }

            return array("error"=>false, "response"=>$this->ci->upload->data());
			// return $this->ci->upload->data();
		}
	}


}

?>