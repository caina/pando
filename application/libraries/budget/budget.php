<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budget extends Authenticated_controller {

	public function __construct()
	{
		parent::__construct();
		$this->set_white_list(array(""));

		$this->load->model("../libraries/budget/model/budget_model",'budget');

		// $this->add_asset("application/libraries/blog/Assets/js/blog_js.js");
	}

	public function index()
	{
		$this->add_asset("application/libraries/budget/Assets/js/budget_create_js.js");
		$this->data['budgets'] = $this->budget->get_budgets()->budgets;

		$this->load_view("../libraries/budget/views/budget_home");
		
	}

	public function remove($id)
	{
		$this->budget->remove($id);
		redirect(site_url("/act/budget/budget/index"));
	}
	public function edit($id)
	{
		$this->add_asset("application/libraries/budget/Assets/js/budget_budget_create.js");

		$this->data['budget'] = $this->budget->get_budget($id)->budget;
		$this->load_view("../libraries/budget/views/budget_create");
	}

	public function create()
	{
		$this->add_asset("application/libraries/budget/Assets/js/budget_budget_create.js");

		$this->data['budget'] = $this->budget->create_new()->budget;
		$this->load_view("../libraries/budget/views/budget_create");
	}

	function save_budget(){
		$form = $this->input->post();
		$this->budget->save_budget($form);
	}

	public function calculate_budget()
	{
		$budget_id = $this->input->post('id');
		$this->budget->calculate_budget($budget_id);

	}

	public function configure()
	{
		$this->add_asset("application/libraries/budget/Assets/js/budget_configure_js.js");


		$this->data['configure'] = $this->budget->get_configuration()->configure;
		$this->load_view("../libraries/budget/views/budget_configure");
	}

	public function save_configuration()
	{
		$form = $this->input->post();
		$this->budget->save_configuration($form);
	}
}

/* End of file budget.php */
/* Location: ./application/controllers/budget.php */