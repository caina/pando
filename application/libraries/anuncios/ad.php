<?php
class Ad extends Authenticated_controller {


	public function __construct() {
		parent::__construct();
		$this->set_white_list(array("manage_list_ajax","category_load_values","ajax_ad","check_price","api_get_brand","api_get_model","api_get_ano","api_get_versao","api_get_price","payment"));


		$this->load->model(array(
				"../libraries/anuncios/model/ad_category_model",
				"../libraries/anuncios/model/ad_image_model",
				"../libraries/anuncios/model/ad_vehicle_model",
				"../libraries/anuncios/model/ad_model",
			));

		$this->add_asset("application/libraries/anuncios/Assets/css/cadastro.css", "css");

	}

	public function home_page() {
		$this->add_asset("application/libraries/anuncios/Assets/js/listar_view.js");
		$this->data["pager_html"] = $this->load->view("../libraries/anuncios/views/dependences/pagination_html", array("pages" => $this->ad_model->number_pager_display()), true);
		$this->load_view("../libraries/anuncios/views/anuncio_view/listar_view");
	}

	function manage_list_ajax() {
		$this->load->model('../libraries/anuncios/model/ad_price_model', 'price');
		$page = $this->input->post("page");
		$this->ad_model->manage_list($page);

		// dump($this->ad_model->objects_listing);
		$view_html = $this->load->view("../libraries/anuncios/views/dependences/list_rows", array("ads" => $this->ad_model->objects_listing, 'prices' => $this->price->listing()), true);

		// $thois->ad_model->
		$this->output->set_content_type('text/plain', 'UTF-8')->set_output($view_html);
	}

	function payment() {
		require_once APPPATH.'third_party/PagSeguroLibrary/PagSeguroLibrary.php';

		$this->load->model('../libraries/anuncios/model/ad_price_model', 'price');
		$this->load->model('user_model');

		// valida se o anuncio e o preco realmente existem
		if( !$this->price->get($this->input->post('price_id', TRUE))->exists() 
				|| 
			!$this->ad_model->get($this->input->post('ad_id', TRUE))->is_mine() )
		{
			set_message("Operação inválida",2);
			redirect(site_url("act/anuncios/ad/home_page"));
		}


		$paymentRequest = new PagSeguroPaymentRequest();
		$paymentRequest->AddItem($this->price->object->id,$this->price->object->title,1,'0.10'/*$this->ad_model->object->price*/);
		$paymentRequest->setShippingType(3);
			
		$user_object = $this->user_model->get();
		// dump($user_object);

		// precisamos dos dados de cidade, bairro, rua e numero
		$paymentRequest->setShippingAddress(  
		  str_replace(array("-","."), "", $user_object->zip_code),
		  @$user_object->street_name,  
		  @$user_object->number,
		  '',  
		  @$user_object->district,  
		  @$user_object->city_name,  
		  @$user_object->state_letter,  
		  'BRA'  
		);

		$type = !empty($user_object->cpf)?"CPF":"CNPJ";
		$type_value = !empty($user_object->cpf)?$user_object->cpf:$user_object->cnpj;

		$paymentRequest->setSender(  
		  $user_object->name,  
		  $user_object->username,  
		  '11',  
		  '',  //str_replace(array("(",")"," ",".","-"), "", $user_object->phone)
		  $type,  
		  $type_value  
		); 
		$paymentRequest->setCurrency("BRL"); 
		$paymentRequest->setReference($this->ad_model->object->id);
		$paymentRequest->setRedirectUrl(site_url("act/anuncios/ad/home_page")); 
		$paymentRequest->addParameter('notificationURL', site_url("pagseguro/notification/anuncios/".$this->ad_model->object->id));  

		try {  
  
		  $credentials = PagSeguroConfig::getAccountCredentials(); // getApplicationCredentials()  
		  $checkoutUrl = $paymentRequest->register($credentials);  
		  
		} catch (PagSeguroServiceException $e) {  
		    set_message("Erro ao gerar link pagamento: ".$e->getMessage(),2);
		    redirect(site_url("act/anuncios/ad/home_page"));
		} 


		$this->data["ad"]    = $this->ad_model->object;
		$this->data["price"] = $this->price->object;
		
		$this->load_view("../libraries/anuncios/views/anuncio_view/payment");
		
	}

	public function create_new_ad($id = false) {
		$this->load->model('user_model');
		$this->add_asset("application/libraries/anuncios/Assets/js/fileupload/vendor/jquery.ui.widget.js");
		$this->add_asset("application/libraries/anuncios/Assets/js/fileupload/jquery.iframe-transport.js");
		$this->add_asset("application/libraries/anuncios/Assets/js/fileupload/jquery.fileupload.js");
		$this->add_asset("application/libraries/anuncios/Assets/js/fileupload/jquery.fileupload-process.js");
		$this->add_asset("application/libraries/anuncios/Assets/js/fileupload/jquery.fileupload-image.js");
		$this->add_asset("application/libraries/anuncios/Assets/js/jquery.maskMoney.js");
		$this->add_asset("application/libraries/anuncios/Assets/js/cadastrar_view.js");

		if ($id) {
			$this->ad_model->get($id);
			if ($this->ad_model->is_mine()) {
				$this->data["ad"] = $this->ad_model->get_listing();
			} else {
				set_message("Ooops, este anuncio parece não lhe pertencer");
				redirect(site_url("lib_generic/method/anuncios/ad/home_page"));
			}
		}

		$this->data["user"] = $this->user_model->get();
		$this->data["states"] = $this->user_model->get_states();

		$this->data["categories"] = $this->ad_category_model->get_categories();
		$this->load_view("../libraries/anuncios/views/anuncio_view/cadastrar_view");
	}

	function remove_ad($ad_id) {
		$this->ad_model->get($ad_id);
		if ($this->ad_model->is_mine()) {
			$this->ad_model->remove();
		}
		redirect(site_url("lib_generic/method/anuncios/ad/home_page"));
	}

	function create_new_ad_action() {
		$this->load->model("user_model");
		$id = @$this->input->post('ad_id', TRUE);
		set_message("Anuncio criado com sucesso");
		$this->ad_model->post_to_values($this->input->post("ad"));

		if (!$this->ad_model->valid) {
			die("validar");
		}

		if ($id) {
			set_message("Anuncio editado com sucesso");
			// verifica antes se o id eh do usuario mesmo
			$this->ad_model->get($id);
			if ($this->ad_model->is_mine()) {
				$this->ad_model->post_to_values($this->input->post("ad"));
				$this->ad_model->update($id);
			}
		} else {

			$this->ad_model->object->user_id = unserialize(get_logged_user())->user_id;
			$this->ad_model->create_new($this->ad_model->object);
		}

		// inserir subcategorias
		// veiculo
		$this->ad_vehicle_model->post_to_values(@$this->input->post("vehicle"));
		if (!empty($this->ad_vehicle_model->object)) {
			if ($this->ad_vehicle_model->is_valid()) {
				$this->ad_vehicle_model->create_or_update($this->ad_model->object->vehicle_id);
				$this->ad_model->set_vehicle_id($this->ad_vehicle_model->object->id);
			} else {
				// voltar pra tela como erro
				die("temos um problema ");
			}
		}
		$this->user_model->update_user($this->input->post("user"));
		// updatear imagens
		$this->ad_image_model->set_ad_images($this->input->post('image'), $this->ad_model->object->id);
		

		redirect(site_url("lib_generic/method/anuncios/ad/home_page"));
	}

	// api functions

	// isso cria os campos de select que ficam como dependencias
	function get_ad_optionals() {
		$category = $this->input->post("category", TRUE);
		$ad_id    = $this->input->post('ad_id', TRUE);
		$vehicle  = false;

		if ($ad_id) {
			$this->ad_model->get($ad_id);
			// esse vehicle não ficou generico, ta bem ruim isso :/
			// talvez colocar na tabela de configuracao quem deve ser chamado, nao sei
			$vehicle = $this->ad_vehicle_model->get($this->ad_model->object->vehicle_id);
		}

		$dependences = $this->ad_category_model->get_dependences($category);
		$html_return = "";

		foreach ($dependences as $dependence) {
			$field_value                 = $dependence->element."_id";
			$data_generic                = new stdClass();
			$data_generic->values        = $dependence->force == 1?$this->ad_category_model->get_values($dependence):array();
			$data_generic->selected      = $dependence->force == 1?$vehicle?@$vehicle->$field_value:false:false;
			$data_generic->name          = $dependence->title;
			$data_generic->watch         = !empty($dependence->table_handle)?$dependence->table_handle:false;
			$data_generic->table         = $dependence->element;
			$data_generic->watch_element = !empty($dependence->table_handle_field)?$dependence->table_handle_field:false;

			$html_return .= $this->load->view("../libraries/anuncios/views/dependences/generic", array("data_generic" => $data_generic), true);
		}
		$this->output->set_content_type('text/plain', 'UTF-8')->set_output($html_return);
	}

	// isso vai buscar os dados dos fields de dependencia da categoria
	function category_load_values() {

		$table = $this->input->post('table', TRUE);
		$field = $this->input->post('field', TRUE);
		$value = $this->input->post('value', TRUE);
		$ad_id = $this->input->post('ad_id', TRUE);

		$this->ad_model->get($ad_id);
		if (!empty((array) $this->ad_model->object)) {
			$this->ad_vehicle_model->get($this->ad_model->object->vehicle_id);
		}

		$data_generic = new stdClass();
		if (!empty($table) && !empty($field) && !empty($value)) {
			$data_generic->values   = $this->ad_category_model->get_category_dependences_values($table, $field, $value, $this->ad_vehicle_model->object);
			$data_generic->selected = false;
		}

		$this->output->set_content_type('application/json', 'utf-8')->set_output(json_encode($data_generic));
	}

	function upload_image() {
		// $image_name = send_image_to_client("image", "gif|jpg|png|jpeg");

		$upload_folder = FCPATH.$this->config->item('upload_folder');

		if (!is_dir($upload_folder)) {
			mkdir($upload_folder, 0777, TRUE);
		}

		$conf['upload_path']   = $upload_folder;
		$conf['allowed_types'] = 'gif|jpg|png|jpeg';
		$conf['file_name']     = '';
		$conf['overwrite']     = FALSE;
		$conf['max_size']      = 500;
		$conf['max_width']     = 1920;
		$conf['max_height']    = 1080;
		$conf['encrypt_name']  = TRUE;

		$this->load->library('upload', $conf);

		if (!$this->upload->do_upload("image")) {
			$err         = $this->upload->display_errors();
			$return_data = array(
				"status"  => "fail",
				"message" => strip_tags($err)
			);

		} else {
			$data = $this->upload->data();
			$this->ad_image_model->save_image($data["file_name"]);

			$return_data = array(
				"status"  => "ok",
				"message" => "",
				"images"  => array(
					array(
						"image"      => $data["file_name"],
						"image_path" => site_url("images/".$data["file_name"]."?width=100&height=100&force=true")
					)
				)
			);
		}

		$this                                        ->output->
		set_content_type('application/json', 'utf-8')->
		set_output(json_encode($return_data));
	}

	// se o cara mudar o nome do arquivo no hidden
	// pode trocar a capa, isso tem que ser revisto!
	// update: Vinculei com o id do usuário, paaaarece gambiarra, mas na hora de inserir
	// não sabemos qual o anuncio que sera vinculado, ficou bem seguro :)
	function set_image_cover() {
		$this->load->model('ad_image_model');
		$image_name = $this->input->post('image_name', TRUE);

		$this->ad_image_model->remove_cover($image_name);
		$this->ad_image_model->set_cover($image_name);
	}

	function remove_image() {
		$this->load->model('ad_image_model');
		$image = $this->ad_image_model->get($this->input->post('image_name'));

		if ($this->ad_image_model->is_mine()) {
			unlink(FCPATH.$this->config->item("upload_folder")."/".$this->ad_image_model->object->image_name);
			$this->ad_image_model->delete();
		}
	}

	function load_images_by_ad() {
		$ad_id = $this->input->post('ad_id', TRUE);
		$this->ad_model->get($ad_id);
		if ($this->ad_model->is_mine()) {
			$this->output->set_content_type('application/json', 'utf-8')->set_output(json_encode($this->ad_image_model->get_to_json($ad_id)));
		}
	}

	function ajax_ad() {
		$ad_id = $this->input->post('ad_id', TRUE);
		$this->ad_model->get($ad_id);
		if ($this->ad_model->is_mine()) {
			$this->output->set_content_type('application/json', 'utf-8')->set_output(json_encode($this->ad_model->object));
		}
	}

	function check_price() {
		$this->load->model("../libraries/anuncios/model/ad_version_model", 'version_model');

		$price      = $this->input->post('price', TRUE);
		$version_id = $this->input->post('version_id', TRUE);

		$is_above = $this->version_model->above_fipe($version_id, $price);
		$this->output->set_content_type('application/json', 'utf-8')->set_output(json_encode(array("valid_fipe" => ($this->version_model->object->price > 0), "above" => $is_above, 'price' => $this->version_model->object->formated_price)));

	}

	//
	//
	// FUNCOES PARA BAIXAR OS DADOS DE VEICULOS
	//
	//
	//

	function teste() {
		$this->load->model("../libraries/anuncios/model/ad_brand_model", 'brand');
		$this->load->model("../libraries/anuncios/model/ad_model_model", 'vehicle_model');
		$this->load->model("../libraries/anuncios/model/ad_year_model", 'year_model');
		$this->load->model("../libraries/anuncios/model/ad_version_model", 'version_model');

		$brands = $this->brand->listing();
		foreach ($brands as $brand) {
			$models = get_json("http://www.webmotors.com.br/carro/modelos", array("marca" => $brand->id));
			$this->vehicle_model->populate($models, $brand->id);
			$models = $this->vehicle_model->listing($brand->id);
			foreach ($models as $model) {
				$years = get_json("http://www.webmotors.com.br/carro/anomodelo", array("modelo" => $model->id));
				$this->year_model->populate($years, $model->id);
				$years = $this->year_model->listing($model->id);
				foreach ($years as $year) {

					$versions = get_json("http://www.webmotors.com.br/carro/versoes", array("modelo" => $model->id, "ano" => $year->title));
					$this->version_model->populate($versions, $year->id);
					$versions = $this->version_model->listing($year->id);
					// dump($versions);
					// foreach ($versions as $version) {
					echo $brand->title." ".$model->title." ".$year->title;
					// }
				}
			}
		}
	}

	public function populate_data() {
		$this->add_asset("application/libraries/anuncios/Assets/js/populate_data.js");

		$this->load->model("../libraries/anuncios/model/ad_brand_model", 'brand');

		$this->data["brands"] = $this->brand->listing();
		$this->load_view("../libraries/anuncios/views/anuncio_view/populate_data");
	}

	function api_get_brand() {
		$this->load->model("../libraries/anuncios/model/ad_brand_model", 'brand');
		$this->output->set_content_type('application/json', 'utf-8')->set_output(json_encode($this->brand->listing()));
	}

	function api_get_model() {
		$this->load->model("../libraries/anuncios/model/ad_model_model", 'vehicle_model');

		$brand  = $this->input->post('brand_id', TRUE);
		$models = get_json("http://www.webmotors.com.br/carro/modelos", array("marca" => $brand));
		$this->vehicle_model->populate($models, $brand);

		$this->output->set_content_type('application/json', 'utf-8')->set_output(json_encode($this->vehicle_model->listing($brand)));
	}

	function api_get_ano() {
		$this->load->model("../libraries/anuncios/model/ad_year_model", 'year_model');

		$model = $this->input->post('model_id', TRUE);
		$years = get_json("http://www.webmotors.com.br/carro/anomodelo", array("modelo" => $model));
		$this->year_model->populate($years, $model);

		$this->output->set_content_type('application/json', 'utf-8')->set_output(json_encode($this->year_model->listing($model)));
	}

	function api_get_versao() {
		$this->load->model("../libraries/anuncios/model/ad_version_model", 'version_model');

		$model   = $this->input->post('model_id', TRUE);
		$year    = $this->input->post('year', TRUE);
		$year_id = $this->input->post('year_id', TRUE);

		$versions = get_json("http://www.webmotors.com.br/carro/versoes", array("modelo" => $model, "ano" => $year));
		$this->version_model->populate($versions, $year_id);

		$this->output->set_content_type('application/json', 'utf-8')->set_output(json_encode($this->version_model->listing($year_id)));
	}

	function api_get_price() {
		$this->load->model("../libraries/anuncios/model/ad_version_model", 'version_model');

		$brand   = $this->input->post('brand', TRUE);
		$model   = $this->input->post('model', TRUE);
		$year    = $this->input->post('year', TRUE);
		$version = $this->input->post('version', TRUE);

		$url = "http://www.webmotors.com.br/avaliacao/tabelawebmotors?TipoVeiculo=1&tipo=meuveiculo&estado=RS&marca={$brand}&modelo={$model}&ano={$year}&versao={$version}";
		$ch  = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_FAILONERROR, 0);

		$data = curl_exec($ch);
		curl_close($ch);
		// dump($data);
		$start = strpos($data, "wm.tab_fipe")+22;
		$end   = strpos($data, "wm.tab_min")-18;
		$valor = substr($data, $start, ($end)-$start);

		$valor = reaisToFloat($valor);

		$this->version_model->get($version);
		$this->version_model->object->price = $valor;
		$this->version_model->update();

	}

}

?>
