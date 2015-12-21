<?php
/****

will generate a simple form multiupload of images

@table = table that will store images of the galery
@to_table = table that contain the form where the galery is

@foreign_key = normaly the primary key of the table param
@image_field = field where the image will be stored
@module = table model placeholder

*/

Class Combo_options {
	
	var $ci;
	var $config;
	var $row_space = "6";

	public function __construct($config){
		$this->ci =&get_instance();
		if(isset($this->config->table)){
			$this->config = $config;
			$this->check_tables();
		}
	}

	public function generate(){
		$options = $this->get_options();
		$html = "";
		
		$html .= "<div class='control-group '><label class='control-label' >{$this->config->label}</label> <div class='controls'><div class='row-fluid'>";
		$i=0;
		foreach ($options as $option) {
			if(($i%(12/(int)$this->row_space)==0) && $i>0){
				$html .="</div><div class='row-fluid'>";
			}
			$html .= $this->create_option($option);

			$i++;
		}
		$html .="</div></div>";

		// dump($this->config);die;
		if(isset($this->config->btn_adictional)){

			$link = site_url($this->config->btn_adictional->link);
			$html .= "<a href='{$link}' role='button' class='btn btn-success'> {$this->config->btn_adictional->label} <i class='{$this->config->btn_adictional->icon}'>&nbsp;</i></a>";

		}
		$html .= "</div>";

		return $html;
	}

	private function create_option($option){
		$html = "
			<div class='span{$this->row_space}'>
				<div class='control'>
					<label class='checkbox'>
			      		<input type='checkbox' name='combo_options[{$this->config->table}][{$option->id}]' ".(!empty($option->selected)?"checked":"")." value='{$option->selected}'> {$option->option_name}
			      	</label>
		      	</div>
	      	</div>";

		return $html;
	}


	//chamado do dynamic controller
	public function insert($id, $value){
		if(!empty($value[$this->config->table])){
			$value = $value[$this->config->table];
			$this->clean_table_data($id);
			$this->insert_data($id,$value);
		}
	}


	/**
	

	FUNCOES DO BANCO

	*/
	
	/***
	SELECT
	a.*,
	b.id
FROM
	vehicles_options a
LEFT  JOIN vehicles_vehicles_options b on a.id=b.vehicles_options_id and b.vehicles_id = 11

	*/
	private function get_options(){
		$fk_id = ($this->ci->uri->segment(5)?$this->ci->uri->segment(5):0);
		$fk_name = $this->config->to_table."_id";

		$this->ci->db->select("a.*,b.id as selected");
		$this->ci->db->from($this->config->table." a");
		$this->ci->db->join($this->get_fk_table_name()." b","a.id=b.{$this->config->table}_id and b.{$fk_name} = {$fk_id}","left");
		$this->ci->db->order_by("a.option_name asc");
		// dump($this->ci->db->get()->result());die;
		return $this->ci->db->get()->result();
	}

	private function get_fk_table_name(){
		return $this->config->to_table."_".$this->config->table;
	}

	private function insert_data($id, $insert_data){
		$fk_name = $this->config->to_table."_id";
		foreach ($insert_data as $key => $value) {
			$value_arr = array($fk_name => $id, "{$this->config->table}_id"=> $key);
			$this->ci->db->insert($this->get_fk_table_name(),$value_arr);
		}
	}

	private function clean_table_data($fk_id){
		// dump($this->config);die;
		$fk_name = $this->config->to_table."_id";
		$this->ci->db->where($fk_name,$fk_id);
		$this->ci->db->delete($this->get_fk_table_name());
	}

	//valida se as tabelas estao certas, se nao cria
	private function check_tables(){
		$table = $this->ci->db->query("SHOW TABLES LIKE '%{$this->config->table}%' "); 
		if($table->num_rows == 0){
			$this->create_opt_table();
		}

		$table = $this->ci->db->query("SHOW TABLES LIKE '%{$this->config->to_table}_{$this->config->table}%' "); 
		if($table->num_rows == 0){
			$this->create_fk_table();
		}		
	}


	private function create_opt_table(){
		$model = $this->ci->load->model($this->config->module, null, true);
		$fields = array(
	        'id' => array(
	             'type' => 'INT',
	             'constraint' => 11,
	             'unsigned' => TRUE,
	             'auto_increment' => TRUE
	          ),
	        "option_name" => array(
	             'type' => 'VARCHAR',
	             'constraint' => 250,
	          )
	    );

		$model->dbforge->add_field($fields);
		$model->dbforge->add_key('id', TRUE);
		$model->dbforge->create_table($this->config->table); 
	}	

	private function create_fk_table(){
		$model = $this->ci->load->model($this->config->module, null, true);
		$fk_name = $this->config->to_table."_id";

		$fields = array(
	        'id' => array(
	             'type' => 'INT',
	             'constraint' => 255,
	             'unsigned' => TRUE,
	             'auto_increment' => TRUE
	          ),
	        "{$fk_name}" => array(
	             'type' => 'INT',
	             'constraint' => 255,
	          ),
	        "{$this->config->table}_id" => array(
	             'type' => 'INT',
	             'constraint' => 255,
	          )
	    );

		$model->dbforge->add_field($fields);
		$model->dbforge->add_key('id', TRUE);
		$model->dbforge->create_table($this->config->to_table."_".$this->config->table); 
	}

	/**


	*/

}