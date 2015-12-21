<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class SidebarMenu {

	var $ci;

	public function __construct(){
		$this->ci =&get_instance();
	}

	public function load_menu($data){
		$user_logged =& $data["logged_user"];
		
		if($user_logged->developer == 1 || empty($user_logged->modules)){
			$this->set_menu_to_session($data);	
		}
		
		return $this->ci->load->view('partial/menu', $data, true);
	}

	private function set_menu_to_session($data){
		$user_logged = $data["logged_user"];
		
		$this->ci->db->select("
			module.id,
			module.mysql_table, 
			module.name, 
			module.icon,
			module.snippet_id,
			module.menu_id,
			IFNULL(snippet_menu.controller,'screen/listing/') as controller,
			IFNULL(snippet_menu.method,module.mysql_table) as method,
			IFNULL(snippet_menu.label,module.name) as label,
			IFNULL(snippet.block_name,'Website') as block_name,
			IFNULL(snippet.module_name,'') as module_name,
			IFNULL(menu.id,snippet_menu.snippet_id) as menu_id,
			ifnull(menu.label,snippet.label) as menu_label,
			(select count(*) from snippet_menu a where a.snippet_id = snippet.id) +
			(select count(*) from module m where m.menu_id = menu.id) 
			as menu_count,
			'0' as badge
		",false);
		$this->ci->db->from("module");
		$this->ci->db->join('menu', 'module.menu_id = menu.id', 'left');
		$this->ci->db->join('snippet', 'snippet.id = module.snippet_id', 'left');
		$this->ci->db->join('snippet_menu', 'snippet_menu.snippet_id = snippet.id', 'left');
		
		if($user_logged->developer != 1){
			$this->ci->db->join('permissions', 'permissions.module_id = module.id');
			$this->ci->db->join('user_roles', 'permissions.role_id = user_roles.role_id');

			$this->ci->db->where('user_roles.user_id', $user_logged->user_id, FALSE);
		}
		
		$this->ci->db->where('module.active', 1, FALSE);
		$this->ci->db->where('module.is_valid', 1, FALSE);
		$this->ci->db->where('module.company_id',$user_logged->company_id , FALSE);

		$this->ci->db->order_by('block_name, menu.id,module.id');

		$user_logged->modules = $this->ci->db->get()->result();
		$user_logged->selected_menu = @$_SESSION['selected_menu'];
		$this->ci->session->set_userdata('user', serialize($user_logged));
		$_SESSION["logged_user"] = serialize($user_logged);
	}
}