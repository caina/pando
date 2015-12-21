<?php 
class Business_meeting_model extends Dynamic_model{

	public function __construct(){
		parent::__construct(); 
		$this->table_name = "snippet";
     }

     var $databse_category_sql = 
     	"create table if not exists meet_categories(
     		id int not null primary key AUTO_INCREMENT,
     		categorie_name varchar(70) not null,
     		color varchar(255) null
     	)
	";

     var $databse_times_sql = 
          "create table if not exists meet_times(
               id int not null primary key AUTO_INCREMENT,
               time_string varchar(70) not null,
               category_id int not null
          )
     ";

     var $databse_companies_sql = 
          "create table if not exists meet_companies(
               id int not null primary key AUTO_INCREMENT,
               name varchar(70) not null,
               meet_times_id int not null
          )
     ";

     function save_company($data){
          $this->get_db()->insert("meet_companies",$data);
          return $this->get_db()->insert_id();
     }

     function remove_company_data($company_id){
          $this->get_db()->where("id",$company_id);
          $this->get_db()->delete("meet_companies");
     }

     function create_three_times($category_id){
          $database_times = $this->get_db()->from("meet_times")->where("category_id",$category_id)->get()->result();
          if(empty($database_times)){
               for($i=0;$i<3;$i++){
                    $data = array("time_string"=>"00:00","category_id"=>$category_id);
                    $this->get_db()->insert("meet_times",$data);
               }
               $database_times = $this->get_db()->from("meet_times")->where("category_id",$category_id)->get()->result();
          }
          return $database_times;
     }

     function update_time($category_id, $time_id,$time_string){
          $data=array("time_string"=>$time_string);
          $this->get_db()->where("id",$time_id);
          $this->get_db()->where("category_id",$category_id);
          $this->get_db()->update("meet_times",$data);
     }

     function create_tables(){
          $this->get_db()->query($this->databse_category_sql);
          $this->get_db()->query($this->databse_times_sql);
     	$this->get_db()->query($this->databse_companies_sql);
     }

     function find_companies($category_id){
          return $this->get_db()->
               select("meet_companies.*")->
               from("meet_companies")->
               join("meet_times","meet_times.id = meet_companies.meet_times_id")->
               where("meet_times.category_id",$category_id)->order_by("meet_companies.name", "asc")->get()->result();
     }

     function create_category($data){
          if(empty($data['id'])){
               unset($data['id']);
               $this->get_db()->insert("meet_categories",$data);
          }else{
               $id = $data['id'];
               unset($data['id']);

               $this->get_db()->where("id",$id);
               $this->get_db()->update("meet_categories",$data);
          }

     }

     function findAllCategories(){
          return $this->get_db()->order_by("meet_categories.categorie_name","asc")->get("meet_categories")->result();
     }

     function delete_category($id){
          $this->get_db()->where("id",$id);
          $this->get_db()->delete("meet_categories");
     }

     function find_category($id=false){
          if(!$id){
               return false;
          }
          return $this->get_db()->from("meet_categories")->where("id",$id)->get()->row();    
     }
 }
 ?>