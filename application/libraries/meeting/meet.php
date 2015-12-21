<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Meet extends Authenticated_controller {

	public function __construct()
	{
		parent::__construct();
		$this->set_white_list(array("index","listing","update"));

		$this->load->model(array("../libraries/meeting/model/meet_model"));
		$this->add_asset("application/libraries/meeting/Assets/js/main.js");
		$this->add_asset("application/libraries/meeting/Assets/css/main.css","css");

	}

	public function index()
	{
		$this->data["times"] = $this->meet_model->times;
		$this->data["schedules"] = $this->meet_model->schedules;
		$this->data["tables"] = $this->meet_model->tables;
		$this->load_view("../libraries/meeting/views/index");

	}

	function listing(){
		$table = $this->input->post('table', TRUE);
		$schedule = $this->input->post('schedule', TRUE);
		$attendees = $this->meet_model->get_meet_particicipants($table, $schedule)->attendees;
		$this->output
    		->set_content_type('application/json')
    		->set_output(json_encode($attendees));
	}

	function update(){
		$this->meet_model->tables 		= $this->input->post('table', TRUE);
		$this->meet_model->schedules 	= $this->input->post('schedule', TRUE);
		$this->meet_model->times 		= $this->input->post('time', TRUE);
		$this->meet_model->field 		= $this->input->post('field', TRUE);
		$this->meet_model->value 		= $this->input->post('value', TRUE);

		$this->meet_model->insert_or_update();
	}

}