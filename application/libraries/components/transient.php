<?php
/****

Transient generator

*/

Class Transient{

	var $config;

	public function __construct($config){
		$this->config = $config;
	}

	public function generate($database_data = false){
		$transients = $this->config->transient;
		$html = false;

		foreach ($transients as $transient) {
			foreach ($transient as $key => $value) {

				if($this->can_perform($value->view)){
					$eval_action = $value->eval;
					if($database_data){
						foreach ($database_data as $key => $value) {
							$eval_action = str_replace($key, "$value", $eval_action);
						}
					}
					eval("\$html .= ".$eval_action.";");
				}
			}
		}
		return $html;
	}

	private function can_perform($actions){
		$current_perform = explode("|",$actions);
		return in_array($this->config->crud_position, $current_perform);
	}
}

?>