<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email extends Authenticated_controller {

	public function __construct()
	{
		$this->set_white_list(array("email_detail"));

		parent::__construct();
		$this->load->model("../libraries/email/email_model");

		$this->add_asset("application/libraries/email/Assets/js/mail.js");
		// $this->add_asset("application/libraries/blog/Assets/js/bootstrap-tagsinput.min.js");
		// $this->add_asset("application/libraries/blog/Assets/css/bootstrap-tagsinput.css","css");
	}

	public function index()
	{
		$this->data["emails"] = $this->email_model->load_emails();
		$this->email_model->read_message();
		// dump($this->data["emails"]);die;
		$this->load_view("../libraries/email/views/list_emails");
	}

	function email_detail($id){
		$this->data["email"] = $this->email_model->get_email($id);
		

		if(!$this->email_model->is_mine()){
			set_message("Email não encontrado");
			redirect(site_url("dashboard"));
		}
		
		$this->email_model->read_message($id);
		$this->load_view("../libraries/email/views/email_detail");
	}

	function download_emails(){
			$filename = 'CSV_Report.csv';

	        $this->load->dbutil();
	        $this->load->helper('file');
	        $this->load->helper('download');
	        $delimiter = ",";
	        $newline = "\r\n";
	        $data = $this->dbutil->csv_from_result($this->email_model->load_mail_list(), $delimiter, $newline);
	        force_download($filename, $data);
	}


}

/* End of file email.php */
/* Location: ./application/controllers/email.php */