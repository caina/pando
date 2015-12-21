<?php 
class Screen_model extends Dynamic_model{

	// var $table_name;

	public function __construct(){
		parent::__construct(); 
     }

     function get_module_data($company_id,$table){
     	return $this->db->from("module")->where("company_id",$company_id)->where("mysql_table",$table)->get()->row();
     }

     function find_by_token($table, $token){
          $this->table_name = $table;
          $database_result = $this->database_client->from($this->table_name)->where("mngr_token",$token)->get()->result(); 
          return $database_result;
     }

     function find_all_by_language($table,$language_id){
          if(!$this->get_db()->field_exists('mngr_languages_id', $table)){
               $this->get_db()->query("alter table ".$table." add COLUMN mngr_languages_id int(11) null REFERENCES mngr_languages(id)");
          }

          $this->table_name = $table;
          $database_result = $this->database_client->from($this->table_name)->where("mngr_languages_id",$language_id)->get()->result(); 
          return $database_result;
     }
     

     function find_all($table){
          // $this->database_client->start_cache();
     	$this->table_name = $table;
     	$database_result = $this->database_client->from($this->table_name)->get()->result(); 
          // $this->database_client->stop_cache();
          return $database_result;
     }

     function insert($data){
          $this->database_client->insert($this->table_name, $data);
          return $this->database_client->insert_id();
     }

     function delete($primary_key, $id){
          $this->database_client->where($primary_key, $id);
          $this->database_client->delete($this->table_name);
     }

     function find_by_language($id, $reference_name){
          if(!$this->get_db()->field_exists('mngr_languages_id', $this->table_name)){
               $this->get_db()->query("alter table ".$this->table_name." add COLUMN mngr_languages_id int(11) null REFERENCES mngr_languages(id)");
          }
          $db_data = $this->database_client->from($this->table_name)->where($this->primary_key,$id)->or_where($reference_name, $id)->get()->result();
          $db_result=array();
          foreach ($db_data as $key => $data) {
               $row_key = $this->database_client->get_where('mngr_languages', array("id"=>$data->mngr_languages_id))->row()->identification;
               $db_result[$row_key] = $data;
          }
          return $db_result;
     }

     function find($id){
          return $this->database_client->from($this->table_name)->where($this->primary_key,$id)->get()->row();
     }

     function update($id, $data){
          $this->database_client->where($this->primary_key,$id);
          $this->database_client->update($this->table_name, $data);   
          return $id;
     }


 }
 ?>