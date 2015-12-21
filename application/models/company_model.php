<?php 
class Company_model extends Authenticated_model{

     var $ci;
     var $object;

	public function __construct(){
		parent::__construct(); 
		$this->table_name = "company";
          $this->ci =&get_instance();
     }

     function list_developers_companies(){
     	$this->ci->load->model("user_model");

          $this->db->from('company');
          $this->db->where('developer_group_id',$this->ci->user_model->user->developer_group_id, FALSE);
          $this->db->where('mysql_host <>', "''", FALSE);
          $this->db->where('mysql_user <>', "''", FALSE);
          $this->db->where('mysql_db <>', "''", FALSE);
          $this->db->where('created', 1, FALSE);
          $this->db->where('status', 1, FALSE);
          $this->db->where("developer_group_id", $this->ci->user_model->user->developer_group_id, FALSE);
          $this->object = $this->db->get()->result();

          return $this->object;
     }

     function listAll(){
          $this->ci->load->model("user_model");

          $this->db->from('company');
          $this->db->where('developer_group_id',$this->ci->user_model->user->developer_group_id, FALSE);
          $this->db->where('created', 1, FALSE);
          $this->db->where('status', 1, FALSE);
          $this->db->where("developer_group_id", $this->ci->user_model->user->developer_group_id, FALSE);
          $this->object = $this->db->get()->result();

          return $this->object;
     }


 }
 ?>