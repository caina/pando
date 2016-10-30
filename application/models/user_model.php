<?php
class User_model extends Authenticated_model {
	var $object;
	var $insertObject;
	var $user;
	var $users;
	var $user_sql_data;
	var $itens_per_page = 10;

	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->user = unserialize(get_logged_user());
	}

	// TODO verificar porque a session do codeignite parece estar tao ruim
	// muito instavel
	public function is_user_logged() {
		return !empty($this->user->user_id);
	}

	function get($id=false) {
		$this->object = unserialize(get_logged_user());
		if(!$id){
			$id =  $this->object->user_id;
		}
		
		$this->db->select("users.*, users.level, roles.title as role, roles.id as role_id, roles.title as role");
		$this->db->from("users");
		$this->db->join("company", "company.id = users.company_id");
		$this->db->join('user_roles', 'users.id = user_roles.user_id', 'left');
		$this->db->join('roles', 'user_roles.role_id = roles.id', 'left');
		$user = (array) $this->db->where("users.id", $id)->get()->row();

		$this->db->select("user_information.*, state.id as state_id, state.letter as state_letter");
		$this->db->from('user_information');
		$this->db->join('zone', 'zone.id = user_information.zone_id', 'left');
		$this->db->join('state', 'state.id = zone.id_state', 'left');
		$this->db->where('user_id', $user["id"]);

		$address = (array) $this->db->get()->row();
		// dump($address);
		return (object) array_merge($user, $address);
	}
	// NAO E O CORRETO, DEVIA MELHORAR O RETORNO DO METODO ANTERIOR, MAS ESTAMOS COM UM 
	// PROBLEMA DE ESTRUTURA, ESSE METODO BUSCA O USUARIO ATUAL
	function get_($user_id=false){
		if($user_id){
			return  $this->db->get_where('users', array("id"=>$user_id))->row();
		}
	}

	function get_by_company_with_permissions($page=1){
		$this->get_by_company($page);
		foreach ($this->users as &$user) {
			$user->permissions = $this->db->get_where('roles', array("company_id"=>$user->company_id))->result();
		}
		return $this->users;
	}

	function get_by_company($page=1){
		$this->db->select('users.*,roles.title as role,user_roles.role_id as user_role, company.name as company_name', FALSE);
		$this->db->from('users');
		$this->db->join('user_roles', 'users.id = user_roles.user_id', 'left');
		$this->db->join('roles', 'user_roles.role_id = roles.id', 'left');
		$this->db->join('company', 'users.company_id = company.id');
		
		if($this->user->developer != 1){
			$this->db->where('users.company_id', $this->user->company_id);
		}else{
			$this->db->where('company.developer_group_id', $this->user->developer_group_id);
		}
		
		$this->db->limit($this->itens_per_page, $page*$this->itens_per_page);
		$this->users = $this->db->get()->result();
		return $this->users;
	}

	function count_by_company(){
		$this->db->select('users.*, company.name as company_name', FALSE);
		$this->db->from('users');
		if($this->user->developer != 1){
			$this->db->where('users.company_id', $this->user->company_id);
		}
		$this->db->join('company', 'users.company_id = company.id');
		$users = $this->db->get();

		return $users->num_rows() / $this->itens_per_page;;
	}

	function user_is_mine($user_id){
		$this->db->from('users');
		$this->db->join('company', 'users.company_id = company.id');
		$this->db->where('company.developer_group_id', $this->user->developer_group_id, FALSE);
		$a =  $this->db->get()->num_rows() > 0;
		dump($this->db->last_query());
	}

	function company_is_mine($company_id){
		return $this->db->get_where('company', array("developer_group_id"=>$this->user->developer_group_id))->num_rows > 0;
	}

	function verify_user($user_name,$user_id=false) {
		if($user_id){
			return $this->db->get_where('users', array("username" => $user_name,"id <>"=>$user_id))->num_rows > 0;

		}
		return $this->db->get_where('users', array("username" => $user_name))->num_rows > 0;
	}

	function has_user_access($id=false){
		if(!$id)
			return true;

		$query = "
			SELECT
				*
			FROM
				users
			JOIN company ON users.company_id = company.id
			WHERE
				users.id = {$id}
			AND(
				company.id = ".$this->user->company_id."
				OR users.developer_group_id = ".$this->user->developer_group_id."
			)";
		$cn_execu = $this->db->query($query);

		return $cn_execu->num_rows() > 0;
	}

	/***
	Vamos:
	verificar se o usuario existe,
		se sim, vamos ver se o usuario que esta atualizando ele tem acesso ao mesmo
		se sim, vamos alterar
	se nao existir: vamos verificar se o usuario nao esta em uso
		se nao, vamos inserir ele
		se sim, retorna com erro
	***/
	function insert_or_update($arr){
		// dump($arr);

		if(!empty($arr["id"])){
			$this->insertObject = $this->db->get_where('users', array("id"=>$arr["id"]))->row();
		}else{
			$this->insertObject = new stdClass();
		}

		$this->insertObject->name = $arr["name"];
		$this->insertObject->username = $arr["username"];
		$this->insertObject->password = md5($arr["password"]);
		$this->insertObject->company_id = empty($arr["company_id"])?$this->user->company_id:$arr["company_id"];

		if(!$this->verify_user($arr["username"],$arr["id"])){
			if($this->has_user_access($arr["id"])){
				if(!empty($arr["id"])){
					$this->db->update('users', $this->insertObject,array("id"=>$arr["id"]));
					$this->object = $this->get_($arr["id"]);
				}else{
					$this->db->insert('users', $this->insertObject);
					$this->object = $this->get_($this->db->insert_id());
				}
			}else{
				set_message("Operação cancelada",2);
				// TODO deviamos salvar um log aqui pra saber que este usuário quis fazer coisinhas
			}
		}else{
			set_message("Este usuário já está em uso",2);
		}

		if(!has_message()){
			if(!empty($arr["id"])){
				set_message("Usuário ".$this->insertObject->name." alterado");
			}else{
				set_message("Usuário ".$this->insertObject->name." criado");
			}
		}

		return $this;


		// $this->db->update('users', $this->insertObject,array("id"=>$arr["id"]));
		// $this->object = $this->get_($arr["id"]);


		// if(!empty($arr["id"])){
		// 	// update
		// 	if($this->user_is_mine($arr["id"])){
		// 		if(!$this->verify_user($arr["username"],$arr["id"])){
		// 			if($this->company_is_mine($arr["company_id"])) {

		// 				$this->insertObject->name = $arr["name"];
		// 				$this->insertObject->username = $arr["username"];
		// 				$this->insertObject->password = md5($arr["password"]);
		// 				$this->insertObject->company_id = $arr["company_id"];
		// 				$this->db->update('users', $this->insertObject,array("id"=>$arr["id"]));
		// 				$this->object = $this->get_($arr["id"]);
		// 			}else{
		// 				set_message("Você não tem direito de cadastrar um usuário para esta empresa",2);
		// 			}
		// 		}else{
		// 			set_message("Este usuário já está em uso",2);
		// 		}
		// 	}else{
		// 		set_message("Você não pode editar um usuário que não pertence a sua organização.",2);
		// 	}
		// 	if(!has_message()){
		// 		set_message($this->object->name . " Editado com sucesso");
		// 	}
		// }else{
		// 	// dump($arr);
		// 	// insert
		// 	if(!$this->verify_user($arr["username"],$arr["id"])){
		// 		if($this->company_is_mine($arr["company_id"])) {

		// 			$this->insertObject->name = $arr["name"];
		// 			$this->insertObject->username = $arr["username"];
		// 			$this->insertObject->password = md5($arr["password"]);
		// 			$this->insertObject->company_id = $arr["company_id"];
		// 			$this->db->insert('users', $this->insertObject);
		// 			$this->object = $this->get_($this->db->insert_id());
		// 		}else{
		// 			set_message("Você não tem direito de cadastrar um usuário para esta empresa",2);
		// 		}
		// 	}else{
		// 		set_message("Usuário já existe",2);
		// 	}

		// 	if(!has_message()){
		// 		set_message($this->object->name . " Cadastrado com sucesso");
		// 	}
		// }

		// return $this;


		// if(!$this->user_model->verify_user($user_arr["username"])){

		// 	if($this->data["logged_user"]->developer != 1 ){
		// 		$user_arr["company_id"] = $this->data["logged_user"]->company_id;
		// 	}

		// 	$user_arr["password"] = md5($user_arr["password"]);
		// 	if(!empty($user_arr["id"])){
		// 		$id = $user_arr["id"];
		// 		unset($user_arr["id"]);
		// 		$this->db->update('users', $user_arr, array("id"=>$id));
		// 	}else{
		// 		unset($user_arr["id"]);
		// 		$this->company_model->db->insert('users', $user_arr);
		// 		$id = $this->company_model->db->insert_id();
		// 	}

		// 	$this->permission_model->set_role($id,$user_arr['role_id']);

		// 	dump($user_arr);
		// 	set_message("Usuário adicionado");
		// }else{
		// 	set_message("Este usuário já está em uso!",2);
		// }
	}

	function set_role($role_id){
		if(!empty($this->object)) {
			$ci =& get_instance();
			$ci->load->model('permission_model');
			$ci->permission_model->set_role($this->object->id,$role_id);
		}
	}

	function register($insert_object) {
		$this->db->insert('users', $insert_object);
	}

	function vinculate_facebook($data) {
		$user = $this->get();
		$this->db->update('users', $data, array("id" => $user->id));
	}

	function update_user($user_post) {
		$user = $this->get();
		if(isset($user_post['name'])){
			$this->db->update('users', array("name" => $user_post['name']), array("id" => $user->id));
		}

		unset($user_post['name']);

		$user_post["user_id"] = $user->id;
		$user_information     = $this->db->get_where('user_information', array("user_id" => $user->id));
		if ($user_information->num_rows == 0) {
			$this->db->insert('user_information', $user_post);
		} else {
			$this->db->update('user_information', $user_post, array("user_id" => $user->id));
		}
	}

	function login($username, $password, $facebook_id = false) {
		$this->db->select("users.id as user_id,users.developer_group_id, users.level, roles.id as role_id, roles.title as role, users.facebook_id, users.name, company.name as company_name,company.profile_id as profile_id, company.id as company_id, company.upload_path as upload_path, users.is_developer as developer");
		$this->db->from("users");
		$this->db->join("company", "company.id = users.company_id");
		$this->db->join('user_roles', 'users.id = user_roles.user_id', 'left');
		$this->db->join('roles', 'user_roles.role_id = roles.id', 'left');
		$this->db->where("users.username", $username);
		if (!$facebook_id) {
			$this->db->where("users.password", $password);
		} else {
			$this->db->where('users.facebook_id', $facebook_id);
			// where("company.id",$company_id);
		}
		$database_user = $this->db->get();
		// dump($database_user);
		if ($database_user->num_rows > 0) {
			$database_user = $database_user->row();
			$this->db->insert('users_log', array("users_id" => $database_user->user_id));
			$_SESSION["user"] = serialize($database_user);
			$this->session->set_userdata('user', serialize($database_user));
		} else {
			return false;
		}
		return true;
	}

	function load_upload_config() {
		$session_user = unserialize($this->session->userdata("user"));
		dump($session_user);
	}

	function get_states() {
		return $this->db->get('state')->result();
	}

	function get_zones($estado_id) {
		return $this->db->get_where('zone', array("id_state" => $estado_id))->result();
	}

	function update_information($user_data){
		$update = new stdClass();
		$update->website = $user_data["website"];
		$update->description =$user_data["description"];
		$this->db->update('users', $update, array("id"=>$this->user->user_id));
	}

}

?>