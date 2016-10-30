<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template_model extends Dynamic_model {

	public $Template;
	public $Version;

	public function __construct()
	{
		parent::__construct();
		$this->Template = new Template_class();
		$this->Version = new Version();
		$this->verify_tables();

	}

	public function get_template($template_id)
	{
		$this->Template->inflate($this->get_db()->get_where($this->Template->table_name,array("id"=>$template_id)));
		$this->Version->inflate($this->get_db()->get_where($this->Version->table_name, array("id"=>$this->Template->version_id)));
		$this->Version->populate($this->get_db()->get($this->Version->table_name));
		$this->Template->set_version($this->Version);
		$this->Template->populate($this->get_db()->get($this->Template->table_name));

		return $this;
	}

	public function update_template_data($post_data)
	{
		$this->Template->set_new_data($post_data);
		$this->get_db()->update($this->Template->table_name, $this->Template->table_data(),array("id"=>$this->Template->id));
	}

	private function verify_tables()
	{
		if(!$this->get_db()->table_exists($this->Template->table_name)){
			$this->get_db()->query($this->Template->table_query());
		}

		if(!$this->get_db()->table_exists($this->Version->table_name)){
			$this->get_db()->query($this->Version->table_query());
		}
		// gabarito :)
	}


	public function get_first_theme()
	{
		$this->Template->inflate($this->get_db()->get($this->Template->table_name));
		$this->Version->inflate($this->get_db()->get($this->Version->table_name));
		$this->basic_configuration();
		
		// na primeira vez que ele inseri, não fica nenhum dado, então precisamos inflar novamente
		if($this->Template->num_rows == 0)
			$this->Template->inflate($this->get_db()->get($this->Template->table_name));

		$this->Template->set_version($this->Version);
		return $this;
	}

	/**
	 * Configuracao pra primeira vez que sera utilizado,
	 * basicamente, inseri um novo item
	 */
	private function basic_configuration()
	{
		if($this->Version->num_rows == 0){
			$this->get_db()->insert($this->Version->table_name,$this->Version->create_new());
			$this->Version->inflate($this->get_db()->get($this->Version->table_name));
		}
		if($this->Template->num_rows == 0){
			$this->create_new_template();
		}
	}

	public function create_new_template()
	{
		if(!isset($this->Version->id)){
			$this->Version->inflate($this->get_db()->get($this->Version->table_name));
		}
		$this->Template->set_version($this->Version);
		$this->get_db()->insert($this->Template->table_name,$this->Template->create_new());
		$this->Template->id = $this->get_db()->insert_id();
		return $this;
	}

	public function create_version($post_data)
	{
		$this->get_db()->insert($this->Version->table_name, $this->Version->create_version($post_data));
	}

	public function update_version()
	{
		$this->get_db()->update($this->Version->table_name, $this->Version->table_data(), array("id"=>$this->Version->id));
	}

}

/**
* Versões
*/
class Version
{	
	
	var $id;
	var $name;
	var $num_rows;
	var $create_at;
	var $published;
	var $publish_date;
	var $Versions = array();
	var $table_name = "template_version";

	public function table_query()
	{
		return "CREATE TABLE if not exists template_version (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  name varchar(255) NOT NULL,
		  publish_date timestamp NULL,
		  published int(1) NOT NULL DEFAULT 0, 
		  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  PRIMARY KEY (id)
		)";
	}

	public function table_data()
	{
		$table = new stdClass();
		$table->name = $this->name;
		$table->publish_date = $this->publish_date;
		$table->published = $this->published;
		return $table;
	}

	public function create_version($post_data)
	{

		if(isset($post_data["publish_date"]) || !empty($post_data["publish_date"])){
			$this->publish_date = strtotime($post_data["publish_date"]);
		}else{
			$this->publish_date = strtotime(date("d/m/Y"));
		}
		if(isset($post_data["name"])){
			$this->name = $post_data["name"];
		}
		if(isset($post_data["published"])){
			$this->published = $post_data["published"];
		}else{
			$this->published = 0;
		}

		$obj 				= new stdClass();
		$obj->name 			= $this->name;
		$obj->publish_date 	= $this->publish_date;
		$obj->published 	= $this->published;
		return $obj;
	}

	public function inflate($db_result)
	{

		$this->num_rows = $db_result->num_rows();
		if($this->num_rows > 0){
			$this->inflate_row($db_result->row());
		}
		$this->populate($db_result);
	}

	public function inflate_row($db_row)
	{
		foreach ($db_row as $key => $value) {
			$this->$key = $value;
		}
	}

	public function populate($db_result)
	{
		$this->Versions = array();
		$result = $db_result->result();
		foreach ($result as $result) {
			$Version = new Version();
			$Version->inflate_row($result);
			$this->Versions[] = $Version;
		}
	}


	function create_new(){
		$obj = new stdClass();
		$obj->name = "Padrão";
		$obj->published = 1;
		$obj->publish_date = strtotime(date("d/m/Y"));
		return $obj;
	}

}
/**
* Template
*/
class Template_class{
	
	var $id;
	var $identification;
	var $version;
	var $table_name = "template";
	var $num_rows;
	var $version_id;
	var $Template_array;
	var $Version;
	var $css_template_id;
	var $Templates = array();

	function __construct()
	{
	}

	public function set_new_data($arr_data){
		foreach ($arr_data as $key => $data) {
			$this->$key = $data;
		}
	}
	public function table_data(){
		// retornar os dados da tabela!
		$obj = new stdClass();
		$obj->identification = $this->identification;
		$obj->domain = $this->domain;
		$obj->version_id = $this->version_id;
		$obj->css_template_id = $this->css_template_id;
		return $obj;
	}

	public function set_version($version)
	{
		$this->Version = $version;
	}
	public function inflate($db_result)
	{
		$this->num_rows = $db_result->num_rows();
		if($this->num_rows > 0){
			$this->inflate_row($db_result->row());
		}
		$this->populate($db_result);
	}

	public function populate($db_result)
	{	
		$this->Templates = array();
		$result = $db_result->result();
		foreach ($result as $result) {
			$Template = new Template_class();
			$Template->inflate_row($result);
			$this->Templates[] = $Template;
		}
		// array_unique($this->Templates);
	}

	private function inflate_row($db_row)
	{

		$this->id 				= $db_row->id; 
		$this->identification 	= $db_row->identification; 
		$this->domain 			= $db_row->domain; 
		$this->version_id 		= $db_row->version_id; 
		$this->css_template_id 	= $db_row->css_template_id; 
	}

	public function create_new()
	{
		$insert_blank = new stdClass();
		$insert_blank->identification = uniqid();
		$insert_blank->version_id = $this->Version->id;
		$insert_blank->domain = "sample";

		return $insert_blank;
	}

	public function table_query()
	{
		return "CREATE TABLE if not exists template (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  identification varchar(255) NOT NULL,
		  domain varchar(255) NOT NULL,
		  version_id int(11) DEFAULT NULL,
		  css_template_id int(11) DEFAULT NULL,
		  PRIMARY KEY (id)
		)";
	}

}

/**
* Gabarito
*/
class Gabarito
{
	
	function __construct()
	{
		# code...
	}
}


/* End of file template_model.php */
/* Location: ./application/models/template_model.php */