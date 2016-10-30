<?php
class Permission_model extends Authenticated_model {
	var $object;
	var $permission;
	var $permissions;
	var $roles;
	var $role;
	var $ci;
	public function __construct() {
		parent::__construct();
		$this->ci =&get_instance();
		// $this->user = unserialize(get_logged_user());
	}

	function is_mine_role($role_id=false){
		$this->ci->load->model('user_model');
		if(!empty($this->role)){
			$role_id = $this->role->id;
		}
		$this->get_role($role_id);
		return $this->role->company_id == $this->ci->user_model->user->company_id;
	}

	function get_role($role_id){
		$this->role = $this->db->get_where('roles', array("id"=>$role_id))->row();
		return $this->role;
	}

	function set_role($user_id,$role_id){
		$this->db->delete('user_roles', array("user_id"=>$user_id));
		$this->db->insert('user_roles', array("user_id"=>$user_id,"role_id"=>$role_id));
	}

	function list_roles($user_id=false){
		$this->ci->load->model('user_model');
		$user = $this->ci->user_model->user;
		if($user_id){
			$user = $this->ci->user_model->get_($user_id);
		}
		$this->roles = $this->db->get_where('roles', array("company_id"=>$user->company_id))->result();
		return $this->roles;
	}

	function list_permissions(){
		$this->ci->load->model('user_model');
		$this->permissions = $this->db->get_where('module', array("active"=>1,"company_id"=>$this->ci->user_model->user->company_id))->result();
		return $this->permissions;
	}

	function list_selected_permissions($role_id=0){
		$this->ci->load->model('user_model');

		$this->db->select("
			module.id,
			module.name,
			module.icon,
			(
				SELECT count(*) 
				FROM module m
				join permissions on permissions.module_id = m.id
				where permissions.role_id = {$role_id}
				and m.id = module.id
			) as selected
		", FALSE);
		$this->db->from('module');
		$this->db->where('company_id', $this->ci->user_model->user->company_id, FALSE);
		$this->db->where('active', 1, FALSE);
		$this->permissions = $this->db->get()->result();
		return $this->permissions;
	}

	function create($name){
		$this->ci->load->model('user_model');
		
		$obj = new stdClass();
		$obj->company_id = $this->ci->user_model->user->company_id;
		$obj->title = $name;

		$this->db->insert('roles', $obj);
		return $this->list_selected_permissions($this->db->insert_id());
	}

	// para usar, lembrese que a role deve ser setada antes
	function update_permissions($permissions,$role_id=false){
		if($role_id){
			$this->get_role($role_id);
		}

		$this->db->delete('permissions', array("role_id"=>$this->role->id));
		foreach ($permissions as $permission) {
			$this->db->insert('permissions', array("module_id"=>$permission,"role_id"=>$this->role->id));
		}
		return $this->list_selected_permissions($this->role->id);
	}

	/***
	Isso aqui pode nao ser a melhor maneira
	mas nao to conseguindo achar uma mais simples
	temos 4 tipos de rotas
	 1. controle direto: buscar snippet_menu
	 2. acesso pelo screen: buscar modules
	 3. metodos do lib_generic: buscar snippet_menu
	 4. metodos do alias do lib_generic chamado act: buscar snippet_menu
	vamos checar o controller, e separar as pesquisas
	**/
	function user_is_allowed($white_list=false){
		$this->ci->load->model('user_model');
		// TODO descomenta isso depois pra testar
		if($this->ci->user_model->user->developer==1){
			return true;
		}

		$module_id = false;
		$class = $module_node = $this->uri->segment(1);//$this->router->fetch_class();
		if($class=="screen"){
			$module_node = $this->uri->segment(3);
			// dump($module_node);
			$module = $this->db->select("id")->from("module")->where("mysql_table",$module_node)->where("company_id",$this->ci->user_model->user->company_id)->get()->row();
			$module_id = @$module->id;
		}else if($class=="lib_generic"){
			$controller = $this->uri->segment(4);
			$method = $this->uri->segment(5);
			
			$this->db->select('module.id', FALSE);
			$this->db->from('module');
			$this->db->join('snippet', 'snippet.id = module.snippet_id');
			$this->db->join('snippet_menu', 'snippet_menu.snippet_id = snippet.id');
			$this->db->where('module.company_id', $this->ci->user_model->user->company_id, FALSE);
			$this->db->where('snippet_menu.controller like', "%{$controller}%");
			$this->db->where('snippet_menu.method like', "%{$method}%");
			$module = $this->db->get()->row();
			$module_id = @$module->id;
			

		}else if($class == "act"){
			$controller = $this->uri->segment(3);
			$method = $this->uri->segment(4);

			$this->db->select('module.id', FALSE);
			$this->db->from('module');
			$this->db->join('snippet', 'snippet.id = module.snippet_id');
			$this->db->join('snippet_menu', 'snippet_menu.snippet_id = snippet.id');
			$this->db->where('module.company_id', $this->ci->user_model->user->company_id, FALSE);
			$this->db->where('snippet_menu.controller like', "%{$controller}%");
			// TODO verifica se precisa mesmo disso
			$this->db->where('snippet_menu.method like', "%{$method}%");
			$module = $this->db->get()->row();
			$module_id = @$module->id;

		// Não me orgulho disso :( 
		}else if(in_array($class, array("dashboard","permissions","profile"))){
			return true;
		}else{
			$controller = $this->router->fetch_class();
			$method = $this->router->fetch_method();
			$this->db->select('module.id', FALSE);
			$this->db->from('module');
			$this->db->join('snippet', 'snippet.id = module.snippet_id');
			$this->db->join('snippet_menu', 'snippet_menu.snippet_id = snippet.id');
			$this->db->where('module.company_id', $this->ci->user_model->user->company_id, FALSE);
			$this->db->where('snippet_menu.controller like', "%{$controller}%");
			// $this->db->where('snippet_menu.method like', "%{$method}%");
			$module = $this->db->get()->row();
			$module_id = @$module->id;
		}

		if(!empty($white_list)){
			$controller;
			$method;

			if($class == "act"){
				$controller = $this->uri->segment(3);
				$method = $this->uri->segment(4);
			}else if($class=="lib_generic"){
				$controller = $this->uri->segment(4);
				$method = $this->uri->segment(5);
			}

			if(in_array($method, $white_list)){
				$this->db->select('module.id', FALSE);
				$this->db->from('module');
				$this->db->join('snippet', 'snippet.id = module.snippet_id');
				$this->db->join('snippet_menu', 'snippet_menu.snippet_id = snippet.id');
				$this->db->where('module.company_id', $this->ci->user_model->user->company_id, FALSE);
				$this->db->where('snippet_menu.controller like', "%{$controller}%");
				
				$module = $this->db->get()->row();
				$module_id = @$module->id;

				$_SESSION['selected_menu'] = $module_id;

				$this->db->select('module.id', FALSE);
				$this->db->from('module');
				$this->db->join('permissions', 'permissions.module_id = module.id');
				$this->db->join('roles', 'roles.id = permissions.role_id');
				$this->db->join('user_roles', 'user_roles.role_id = roles.id');
				$this->db->where('user_roles.user_id', $this->ci->user_model->user->user_id);
				$this->db->where('module.company_id', $this->ci->user_model->user->company_id);
				$this->db->where('module.id', $module_id);
				$permission = $this->db->get();

				return $permission->num_rows > 0;
			}
		}

		// checar a whitelist
		// verificamos se o usuario tem acesso pelo menos ao controlador
		// se sim, verificamos se o metodo esta na lista


		$this->db->select('module.id', FALSE);
		$this->db->from('module');
		$this->db->join('permissions', 'permissions.module_id = module.id');
		$this->db->join('roles', 'roles.id = permissions.role_id');
		$this->db->join('user_roles', 'user_roles.role_id = roles.id');
		$this->db->where('user_roles.user_id', $this->ci->user_model->user->user_id);
		$this->db->where('module.company_id', $this->ci->user_model->user->company_id);
		$this->db->where('module.id', $module_id);
		
		$_SESSION['selected_menu'] = $module_id;
		$permission = $this->db->get();
		
		$a = @$controller;
		$b = @$method;
		if(empty($a) && empty($b)){
			// isso serve para os modulos das funcoes 1 > n
			// nao tenho solucao melhor por enquanto
			return true;
		}
		
		// if(!($permission->num_rows > 0) && apache_request_headers()["Host"]=="localhost"){
		// 	echo $this->db->last_query();
		// 	dump($controller." - ".$method);
			
		// }

		// dump($permission);
		// echo $this->router->fetch_class();
		// echo "<br/>".$this->router->fetch_method();
		// dump($this->uri->segment(3));

		return $permission->num_rows > 0;
	}



}