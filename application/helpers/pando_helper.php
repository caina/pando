<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');
}

function dump($arr) {
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
	die;
}

/***
FUNCAO QUE VAI PEGAR OS DADOS DO USUÁRIO
A SESSAO DO CODEIGNITER NAO ESTA RESPONDENDO CORRETAMENTE
COM O SERVIDOR, POR ISSO OPTAMOS POR VOLTAR PRA SESSAO NORMAL DO PHP
 **/
function get_logged_user() {
	$ci           = &get_instance();
	$session_user = "";
	if(!empty($_SESSION["logged_user"])){
		$session_user = $_SESSION["logged_user"];
	}else if (!empty($_SESSION["user"])) {
		$session_user = $_SESSION["user"];
	} else {
		$session_user = $ci->session->userdata("user");
	}
	return $session_user;
}

function cut_string($string,$size){
	return (strlen($string) > $size) ? substr($string,0,$size) : $string;
}

function getImagesPath() {
	$ci           = &get_instance();
	$session_user = unserialize(get_logged_user());
	return $session_user->upload_path;
}

function bytes_converter($size, $show_units = true) {
	$unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
	return @round($size/pow(1024, ($i = floor(log($size, 1024)))), 2).($show_units?' '.$unit[$i]:'');
}

function send_image_to_client($image_name, $file_alloweds = false) {
	// $model->screen_model->get_db();
	$ci = &get_instance();

	$session_user = unserialize(get_logged_user());
	// dump($session_user);
	// dump($_FILES[$image_name]);
	$file_name = md5(date("d_m_Y_H_m_s_u"))."_".str_replace(" ", "_", stripAccents($_FILES[$image_name]['name']));
	// $file_name = md5(date("Ymds"));
	$filename = $_FILES[$image_name]['tmp_name'];
	$handle   = fopen($filename, "r");
	$data     = fread($handle, filesize($filename));

	$POST_DATA = array(
		'file' => base64_encode($data),
		'name' => $file_name);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $session_user->upload_path."upload.php");
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $POST_DATA);
	curl_setopt($curl, CURLOPT_HEADER, true);
	$response = curl_exec($curl);
	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);
	if($httpCode != 200) {
		set_message("Erro ao publicar foto: <br/>".$response,2);
	}

	// dump($response);
	return $file_name;
}

function stripAccents($stripAccents) {
	return str_replace(",", "", strtr($stripAccents, 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY'));
}

function get_json($url, $post_data = false) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_FAILONERROR, 0);
	if ($post_data) {
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	}

	$data = curl_exec($ch);
	curl_close($ch);
	return json_decode($data);
}

function set_message($message,$type=1){
	if(!has_message()){
		$_SESSION["data_message"] = array("message"=>$message,"type"=>$type);
	}
}


function destroy_message(){
	unset($_SESSION["data_message"]);
}

function has_message(){
	return !empty($_SESSION["data_message"]);	
}

function type_message(){
	if(has_message()){
		return $_SESSION["data_message"]["type"];
	}
}


function display_message(){
	if(has_message()){
		$message = $_SESSION["data_message"]["message"];
		unset($_SESSION["data_message"]);
		return $message;
	}
}

function reaisToFloat($reais) {
	return str_replace(',', '.', str_replace('.', '', $reais));
}

function view_evalute($value = false) {
	return $value?$value:'';
}

function isJson($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}

?>
