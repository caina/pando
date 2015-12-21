<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Meet_model extends Dynamic_model {

	public $times;
	public $schedules;
	public $tables;
	public $field;
	public $value;
	public $attendees;
	public $meet_obj;
	public $prepared_obj;

	public function __construct()
	{
		parent::__construct();
		$this->get_params();
	}

	function get_expecific(){
		$this->meet_obj = $this->get_db()->get_where('meet', array("tables_id"=>$this->tables,"schedules_id"=>$this->schedules,"time_id"=>$this->times));
		return $this;
	}

	function get_params(){
		$this->times = $this->get_db()->from("times")->order_by("title")->get()->result();
		$this->tables = $this->get_db()->get("tables")->result();
		$this->schedules = $this->get_db()->get("schedules")->result();
		return $this;
	}

	function prepare(){
		$this->prepared_obj = new stdClass();
		$this->prepared_obj->tables_id = $this->tables;
		$this->prepared_obj->schedules_id = $this->schedules;
		$this->prepared_obj->time_id = $this->times;
		$f = $this->field;
		$this->prepared_obj->$f = $this->value;
	}

	function get_meet_particicipants($table, $schedule){
		$this->attendees =  $this->get_db()->get_where('meet', array("tables_id"=>$table,"schedules_id"=>$schedule))->result();
		return $this;
	}

	function insert_or_update(){
		$this->prepare();
		if($this->get_expecific()->meet_obj->num_rows() > 0){
			$this->get_db()->update('meet', $this->prepared_obj, array("id"=>$this->meet_obj->row()->id));
		}else{
			$this->get_db()->insert('meet', $this->prepared_obj);
		}
	}

}

/* End of file meet_model.php */
/* Location: ./application/models/meet_model.php */