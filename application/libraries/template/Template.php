<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template extends Authenticated_controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("../libraries/template/model/template_model");
		$this->set_white_list(array("edit_informations","change_template"));
	}

	public function index($theme_id=false){
		$this->add_asset("application/libraries/template/Assets/css/template_page.css",'css');
		$this->add_asset("assets/cube/js/jquery.maskedinput.min.js");
		$this->add_asset("application/libraries/template/Assets/js/template_page.js");
		
		if(!$theme_id){
			$this->data['current_theme'] = $this->template_model->get_first_theme()->Template;
		}else{
			$this->data['current_theme'] = $this->template_model->get_template($theme_id)->Template;
			if(empty($this->data['current_theme']->id)){
				redirect(site_url("act/template/template/index/"));
			}
		}

		// dump($this->data['current_theme']);
		$this->top_menu_html = $this->load->view('../libraries/template/views/widget_template_list.php', $this->data, TRUE);
		$this->load_view("../libraries/template/views/template_page_views");
	}

	public function create_new()
	{
		$template_id = $this->template_model->create_new_template()->Template->id;
		redirect(site_url("act/template/template/index/{$template_id}"));
	}

	function update_version($template_id){
		$form_data = $this->input->post();

		if(!isset($form_data["published"])){
			$form_data["published"] = 0;
		}else{
			$this->template_model->activate_template_version($template_id,$form_data["id"]);
		}
		
		$form_data["publish_date"] = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $form_data["publish_date"]." 00:00:00");
		
		$this->template_model->Version->inflate_row($form_data);
		$this->template_model->update_version();
		redirect(site_url("act/template/template/index/{$template_id}"));
	}

	public function new_version($template_id)
	{
		$this->template_model->create_version($this->input->post());
		redirect(site_url("act/template/template/index/{$template_id}"));
	}

	public function edit_informations($value='')
	{
		$post_data = $this->input->post();
		$this->template_model->get_template($post_data["id"])->update_template_data($post_data);
		if (strtolower(filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH')) !== 'xmlhttprequest') {
			redirect(site_url("act/template/template/index/".$post_data['id']));
		}
	}

}

/* End of file Template.php */
/* Location: ./application/controllers/Template.php */