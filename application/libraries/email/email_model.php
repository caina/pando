<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_model extends Dynamic_model {
	
	var $ci;
	var $object;
	public $variable;


	public function __construct(){
		parent::__construct();
		$this->ci = &get_instance();
     	$this->ci->load->model("user_model");
	}

	function load_emails(){
		return $this->db->from('client_email_information')->where("company_id",$this->get_company_id())->order_by("send_date","desc")->get()->result();
	}

	function get_email($id){
		$this->object = $this->db->get_where('client_email_information', array("id"=>$id,"company_id"=>$this->get_company_id()))->row();
		return $this->object;
	}

	function read_message($id=false){
		$condition = array();
		$condition["company_id"] = $this->user_model->user->company_id;
		if($id){
			$condition["id"] = $id;
		}
		$this->db->update('client_email_information', array("notified"=>1), $condition);
	}

	function is_mine($id=false){
		if($id){
			$this->get_email($id);
		}
		return $this->object->company_id = $this->user_model->user->company_id;
	}

	function load_mail_list(){
		return $this->db->select("email")->from('client_email_information')->where("company_id",$this->get_company_id())->order_by("send_date","desc")->get();
	}

	function get_nonreadded_emails(){
		$object = new stdClass();
		
		
		$this->db->start_cache();

     	$this->db->select('id,subject,message,send_date', FALSE);
     	$this->db->from('client_email_information');
     	$this->db->where('notified', '0', FALSE);
     	$this->db->where('company_id', $this->ci->user_model->user->company_id, FALSE);
		$this->db->stop_cache();
     	$object->total = $this->db->get()->num_rows;
     	
     	$this->db->limit(3);
     	$this->db->order_by('send_date', 'desc');
     	$object->result = $this->db->get()->result();

		$this->db->flush_cache();
		return $object;
	}

}

/* End of file email_model.php */
/* Location: ./application/models/email_model.php */