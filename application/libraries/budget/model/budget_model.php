<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budget_model extends Dynamic_model {

	public $configure;
	public $budget;
	public $budgets;
	public $calculated_budget;

	public function __construct()
	{
		parent::__construct();
		
	}

	public function get_budgets()
	{
		if(!$this->table_exists('budget_budget')){
			$this->create_budget_table();
		}
		$this->budgets = $this->get_db()->get('budget_budget')->result();
		return $this;
	}

	public function remove($id)
	{
		$this->get_db()->delete('budget_budget', array("id"=>$id));
	}

	public function create_new()
	{
		if(!$this->table_exists('budget_budget')){
			$this->create_budget_table();
		}

		$this->get_configuration();
		unset($this->configure->id);
		$this->get_db()->insert("budget_budget",$this->configure);
		return $this->get_budget($this->get_db()->insert_id());
	}

	function get_budget($budget_id){
		$this->budget = $this->get_db()->get_where('budget_budget', array("id"=>$budget_id))->row();
		return $this;
	}

	public function get_configuration()
	{
		if(!$this->table_exists('budget_configure')){
			$this->create_budget_configure_table();
			$this->get_db()->insert("budget_configure",array("tax"=>0));
		}

		$this->configure = $this->get_db()->get('budget_configure')->row();
		return $this;
	}

	public function save_configuration($form)
	{
		$this->get_db()->update('budget_configure', $form, array("id"=>1));
	}

	public function calculate_budget($budget_id)
	{
		$this->get_budget($budget_id);
		$this->calculated_budget = new Budget_class();
		
		$this->calculated_budget->set_budget($this->budget);
		$this->calculated_budget->calculate();



		$this->calculated_budget->format();
		$this->output->set_content_type('application/json')->set_output(json_encode($this->calculated_budget));
	}

	private function table_exists($table)
	{
		return $this->get_db()->table_exists($table);
	}

	public function save_budget($form)
	{
		$id = $form["id"];
		unset($form["id"]);
		$form["has_home_page"] = isset($form["has_home_page"])? $form["has_home_page"]:0;
		$form["use_template"] = isset($form["use_template"])? $form["use_template"]:0;
		$this->get_db()->update('budget_budget', $form ,array("id"=>$id));
	}

	public function create_budget_table()
	{
		$query = "
		CREATE TABLE `budget_budget` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `project_identifier` varchar(255) NULL,
		  `has_home_page` int(11) NOT NULL DEFAULT '1',
		  `number_pages` int(11) DEFAULT NULL,
		  `use_template` int(1) NOT NULL DEFAULT '0',
		  `theme_value` double(11,2) NOT NULL DEFAULT '0.00',
		  `used_configuration_hour` double(11,2) NOT NULL DEFAULT '0.00',
		  `used_programing_hour` int(11) NOT NULL DEFAULT '0',
		  `used_design_hour` int(11) NOT NULL DEFAULT '0',
		  `tax` double(11,2) NOT NULL DEFAULT '0.00',
		  `profit` double(11,2) NOT NULL DEFAULT '0.00',
		  `design_hour` double(11,2) NOT NULL DEFAULT '0.00',
		  `page_value` double(11,2) NOT NULL DEFAULT '0.00',
		  `home_page_value` double(11,2) NOT NULL DEFAULT '0.00',
		  `programing_hour` double(11,2) NOT NULL DEFAULT '0.00',
		  `configuration_value` double(11,2) NOT NULL DEFAULT '0.00',
		  PRIMARY KEY (`id`)
		)";
		$this->get_db()->query($query);

	}

	private function create_budget_configure_table()
	{
		$query = "
		CREATE TABLE `budget_configure` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `tax` double(11,2) NOT NULL DEFAULT '0.00',
		  `profit` double(11,2) NOT NULL DEFAULT '0.00',
		  `design_hour` double(11,2) NOT NULL DEFAULT '0.00',
		  `page_value` double(11,2) NOT NULL DEFAULT '0.00',
		  `home_page_value` double(11,2) NOT NULL DEFAULT '0.00',
		  `programing_hour` double(11,2) NOT NULL DEFAULT '0.00',
		  `configuration_value` double(11,2) NOT NULL DEFAULT '0.00',
		  PRIMARY KEY (`id`)
		);";

		$this->get_db()->query($query);
	}
}


class Budget_class{
	public $itens = array();
	public $configuration_value = "";
	public $sub_total = "";
	public $total = "";
	public $taxes = "";
	public $profit = "";

	public $diagramation_value;
	public $design_value;
	public $programation_value;
	public $condiguration_value;
	public $theme_value;


	private $budget;

	public function set_budget($budget)
	{
		$this->budget = $budget;
	}

	public function calculate()
	{
		

		if($this->budget->use_template){
			$this->diagramation_value = 0;

		}else{
			$this->diagramation_value = 0;
			if($this->budget->has_home_page){
				$this->diagramation_value += $this->budget->home_page_value;
				$this->set_item("Diagramação Home","1 Página", $this->budget->home_page_value,$this->budget->home_page_value);
			}
			$this->diagramation_value += $this->budget->page_value * $this->budget->number_pages;
			$this->set_item(
				"Diagramação de ".$this->budget->number_pages." páginas",
				$this->budget->number_pages." páginas",
				$this->budget->page_value,
				$this->budget->page_value * $this->budget->number_pages);
		}

		$this->theme_value = $this->budget->theme_value;
		$this->set_item(
				"Compra do Template",
				0,
				$this->theme_value,
				$this->theme_value);
	
		$this->configuration_value = $this->budget->used_configuration_hour * $this->budget->configuration_value;

		$this->set_item(
				"Configuração e publicação",
				$this->budget->used_configuration_hour,
				$this->budget->configuration_value,
				$this->configuration_value);
		
		$this->desgin_value = $this->budget->design_hour * $this->budget->used_design_hour;
		if($this->desgin_value>0){
			$this->set_item(
				"Design",
				$this->budget->used_design_hour,
				$this->budget->design_hour,
				$this->desgin_value
				);
		}

		$this->programation_value = $this->budget->used_programing_hour * $this->budget->programing_hour;
		if($this->programation_value>0){
			$this->set_item(
				"Programação",
				$this->budget->used_programing_hour,
				$this->budget->programing_hour,
				$this->programation_value
			);
		}

		$this->sub_total = 	$this->diagramation_value + 
							$this->desgin_value + 
							$this->programation_value + 
							$this->theme_value +
							$this->configuration_value;

		$this->taxes = $this->sub_total * ($this->budget->tax/100);

		$this->profit = $this->sub_total * ($this->budget->profit/100);
		$this->total = $this->sub_total + $this->taxes + $this->profit;

	}

	public function format()
	{
		$this->total = number_format($this->total,2, ",",".");
		$this->taxes = number_format($this->taxes,2, ",",".");
		$this->sub_total = number_format($this->sub_total,2, ",",".");
		$this->profit = number_format($this->profit,2, ",",".");

	}


	public function add_diagramation($number_hours)
	{
		
	}



	public function set_item($name,$hour, $unity_value,$total_value)
	{
		if($total_value <=0) return;

		$this->itens[] = array(
			"name"=>$name,
			"hours"=>$hour,
			"unity_value"=>number_format($unity_value,2, ",","."),
			"total_value"=>number_format($total_value,2, ",",".")
		);
	}

}


/* End of file budget_model.php */
/* Location: ./application/models/budget_model.php */