<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Business_meeting extends Authenticated_controller {

	public function __construct(){
		parent::__construct(); 
		$this->load->model("business_meeting_model");

		$this->business_meeting_model->create_tables();
		$this->data["arr_color_categories"] = array("azul","amarelo","vermelho");
	}

	function listing($category_id=false){
		$this->data["categories"] 	= $this->business_meeting_model->findAllCategories();

		$category_obj 	= null;
		$times_obj 		= null;
		$companies_obj 	= null;

		if($category_id){
			$category_obj 	= $this->business_meeting_model->find_category($category_id);
			$times_obj 		= $this->business_meeting_model->create_three_times($category_id);
		}

		if(!empty($times_obj)){
			$companies_obj 	= $this->business_meeting_model->find_companies($category_id);	  
		}

		$this->data["companies_data"]	= $companies_obj;
		$this->data["category_obj"] 	= $category_obj;
		$this->data["times_obj"] 		= $times_obj;
		$this->load_view("business_meeting/dashboard");
	}

	function saveCompanyTime($time_id){
		$data = array();
		$data["meet_times_id"] = $time_id;
		$data["name"] = $this->input->post("name");
		$company_inserted = $this->business_meeting_model->save_company($data);
		$this->output ->set_content_type('application/json')->set_output(json_encode(array("id"=>$company_inserted)));
	}

	function remove_company($category, $company_id){
		$this->business_meeting_model->remove_company_data($company_id);
		redirect("business_meeting/listing/{$category}");
	}

	function time_update($category_id, $time_id){

		$this->business_meeting_model->update_time($category_id, $time_id,$this->input->post("time_string"));
		
		redirect("business_meeting/listing/{$category_id}");
	}

	function time_manager(){
		$this->load_view("business_meeting/timer");

	}

	function categories_manager($id= false,$delete=false){

		$post = $this->input->post();
		if($delete){
			$this->business_meeting_model->delete_category($id);
		}else if ($post){
			$this->business_meeting_model->create_category($post);
		}

		


		$this->data["categories"] 	= $this->business_meeting_model->findAllCategories();
		$this->data["category_obj"]	= $this->business_meeting_model->find_category($id);

		$this->load_view("business_meeting/categories");
		
	}

}
