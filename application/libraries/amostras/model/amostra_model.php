<?php
class Amostra_model extends Dynamic_model {
	var $table_prefix = "";

	var $object;
	var $post_data;
	var $objects;
	var $ci;
	var $exists;

	var $itens_per_page = 5;
	var $itens_found;

	public function __construct() {
		parent::__construct();
		$this->ci =&get_instance();
		$this->ci->load->model('user_model');
	}

	function get($code=false){
		if($code){
			$this->object = $this->get_db()->get_where('samples', array("code"=>$code))->row();
			$this->object->date = date("d/m/Y",strtotime($this->object->date));
		}else{
			$user_name = $this->ci->user_model->user->name;
			$user_name = strtoupper(substr($user_name, 0,2)).$this->ci->user_model->user->user_id;

			$this->object = new stdClass();
			$this->object->date = date("d/m/Y");
			$this->object->code = $user_name.($this->user_code_used($user_name)).date("Y");
		}

		$this->object->clients = $this->list_clients();

		return $this;
	}

	function get_display($code=false){
		if($code){
			$this->get($code);
		}
		$this->get_db()->select('samples.*, client.name as client_name, client.email as client_email, client.phone_number as client_phone');
		$this->get_db()->from('samples');
		$this->get_db()->join('client', 'client.id = samples.client_id', 'left');
		$this->get_db()->where('samples.code', $this->object->code);
		$this->object = $this->get_db()->get()->row();

		$user = $this->db->get_where('users', array("id"=>$this->object->user_id))->row();
		$this->object = (object) array_merge((array)$this->object, (array)$user);
		return $this;
	}

	function list_clients(){
		return $this->get_db()->get('client')->result();
	}

	function create_or_edit($post_data){
		$this->post_data = $post_data;
		$this->exists = $this->get_db()->get_where('samples', array("code"=>$post_data["code"]))->num_rows >0;

		if($this->exists){
			$this->get_db()->update('samples', $post_data, array("code"=>$post_data["code"]));	
		}else{
			// dump($this->ci->user_model->user);
			$post_data["user_id"] = $this->ci->user_model->user->user_id;
			$this->get_db()->insert('samples', $post_data);
		}
		return $this->get($post_data['code']);
	}

	function user_code_used($code_base){
		$this->get_db()->from('samples');
		$this->get_db()->where('code like', "%{$code_base}%");
		return $this->get_db()->get()->num_rows;
	}

	function find_by_params($page=0,$code=false,$client_id=false,$user_id=false){

		$this->get_db()->start_cache();
		
		$this->get_db()->select('samples.*, client.name as client_name, client.email as client_email, client.phone_number as client_phone_number', FALSE);
		$this->get_db()->from('samples');
		$this->get_db()->join('client', 'samples.client_id = client.id', 'left');

		if($code){
			$this->get_db()->where('samples.code like', "'%{$code}%'", FALSE);
		}
		if($client_id){
			$this->get_db()->where('samples.client_id', $client_id, FALSE);
		}
		if($user_id){
			$this->db->where('samples.user_id', $user_id, FALSE);
		}
		$this->itens_found = $this->get_db()->get()->num_rows;
		$this->get_db()->stop_cache();
		$this->get_db()->order_by("samples.date","desc");
		$this->get_db()->limit($this->itens_per_page, $page*$this->itens_per_page);
		$this->objects = $this->get_db()->get()->result();

		return $this;
	}

	function run_basic(){

		$basicSQL = <<<EOT
			CREATE TABLE `client` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(255)  NULL,
			  `email` varchar(255)  NULL,
			  `phone_number` varchar(255)  NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

			CREATE TABLE `samples` (
			  `code` varchar(255) NOT NULL,
			  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `client_id` int(11) NOT NULL,
			  `sample_type` varchar(255) NOT NULL,
			  `user_id` int(11) NOT NULL,
			  PRIMARY KEY (`code`),
			  KEY `client_id` (`client_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		
EOT;

		$qrs = explode(";", $basicSQL);
		foreach ($qrs as $query) {
			$this->get_db()->query($query);
		}
	}

}
?>
