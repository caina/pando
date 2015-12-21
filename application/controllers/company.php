<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company extends Authenticated_controller {

	/****
	Tela que gera o menu
	linha de teste
	**/
	function index($id=false){
		$this->load->model("company_model");
		$this->data["database_company"] = $this->company_model->listAll();
		if($id){
			$this->data["company_obj"] = $this->company_model->get($id);
		}
		
		$this->load_view('company/index');
	}

	function register(){
		$this->load->model("company_model");
		
		$post_data = $this->input->post();
		$insert_post_id = @$post_data["id"];
		
		if(!empty($insert_post_id)) {
			unset($post_data["id"]);
			$this->company_model->update($insert_post_id,$post_data);	
			redirect("company/index/{$insert_post_id}");
		}else{
			unset($post_data["id"]);
			$this->company_model->create($post_data);	
			redirect("company");
		}
	}

	// METODO APENAS PARA DESENVOLVEDORES
	function change_selected_company($company_id=false){
		if($this->data["logged_user"]->developer==1){
			$database_company = $this->company_model->get($company_id);
			
			$session_user = unserialize(get_logged_user());
			$session_user->company_id = $company_id;//$this->input->post("company_id");
			$session_user->profile_id = $database_company->profile_id;
			$session_user->upload_path = $database_company->upload_path;
			// dump($session_user);
			$_SESSION["user"] = serialize($session_user);
			$_SESSION["logged_user"] = serialize($session_user);
			$this->session->set_userdata('user', serialize($session_user));
		}
		redirect("dashboard");
	}

	function upload_teste(){
          if (isset($_FILES['uploadedfile'])){
           $filename = $_FILES['uploadedfile']['tmp_name'];
           $handle = fopen($filename, "r");
           $data = fread($handle, filesize($filename));
           $POST_DATA   = array('file'=>base64_encode($data),'name'=>"piroca");
           $curl = curl_init();
           curl_setopt($curl, CURLOPT_URL, 'http://www.mbr.mx/clientes/teste_images/index.php');
           curl_setopt($curl, CURLOPT_TIMEOUT, 30);
           curl_setopt($curl, CURLOPT_POST, 1);
           curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
           curl_setopt($curl, CURLOPT_POSTFIELDS, $POST_DATA);
           $response = curl_exec($curl);
           curl_close ($curl);
           // echo "<h2>File Uploaded</h2>";
          }
	}



	function test_ftp(){
		$this->load->library("ftp");

		$post_company_id = $this->input->post("id");
		$database_company = $this->company_model->get($post_company_id);
		// dump($database_company);
		$ftpconfig['hostname'] = $database_company->ftp_host;
		$ftpconfig['username'] = $database_company->ftp_user;
		$ftpconfig['password'] = $database_company->ftp_pass;
		$ftpconfig['debug'] = TRUE;

		$teste = $this->ftp->connect($ftpconfig);

		$list = $this->ftp->list_files($database_company->upload_path);
		dump($list);
	}
}
