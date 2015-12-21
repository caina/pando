<?php
class Blog extends Authenticated_controller{


	public function __construct(){
		parent::__construct(); 
		$this->set_white_list(array("new_post","add_tag","update_facebook","galery_image_upload","load_gallery_images"));

		$this->load->model("../libraries/blog/blog_model");

		$this->add_asset("application/libraries/blog/Assets/js/blog_js.js");
		$this->add_asset("application/libraries/blog/Assets/js/bootstrap-tagsinput.min.js");
		$this->add_asset("application/libraries/blog/Assets/css/bootstrap-tagsinput.css","css");
		$this->add_asset("assets/cube/css/libs/dropzone.css","css");
		$this->add_asset(("assets/cube/js/dropzone.min.js"));

	}

	function listar($row=0){
		$this->load->helper('text');
		
		if(!$row || $row < 0){
			$row = 0;
		}

		$search = $this->input->post('search');

		$this->data["itens_per_page"]	= $this->blog_model->max_lines_show;
		$this->data["row"]				= $row;
		$this->data["totalizadores"] 	= $this->blog_model->get_totalizadores();
		if($this->data["totalizadores"]->postagens < $row*$this->blog_model->max_lines_show){
			$row= $row-1;
		}
		$this->data["database_posts"]	= $this->blog_model->list_posts($row*$this->blog_model->max_lines_show,$search);

		$this->data["current_row"] = $row;
		$this->load_view("../libraries/blog/views/blog_home");
	}

	function aprove_comments(){
		$this->data["totalizadores"] 	= $this->blog_model->get_totalizadores();

		$this->data["comments_aprove"] = $this->blog_model->get_new_comments();
		$this->load_view("../libraries/blog/views/comments_widget",$this->data);
	}

	function comment_actions($comment_id, $action){
		$return_statement = $this->blog_model->comment_action($comment_id, $action);
		$this->output->set_content_type('application/json')->set_output(json_encode($return_statement));
	}

	function delete_post($post_id=false){
		if($post_id){
			$this->blog_model->delete_post($post_id);
		}

		redirect(site_url("lib_generic/method/blog/blog/listar"));
		return;
	}

	function save_post(){
		// verificar se ele deleta os posts de outros idiomas
		$blog_gallery_image = $this->input->post('blog_gallery_image');
		unset($_POST['blog_gallery_image']);


		$save_post = $this->input->post();
		if($save_post["action"]==2){
			$post_id = $save_post["id"];
			$this->delete_post($post_id);
			exit;
		}

		$language = $save_post["action"];
		if(!ctype_digit($language)){
			unset($save_post["action"]);
		}

		if(isset($_FILES)){
			foreach ($_FILES as $key => $file) {
				if(empty($file["name"])){
					continue;
				}
				$save_post[$key] = send_image_to_client($key);
			}
		}
		// dump($save_post);
		//image?
		set_message("Sua postagem foi salva como rascunho");
		$database_post_id = $this->blog_model->insert_post($save_post);
		$this->blog_model->inser_blog_categories($database_post_id, $this->input->post("categories"));

		if(!empty($save_post["id_blog_post"])){
			$database_post_id = $save_post["id_blog_post"];
		}

		//ativar postagens de outros idiomas, ou colocar como rascunho
		$this->blog_model->update_languages_post($database_post_id,$save_post);

		$this->blog_model->updade_gallery_images($database_post_id,$blog_gallery_image);

		// ****
		// se for publicar o post, verificamos se tem como postar no facebook e publicamos
		if($save_post["action"]==1){
			set_message("Sua postagem está publicada");
			$blog_data = $this->blog_model->get_facebook_data();
			if(!empty($blog_data->page_code)){
				
				$fb_config = array(
		            'appId'  => $this->config->item('facebook_app_id'),
		            'secret' => $this->config->item('facebook_app_pass')
		        );
		        $this->load->library('facebook', $fb_config);
		        
		        $page_token='';
		        $page_id='';
		        list($page_token,$page_id) = explode("-", $blog_data->page_code);
		        if(!empty($page_token) && !empty($page_id)){
					try {
						
						$this->facebook->api('/'.$page_id.'/feed', 'post',
				            array('access_token' => $page_token,
					            'message'=> $save_post["title"],
					            'from' => $this->config->item('facebook_app_id'),
					            'to' => $page_id,
					            'caption' => $save_post["title"],
					            'name' => $save_post["title"],
					            'link' => $blog_data->website_blog_page."/".url_title($save_post["title"]),
					            'description' => strip_tags($save_post["entry"])
				            ));
					} catch (Exception $e) {
						set_message("Não foi possível publicar no facebook: ".$e,2);
					}
		        }

			}
		}
		// dump($save_post);die;
		if(ctype_digit($language)){
			redirect(site_url("lib_generic/method/blog/blog/listar"));
		}else{
			redirect(site_url("lib_generic/method/blog/blog/new_post/{$database_post_id}/{$language}"));
		}
	}
	function new_post($post_id=false,$language=false){
		$this->data["language"] = $language;
		if($language)
			$this->data["parent_id"] = $post_id;
		$this->data["database_post"] = $this->blog_model->get_post($post_id,$language);
		$this->data["database_post_categories"] = $this->blog_model->get_categories_by_post($post_id);
		
		$this->data["display_multiupload"] = $this->blog_model->can_display_multiupload();

		$this->data["facebook_data"] = $this->blog_model->get_facebook_data();
		$this->data["facebook_user"] = $this->get_facebook_login_link();

		$this->load_view("../libraries/blog/views/post");
	}

	function add_category(){
		$category = $this->input->post('category', TRUE);
		if(empty($category)){
			$this->load_post_dependencies();
			return;
		}
		$this->blog_model->add_category($category);
		$this->load_post_dependencies();
	}
	
	function remove_category(){
		$category = $this->input->post('category_id', TRUE);
		if(empty($category)){
			$this->load_post_dependencies();
			return;
		}
		$this->blog_model->remove_category($category);
		$this->load_post_dependencies();
	}

	function load_post_dependencies(){
		$post = $this->input->post('post', TRUE);
		$response_arr = array();
		$response_arr["categories"] = $this->blog_model->get_categories($post);
		
		$this->output->set_content_type('application/json')->set_output(json_encode($response_arr));
	}

	function galery_image_upload(){
		if(isset($_FILES)){
			foreach ($_FILES as $key => $file) {
				if(empty($file["name"])){
					continue;
				}
				$image = send_image_to_client($key);
			}
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($image));
	}

	function load_gallery_images(){
		$post_id = $this->input->post('post_id');
		$gallery = $this->blog_model->get_gallery_images($post_id);
		$this->output->set_content_type('application/json')->set_output(json_encode(array("gallery"=>$gallery)));
	}

	function update_facebook(){
		$data = $this->input->post();
		$this->blog_model->save_facebook_data($data);
	}

	public function get_facebook_login_link(){
		$fb_config = array(
            'appId'  => $this->config->item('facebook_app_id'),
            'secret' => $this->config->item('facebook_app_pass')
        );
        $this->load->library('facebook', $fb_config);
        $user = $this->facebook->getUser();
        $access_token = "";
        if ($user) {
            try {
                $data['user_profile'] = $this->facebook->api('/me');
                $data['permissoes'] = $permissions = $this->facebook->api("/me/permissions");
                // $data['user_pages'] = $accounts_data =  $this->facebook->api('/me/accounts');
                $access_token = $this->facebook->getAccessToken();
                $data['user_pages'] = $this->facebook->api('/me/accounts', 'GET', array('access_token' => $access_token));
            } catch (FacebookApiException $e) {
                $user = null;
            }
            $data['logout_url'] = $this->facebook->getLogoutUrl(array('next'=>site_url("login/facebook_logout")));
        }
        $data['login_url'] = $this->facebook->getLoginUrl(array(
		   'scope' => 'email,user_about_me,offline_access,publish_stream,publish_actions,manage_pages'
		));

        // dump($data);die;
        return $data;
	}

}

?>