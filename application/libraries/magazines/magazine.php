<?php
class Magazine extends Authenticated_controller{


	public function __construct(){
		parent::__construct(); 
		$this->set_white_list(array("new_post","add_tag","update_facebook"));

		$this->load->model("../libraries/magazines/models/magazine_model");

		// $this->add_asset("application/libraries/blog/Assets/js/blog_js.js");
		// $this->add_asset("application/libraries/blog/Assets/js/bootstrap-tagsinput.min.js");
		// $this->add_asset("application/libraries/blog/Assets/css/bootstrap-tagsinput.css","css");
	}


	function dashboard(){
		$this->add_asset("application/libraries/magazines/Assets/js/dashboard.js");
		

		$this->load_view("../libraries/magazines/views/dashboard");
	}


	function create_new($id=false){
		$this->data["magazine"] = $this->magazine_model->get($id)->magazine;
		$this->load_view("../libraries/magazines/views/create_new");
	}

	
	function add_category(){
		dump($this->input->post());
	}

}