<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clients_model extends Dynamic_model {


		public function __construct()
		{
			parent::__construct();
			
		}

		function get_categories(){
			return $this->get_db()->get("adm_client_category")->result();
		}

		function add_category($post){
			if($this->get_db()->table_exists('adm_client_category') === FALSE){
				$this->create_category_table();
			}
			if(!empty($post)){
				$this->get_db()->insert('adm_client_category', $post);
			}
		}

		function create_category_table(){
			$query = "
			DROP TABLE IF EXISTS `adm_client_category`;
			CREATE TABLE `adm_client_category` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(255) DEFAULT NULL,
			  `color` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;

			SET FOREIGN_KEY_CHECKS = 1;
			";

			$qrs = explode(";", $query);
			foreach ($qrs as $query) {
				$this->get_db()->query($query);
			}
		}

		public function list_full(){
			$company_id = $this->get_company_id();
			return $this->get_db()->get_where("adm_clients",array("company_id"=>$company_id))->result();
		}

		function save_data($save){
			$save["company_id"] = $this->get_company_id();
    		$this->get_db()->insert("adm_clients",$save);
		}

		function get_client($client_id){
			return $this->get_db()->
				select("adm_clients.*, admin_city.nome as city_name,admin_state.nome as state_name")->
				from("adm_clients")->
				join("admin_city","admin_city.id = adm_clients.admin_city_id","left")->
				join("admin_state","admin_city.admin_state_id = admin_state.id","left")->
				where("adm_clients.company_id",$this->data["logged_user"]->company_id)->
				where("adm_clients.id",$client_id)->
				get()->row();
		}
}