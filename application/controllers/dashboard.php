<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Authenticated_controller {

	

	/****
	busca os dados que ficam nos componentes
	para listar na view
	**/
	function index(){
		$this->load->helper('directory');
		$map = directory_map('./application/libraries/components', FALSE, TRUE);
		sort($map, SORT_NATURAL | SORT_FLAG_CASE);
		$display_html = "";
		foreach ($map as $library) {
			if (strpos($library,'.php') !== false) {
				$library = str_replace(".php", "", $library);
				$this->load->library("components/{$library}",array());
				if(method_exists($this->$library, "dashboard_display")){
					$display_html.= $this->$library->dashboard_display();
				}
			}
		}

		$this->data["widgets"] = $display_html;

		$this->load_view('dashboard/index');
	}

}
