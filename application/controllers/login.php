<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Nomauthenticated_controller {

	var $username;
	var $password;
	var $company;

	/**
	 * Tela de usuario, 
	 * uma hora escrevo o resto
	 */
	public function index(){

		$get_company_id = @$this->input->get("cp");
		if(!empty($get_company_id)){
			$this->session->set_userdata('get_company_id', $get_company_id);
		}
		
		$arr_send_data["company_id"] = @$this->session->userdata("get_company_id");
		$client_default = $this->config->item('client_default');
		if(!empty($client_default)){
			$arr_send_data["facebook_login"] = $this->get_facebook_login_link();
		}

		$this->load->view('partial/head',$this->data);
		$this->load->view('users/'.$this->config->item('client_default').'/login',$arr_send_data);
		$this->load->view('partial/footer',$this->data);

	}

	function register_facebook(){
		$this->load->model("user_model");
		

		$data = $this->get_facebook_login_link();
		if(!empty($data["user_profile"])){
			$user_insert = new stdClass();
			$user_insert->name = $data["user_profile"]["name"];
			$user_insert->facebook_id = $data["user_profile"]["id"];
			$user_insert->company_id = $this->config->item("client_default_code");
			$user_insert->username = $data["user_profile"]["email"];
			
			if($this->user_model->verify_user($user_insert->username)){
				$response = $this->user_model->login($user_insert->username,false,$user_insert->facebook_id);
			}else{
				$this->user_model->register($user_insert);
				$response = $this->user_model->login($user_insert->username,$user_insert->password);
			}

			if(!$response){
				redirect("login/index");
			}else{
				redirect("dashboard/index");
			}
			// $user_insert->password = md5($this->input->post('password'));
		}

		redirect("login/index");
	}

	

	public function get_facebook_login_link(){
		$fb_config = array(
            'appId'  => $this->config->item('facebook_app_id'),
            'secret' => $this->config->item('facebook_app_pass')
        );
        $this->load->library('facebook', $fb_config);
        $user = $this->facebook->getUser();
        $access_token = "";
        if ($user) {
            try {
                $data['user_profile'] = $this->facebook->api('/me');
                $data['permissoes'] = $permissions = $this->facebook->api("/me/permissions");
                // $data['user_pages'] = $accounts_data =  $this->facebook->api('/me/accounts');
                $access_token = $this->facebook->getAccessToken();
                $data['user_pages'] = $this->facebook->api('/me/accounts', 'GET', array('access_token' => $access_token));
            } catch (FacebookApiException $e) {
                $user = null;
            }
            $data['logout_url'] = $this->facebook->getLogoutUrl(array('next'=>site_url("login/facebook_logout")));
            // dump($data);
        }
        $data['login_url'] = $this->facebook->getLoginUrl(array(
		   'scope' => 'email,user_about_me',
		   'redirect_uri' => site_url("login/register_facebook")
		));

        // dump($data);die;
        return $data;
	}

	function register(){
		$this->load->library('form_validation');
		
		$data["facebook_login"] = $this->get_facebook_login_link();


		$this->load->view('partial/head',$this->data);
		$this->load->view('users/'.$this->config->item('client_default').'/registration',$data);
		$this->load->view('partial/footer',$this->data);

	}

	function register_action(){
		$this->load->library('form_validation');
		$this->load->model("user_model");

		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');

		$user_in_use = false;
		if($this->user_model->verify_user($this->input->post('email'))){
			$user_in_use=true;
			$this->data["error_message"] = "Usuário em uso";
		}

		if ($this->form_validation->run() == FALSE || $user_in_use == true)
		{
			$this->data["facebook_login"] = $this->get_facebook_login_link();

			$this->load->view('partial/head',$this->data);
			$this->load->view('users/'.$this->config->item('client_default').'/registration',$this->data);
			$this->load->view('partial/footer',$this->data);
		}
		else
		{
			$user_insert = new stdClass();
			$user_insert->name = $this->input->post('name');
			$user_insert->username = $this->input->post('email');
			$user_insert->password = md5($this->input->post('password'));
			$user_insert->company_id = $this->config->item("client_default_code");

			$this->user_model->register($user_insert);
			$response = $this->user_model->login($user_insert->username,$user_insert->password);
			if(!$response){
				redirect("login/index");
			}else{
				redirect("dashboard/index");
			}

		}
		
	}


	/***
		loga no sistema
		arrumar o negocio da empresa que loga!
	**/
	function do_login(){
		
		session_destroy();
		session_start();

		$this->load->model("user_model");
		$this->username = $this->input->post("username");
		$this->password = $this->input->post("password");

		$response = $this->user_model->login($this->username,md5($this->password));
		if(!$response){
			redirect("login/index");
		}else{
			redirect("dashboard/index");
		}
	}

	function logout(){
		$this->session->sess_destroy();
		session_destroy();
		
		redirect("login/index");
	}

	function facebook_logout(){
		$fb_config = array(
            'appId'  => $this->config->item('facebook_app_id'),
            'secret' => $this->config->item('facebook_app_pass')
        );

        $this->load->library('facebook', $fb_config);
		setcookie('fbs_'.$this->facebook->getAppId(), '', time()-100, '/', 'domain.com');
		session_destroy();
		redirect("login/do_login");
	}

	function get_zones(){
		$this->load->model("user_model");
		$estado_id = $this->input->post('estado');
		$cities = $this->user_model->get_zones($estado_id);
		$this->output->set_content_type('application/json', 'utf-8')->set_output(json_encode($cities));
	}

}
