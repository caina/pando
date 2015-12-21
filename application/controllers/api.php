<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class API extends Nomauthenticated_controller {

	function __construct(){
		parent::__construct();
		$this->load->model("company_model");
	} 
 
	function send_email(){
		
		require_once APPPATH.'third_party/Mandrill/Mandrill.php';

		$company_id 	= $this->input->post('company_id');
		$name 			= @$this->input->post('name');
		$email 			= @$this->input->post('email');
		$phone_number 	= @$this->input->post('phone_number');
		$subject 		= @$this->input->post('subject');
		$message 		= @$this->input->post('message');
		$email_to 		= @$this->input->post('email_to');
		$ip 			= @$this->input->post('ip');
		$post 			= @$this->input->post('post');
		$result 		= "";


		$message = @$message;
		if($post){
			foreach ($post as $field => $value) {
				switch ($field) {
					case 'nome':
						$name = $value;
						break;
					case 'email':
						$email = $value;
						break;
					case 'telefone':
						$phone_number = $value;
					break;
				}

				$message .= ucfirst(str_replace("_", " ", $field)) . ": {$value} <br/>";

			}
		}

		$email_configuration = $this->company_model->db->get_where("company",array("id"=>$company_id))->row();
		// dump($email_configuration);
		if(empty($email_configuration))
			return;

		$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));

		$data_save = array(
			"name" => $name,
			"email" => $email,
			"email_to" => $email_to,
			"message"=>$message,
			"subject"=>$subject,
			"company_id" => $company_id,
			"phone_number" => $phone_number,
			"ip" => @$details->ip,
			"city" => @$details->city,
			"country" => @$details->country,
			"region" => @$details->region,
			"postal" => @$details->postal,
			"loc" => @$details->loc
		);

		

		
		$compose = array(
			"message" => $message,
			"email" => $email,
			"name" => $name,
			"data" => date("d/m/Y")
			);
		$subject = empty($subject)? "Contato via site": $subject;

		$html = $this->load->view('webmail_templates/contact_by_website', $compose, TRUE);
		
		$send_to = $email_to?$email_to:$email_configuration->email_contact;
		$email_from = empty($email)?$send_to:$email;

		$ci =&get_instance();

		$emails_array = array();
		$emails = explode(",", $email_to);
		foreach ($emails as $ema) {
			$emails_array[] = array('email' => $ema, 'name' => 'Contato', 'type' => 'to'); }

		try {
			$mandrill = new Mandrill('UrX68pM2VF16aaA1zYrQzw');
		    $message = array(
		        'html' => $html,
		        'text' => strip_tags($html),
		        'subject' => $subject,
		        'from_email' => $email_from,
		        'from_name' => "Email automatico",
		        // 'to' => array(
		        //     array(
		        //         'email' => $email_to,
		        //         'name' => 'Contato',
		        //         'type' => 'to'
		        //     )
		        // ),
		        'to'=>$emails_array,
		        'headers' => array('Reply-To' => $email_from),
		        'important' => false,
		        'track_opens' => null,
		        'track_clicks' => null,
		        'auto_text' => null,
		        'auto_html' => null,
		        'inline_css' => null,
		        'url_strip_qs' => null,
		        'preserve_recipients' => null,
		        'view_content_link' => null,
		        'tracking_domain' => null,
		        'signing_domain' => null,
		        'return_path_domain' => null,
		        'merge' => true,
		        'merge_language' => 'mailchimp',
		        'tags' => array('contato-website')
		    );
		    $async = false;
		    $ip_pool = 'Main Pool';
		    $result = $mandrill->messages->send($message, $async, $ip_pool);
		    // print_r($result);
		  
		} catch(Mandrill_Error $e) {
		    $result =  'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
		    $data_save["error"] = 1;
		    $data_save["error_message"] = $e->getMessage();
		    // throw $e;
		}
		$this->company_model->db->insert('client_email_information', $data_save);

		// print_r($result);
	}

}

?>