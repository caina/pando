<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends Authenticated_controller {

	function index(){
$this->load->model(array("company_model","user_model"));

		$this->data["database_company"] = $this->company_model->listAll();
		$this->data["users"] 			= $this->user_model->get_by_company();
		// dump($this->data['users']);
		// TODO rever essas variaveis
		$this->data["user_obj"] 		= $this->user_model->get();
		$this->data["user"]				= $this->data["user_obj"];
		// dump($this->data["user"]);
		$this->data["states"] 			= $this->user_model->get_states();
		$this->data["facebook_link"] 	= $this->get_facebook_login_link();
		$this->load_view('users/profile');

	}

	function update_account_data(){
		$this->load->model("user_model", 'user');
		$this->user->update_information($this->input->post('user', TRUE));
		set_message('Dados salvos');
		// dump($this->session->userdata('message'));
		redirect("profile");
	}	

	function save_user(){
		$this->load->model("user_model");
		$this->user_model->update_user($this->input->post("user"));
		set_message('Seus dados foram atualizados');
		redirect("profile");
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
		   'redirect_uri' => site_url("profile/vincular_facebook")
		));

        // dump($data);die;
        return $data;
	}

	function vincular_facebook(){
		$this->load->model("user_model");

		$data = $this->get_facebook_login_link();
		if(!empty($data["user_profile"])){
			$data_ = array();
			$data_["name"] = $data["user_profile"]['name'];
			$data_["facebook_id"] = $data["user_profile"]['id'];
			$this->user_model->vinculate_facebook($data_);
			$this->user_model->login($user_insert->username,false,$data["user_profile"]['id']);
			set_message("Vinculado com sucesso");
		}else{
			set_message("Falha ao vincular",2);
		}

		redirect(site_url("profile"));
	}

}

?>