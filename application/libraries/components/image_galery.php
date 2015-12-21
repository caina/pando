<?php
/****

will generate a simple form multiupload of images

@table = table that will store images of the galery
@to_table = table that contain the form where the galery is

@foreign_key = normaly the primary key of the table param
@image_field = field where the image will be stored
@module = table model placeholder

*/

Class Image_galery {
	
	var $ci;
	var $config;
	var $assets_path = "assets/plugins/uploadify/";

	public function __construct($config){
		if(isset($this->config->to_table)){

			if(!isset($config->file_types)){
				$config->file_types = "*.gif; *.jpg; *.png; *.jpeg";
			}
			$this->ci =&get_instance();
			$this->config = $config;
			if(!isset($this->config->table)){
				$this->config->table = $this->config->to_table."_".$this->config->image_field;
			}
			$this->create_table();
		}
	}

	public function generate(){
		$this->add_assets();

		$html_output = "";
		$html_output .= $this->create_upload_buttons();

		return $html_output;
	}

	public function insert($parent_id,$values){
		$fk_name = $this->config->to_table."_".$this->config->foreign_key;
		foreach ($values as $key=> $value) {
			$field = array($fk_name => $parent_id);
			
			$this->ci->db->where("id",$value);
			$this->ci->db->update($this->config->table,$field);
		}
	}

	public function upload_action(){
		// dump($_FILES);die;

		if(!isset($this->config->module)){
			die("Nao foi coinfigurado o module, nao sei onde salvar. Operacao abortada");
		}

		// $upload_folder = $this->options->image_path;
		$upload_folder = "./assets/upload/image_galery";
		
		if(!is_dir($upload_folder)){
			mkdir($upload_folder, 0777);
			chmod ($upload_folder, 0777);
		}
		list($w,$h) = explode("x",$this->config->size);

		$config = array();
		$config['upload_path'] = $upload_folder;
		$config['allowed_types'] = str_replace(array(";"),"|",str_replace(array("*","."," "),"",$this->config->file_types));
		$config['image_width']  = $w;
		$config['image_height']  = $h;
		$config['encrypt_name']  = true;
		$config['overwrite']  = false;

		$files_uploades = array();
		$file_keys = array_keys($_FILES);
		for($i=0;$i<count($file_keys);$i++){
			$files_uploades = $this->upload_act($file_keys[$i],$config);
		}

		if(!empty($files_uploades)){
			if($files_uploades["error"]){
				Template::set_message($files_uploades["response"]);
			}else{
					
				return $this->save_image($files_uploades["response"]);
			}
		}
		// dump($this->config);
		// dump($files_uploades);die;
	}

	private function create_table(){
		if($this->config->table){
			$model = $this->ci->load->model($this->config->module, null, true);
			$query = $model->db->query("SHOW TABLES LIKE '%{$this->config->table}%' "); 
			
			$fk_name = $this->config->to_table."_".$this->config->foreign_key;
			if($query->num_rows == 0){
				
				$fields = array(
	                'id' => array(
	                     'type' => 'INT',
	                     'constraint' => 255,
	                     'unsigned' => TRUE,
	                     'auto_increment' => TRUE
	                  ),
	                "{$fk_name}" => array(
	                     'type' => 'INT',
	                     'constraint' => 100,
	                  ),
	                $this->config->image_field => array(
	                     'type' =>'VARCHAR',
	                     'constraint' => '100'
	                  )
	            );

				$model->dbforge->add_field($fields);
				$model->dbforge->add_key('id', TRUE);
				$model->dbforge->create_table($this->config->table); 
			}

		}	
	}

	private function save_image($file){
		$fk_name = $this->config->to_table."_".$this->config->foreign_key;
		// dump($this->config);die;
		$inserted = 0;
		if(isset($file['file_name'])){
			$insert_data = array("{$fk_name}"=>0,$this->config->image_field => $file['file_name']);
			$this->ci->db->insert($this->config->table, $insert_data);
			$inserted = $this->ci->db->insert_id();
		}

		$return_obj = new stdClass();
		$return_obj->thumb = base_url("assets/upload/image_galery/".$this->get_thumb($file['file_name']));
		$return_obj->image = base_url("assets/upload/image_galery/".$file['file_name']);
		$return_obj->image_name = $file['file_name'];
		$return_obj->remove_url = $delete_path = site_url($this->config->path."/image_galery_upload/{$inserted}");
		$return_obj->id = $inserted;
		return $return_obj;
	}

	private function get_thumb($file_name){
		$image = "";
		$value = explode(".",$file_name);
		$ext = end($value);
		$i=0;
		foreach ($value as $val) {
			if($i == (count($value)-1)){
				$image .= "_thumb.".$val;
			}else{
				$image .= "{$val}";
			}
			$i++;
		}
		return $image;
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
	        $config2['new_image'] = "./assets/upload/image_galery";
	        $config2['maintain_ratio'] = FALSE;
	        $config2['create_thumb'] = TRUE;
	        $config2['thumb_marker'] = '_thumb';
	        $config2['width'] = 75;
	        $config2['height'] = 75;

	        $this->ci->load->library('image_lib',$config2); 
            if(!$this->ci->image_lib->resize()){
            	dump( $this->image_lib->display_errors('', ''));die("falha");
            }

            return array("error"=>false, "response"=>$this->ci->upload->data());
			// return $this->ci->upload->data();
		}
	}

	private function add_assets(){

		$swf_path = base_url("{$this->assets_path}uploadify.swf");
		$upload_path = site_url($this->config->path."/image_galery_upload");

		// $upload_path =  base_url("{$this->assets_path}uploadify.php");
		Assets::add_css(base_url("{$this->assets_path}uploadify.css"));
		Assets::add_js(base_url("{$this->assets_path}jquery.uploadify.min.js"));


		//todo tirar de alert e colocar num bonito
		$js = <<<EOT
			$('#file_upload').uploadify({
	            'swf'      : '{$swf_path}',
	            'uploader' : '{$upload_path}',
	            'buttonText' : 'Selecione os arquivos',
		    	'removeCompleted' : true,
				'multi'    	  : true,
		        height        : 40,
		        width         :  150,
		        'fileTypeExts' : '{$this->config->file_types}',
		        'onUploadSuccess' : function(file, data, response) {
		        	data = JSON.parse(data);
		        	$("form").append($("<input>").attr("type","hidden").attr("name","image_galery[]").val(data.id));
		        	$("#image-galery-list-divs").append('<div class="span2 deletable"> <a href="'+data.remove_url+'" class="remove_image_galery" ><i class="icon-remove">&nbsp;</i></a> <a href="'+data.image+'" target="_blank" class="thumbnail"> <img src="'+data.thumb+'" /> </a> <br/> </div> '); },
		        'onUploadError' : function(file, errorCode, errorMsg, errorString) {
		            alert('Erro ao fazer upload ' + file.name + errorMsg +' - ' + errorString);
		        }
	        });

			$(".remove_image_galery").live("click",function(e){
				e.preventDefault();

				var div_located = $(this).parents(".deletable");
				$.getJSON($(this).attr("href"), function() {
					div_located.remove();
				  
				});
			});
EOT;
	
	Assets::add_js($js,"inline",true);

}

	public function delete_action($id){
		$this->ci->db->delete($this->config->table, array("id"=>$id));
		return array("ok");
	}

	private function create_upload_buttons(){

		$data = array();
		//translate
		$id = $this->ci->uri->segment(5);
		if($id !== false){
			$fk_name = $this->config->to_table."_".$this->config->foreign_key;
			$this->ci->db->where($fk_name,$id);
			$this->ci->db->from($this->config->table);
			$data = $this->ci->db->get()->result();
		}

		$imgs = "";
		foreach ($data as $value) { 
			$img_field = $this->config->image_field;
			$img   = $value->$img_field;
			$thumb = base_url("assets/upload/image_galery/".$this->get_thumb($img));
			$delete_path = site_url($this->config->path."/image_galery_upload/".$value->id);

			$imgs .= '
				<div class="span2 deletable">
			    <a href="'.$delete_path.'"  class="remove_image_galery" /><i class="icon-remove">&nbsp;</i></a>
				<a href="'.base_url("assets/upload/image_galery/".$img).'" target="_blank" class="thumbnail">
				  	<img src="'.$thumb.'" />
			    </a>
				<br/>
			  </div>
			';
		}


		$output = <<<EOT
		<div class="row-fluid">
			<div class="span5">
				<div class="hero-unit">
				  <h1>Galeria</h1>
				  <p><small> Clique no bot&atilde;o de upload e selecione arquivos do tipo: {$this->config->file_types} </small></p>
				  <p>
					<input type="file" name="file_upload" id="file_upload" />
				  </p>
				</div>
			</div>
			<div class="span7">
					<div class="hero-unit">
					<div class="row-fluid" id="image-galery-list-divs">
					  {$imgs}
					</div>

					</div>
			</div>
		</div>

EOT;

		return $output;
	}

	private function get_images(){

	}



}
?>