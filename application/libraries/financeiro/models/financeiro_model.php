<?php
class Financeiro_model extends Dynamic_model {
	var $ci;
	var $table_prefix = "";

	var $object;
	var $objects;
	var $object_listing;
	var $objects_listing;
	var $itens_per_page = 10;
	var $founded_itens=0;
	var $db_object;

	public function __construct() {
		parent::__construct();
		$this->ci = &get_instance();
     	$this->ci->load->model("user_model");
	}

	function get($id=false){
		if($id){
			$this->object = $this->db->get_where('company', array("id"=>$id))->row();
		}
		return $this->object;
	}

	function create_dummy(){
		$this->db->insert('company', array("developer_group_id"=>$this->user_model->user->developer_group_id));
		return $this->get($this->db->insert_id());
	}

	function is_mine($id=false){
		$this->get($id);
		return $this->object->developer_group_id = $this->user_model->user->developer_group_id;
	}

	function update_data($post_data){
		unset($post_data["developer_group_id"]);
		$post_data["created"]=1;
		$this->db->update('company', $post_data, array("id"=>$this->object->id));
		return $this->get($this->object->id);
	}

	function listAll(){
		$this->db->from('company');
		$this->db->where('developer_group_id', $this->user_model->user->developer_group_id, FALSE);
		$this->db->where('created', 1, FALSE);
		$this->db->where('id <>', $this->user_model->user->company_id, FALSE);
		$this->db->order_by('status', 'asc');
		$this->objects = $this->db->get()->result();
		return $this->objects;
	}

	function get_by_query($page=0,$search=false,$type=false){
		$this->db->start_cache();

		$this->db->from('company');
		$this->db->where('developer_group_id', $this->user_model->user->developer_group_id, FALSE);
		$this->db->where('created', 1, FALSE);
		if($search){
			$this->db->where('name like', " '%{$search}%'", FALSE);
		}
		if($type){
			$this->db->where('status', $type, FALSE);
		}

		$this->db->stop_cache();
		
		$this->founded_itens = $this->db->get()->num_rows;
		$this->db->limit($this->itens_per_page, $page*$this->itens_per_page);
		$this->db->order_by('id', 'desc');
		$this->objects = $this->db->get()->result();
		
		$this->db->flush_cache();
		return $this->objects;
	}

	function get_companies_total(){
		$group = $this->user_model->user->developer_group_id;
		$this->db->select("
			(select count(id) from company where status = 1 and created = 1 and developer_group_id = {$group} ) as ativos, 
			(select count(id) from company where status = 2 and created = 1 and developer_group_id = {$group} ) as prospectados, 
			(select count(id) from company where status = 3 and created = 1 and developer_group_id = {$group} ) as inativos ", FALSE);
		$this->db->from("company",FALSE);
		return $this->db->get()->row();
	}

}