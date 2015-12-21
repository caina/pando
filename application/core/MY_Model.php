<?php 
class MY_Model extends CI_Model{
	public function __construct(){
    	parent::__construct(); 
    }
}

class Authenticated_model extends CI_Model{
	
	var $table_name;

	public function __construct(){
    	parent::__construct(); 
    }

    function listAll(){
    	return $this->db->from($this->table_name)->get()->result();
    }

    function get($id){
        return $this->db->from($this->table_name)->where("id",$id)->get()->row();
    }



    function update($id, $data){
        $this->db->where("id",$id);
        $this->db->update($this->table_name, $data);
        return $id;
    }

    function create($obj){
        $this->db->insert($this->table_name, $obj); 
    }

}


class Dynamic_model extends CI_Model{
    
    var $table_name;
    var $database_client;
    var $primary_key;


     function check_table($module, $table){
        if(!$this->get_db()->table_exists($table)){
            // colocar o create table 
        }
     }

    public function __construct(){
        parent::__construct(); 
        // $ci =&get_instance();
        $this->configure($this->data["logged_user"]->company_id);
    }

    function get_company_id(){
        return $this->data["logged_user"]->company_id;
    }

    function listAll(){
        return $this->db->from($this->table_name)->get()->result();
    }

    function get($id){
        return $this->db->from($this->table_name)->where("id",$id)->get()->row();
    }

    function get_db(){
        // return $this->db;
        if(empty($this->database_client)){
            // dump($this->data["logged_user"]);die;
            $this->configure($this->data["logged_user"]->company_id);
        }
        return $this->database_client;
    }

    function get_field_data($table){
        return $this->get_db()->field_data($table);
    }


    function update($id, $data){
        $this->db->where("id",$id);
        $this->db->update($this->table_name, $data);
        return $id;
    }

    function create($obj){
        $this->db->insert($this->table_name, $obj); 
    }

    

     // CONFIGURA O BANCO DE DADOS USANDO OS DADOS DO CLIENTE
     // TEM QUE DEIXAR MAIS OTIMIZADO ISSO, PRA QUE ELE N BUSQUE ESSE DADOS
     // TODA HORA
     function configure($company_id){
        $database_company = $this->db->from("company")->where("id",$company_id)->get()->row();
        $dsn1 = "mysqli://{$database_company->mysql_user}:{$database_company->mysql_pass}@{$database_company->mysql_host}/{$database_company->mysql_db}";
        // dump($this->load->database($dsn1, true));
        $this->database_client = $this->load->database($dsn1, true); 
        $connected = $this->database_client->initialize();
        if (!$connected) {
            die("Erro ao conectar com o banco de dados");
        }
     }

}
?>