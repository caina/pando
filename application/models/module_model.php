<?php 
class Module_model extends Dynamic_model{

  var $object;
  var $objects;
  var $ci;
  var $post_data;
  var $operation_name;

  var $json_object;

	public function __construct(){
		
    parent::__construct(); 
      $this->table_name = "module";
      $this->ci =& get_instance();
    }

    function is_mine(){
      $this->ci->load->model('user_model');
      return $this->object->company_id == $this->ci->user_model->user->company_id;
    }
    function get($id=false){
      if($id){
        $this->object = $this->db->get_where($this->table_name, array("id"=>$id))->row();
      }
      return $this->object;
    }

    function delete_module($module_id=false){
      $this->get($module_id);
      $this->db->delete($this->table_name, array("id"=>$this->object->id));
    }

    function retrive_post($post){
      $this->post_data = (object) $post;

      if(empty($this->post_data->snippet_id)){

        $this->post_data->is_valid = 0;
        $this->post_data->problem_message= "";
        $this->json_object = json_decode($this->post_data->json_config);

        if(empty($this->post_data->mysql_table)){
          $this->post_data->is_valid = 0;
          $this->post_data->problem_message .= "Nenhuma tabela MySQL foi setada<br/>";
        }

        switch (json_last_error()) {
          case JSON_ERROR_NONE:
              if(empty($this->post_data->problem_message)){
                $this->post_data->is_valid = 1;
                $this->post_data->problem_message .="Não ocorreu nenhum erro";
              }
            break;
          case JSON_ERROR_DEPTH:
            $this->post_data->problem_message.="A profundidade máxima da pilha foi excedida";
            break;
          case JSON_ERROR_STATE_MISMATCH:
            $this->post_data->problem_message.="JSON inválido ou mal formado";
            break;
          case JSON_ERROR_CTRL_CHAR:
            $this->post_data->problem_message.="Erro de caractere de controle, possivelmente codificado incorretamente";
            break;
          case JSON_ERROR_SYNTAX:
            $this->post_data->problem_message.="Erro de sintaxe";
            break;
          case JSON_ERROR_UTF8:
            $this->post_data->problem_message.="caracteres UTF-8 malformado , possivelmente codificado incorretamente";
            break;
        }

       

        if($this->post_data->is_valid == 1){
          if(isset($this->json_object->languages)){
            // Valida idiomas, regra:
            // nada de acentos
            // checar se tabela de idiomas existe, se nao, criar
            // verificar se as siglas inseridas existem
            if(is_array($this->json_object->languages)){
              if(!$this->get_db()->table_exists('mngr_languages')){
                $this->get_db()->query("
                  CREATE TABLE `mngr_languages` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `identification` varchar(20) NOT NULL,
                    PRIMARY KEY (`id`),
                    KEY `mngr_languages_identification` (`identification`)
                  )");
              }

              foreach ($this->json_object->languages as $language) {
                if (preg_match('/[^a-zA-Z]+/', $language, $matches)){
                  $this->post_data->is_valid = 0;
                  $this->post_data->problem_message .= "Idiomas aceita apenas siglas, ex:(EN)";
                }else{
                  $databse_language = $this->get_db()->get_where('mngr_languages', array('identification'=>$language));
                  if($databse_language->num_rows == 0){
                    $this->get_db()->insert('mngr_languages', array('identification'=>$language));
                  }
                }
              }
            }else{
              $this->post_data->is_valid = 0;
              $this->post_data->problem_message .= "languages foi mal configurado, deve ser um array ex: ['PT','EN']";
            }
          }


          // validar os componentes

          // validar as foreign keys, 
          // checar se as tabelas de ligacao existem
          // e se os campos estão corretos
          if(isset($this->json_object->options->foreign_keys)){
            $foreign_keys = (array)$this->json_object->options->foreign_keys;
            
            foreach ($foreign_keys as $field => $foreign_key) {

              if(!$this->get_db()->field_exists($field,$this->post_data->mysql_table)){
                $this->post_data->is_valid = 0;
                $this->post_data->problem_message .= "<br/><br/><i class='fa fa-exclamation'></i> A sua chave da Foreign Keu {$field} não existe na tabela ".$this->post_data->mysql_table;
              }

              if(!$this->get_db()->table_exists($foreign_key->table)){
                $this->post_data->is_valid = 0;
                $this->post_data->problem_message .= "<br/><br/><i class='fa fa-exclamation'></i> Tabela ".$foreign_key->table." das foreign keys não existe";
              }else{
                if(!$this->get_db()->field_exists($foreign_key->field, $foreign_key->table)){
                  $this->post_data->is_valid = 0;
                  $this->post_data->problem_message .= "<br/><br/><i class='fa fa-exclamation'></i> O campo ".$foreign_key->field." da tabela '".$foreign_key->table."' nas foreign keys não existe";
                }
              }
            }
          }
        }


      }else{
        unset($this->post_data->json_config);
      }
    }

    function register(){
      if(!empty($this->post_data->id)){
        $this->operation_name = 'atualizar';
        $this->get($this->post_data->id);
        if($this->is_mine()){
          $this->db->update($this->table_name, $this->post_data, array("id"=>$this->post_data->id));
          $this->get($this->post_data->id);
        }else{
          set_message("Uma falha de segurança foi detectada, operação cancelada",2);
        }
      }else{
        $this->operation_name = 'criar';
        $this->db->insert($this->table_name, $this->post_data);
        $this->get($this->db->insert_id());
      }
    }

     public function findByCompany($company_id){
     	return $this->db->from("module")->where("company_id",$company_id)->get()->result();
     }

     function list_possible_icons(){
     	return $this->db->from("module_icon_name")->get()->result();
     }

     function list_menus($company_id){
          return $this->db->from("menu")->where("company_id",$company_id)->get()->result();
     }

     function list_tables(){
          return $this->database_client->list_tables();
     }

     function populate_json($table){
          $database_fields = $this->database_client->list_fields($table);
          $fild_names = "";

          foreach ($database_fields as $index => $field) {
               $fild_names .= '"'.$field.'":""';
               if(end($database_fields) != $field){
                    $fild_names .= ",\n";
               }
          }
          
           $default_json='{
            "languages":["PT","EN","ES","ITA"],
  "primary_key": "id",
  "layout":"default",
  "labels": {
    '.
    $fild_names
    .'
  },
  "remove_list": [
    "id"
  ],
  "possible_actions": [
    "c",
    "r",
    "u",
    "d"
  ],
  "components": {
    "out_of_form": {
      "onetomany": {
        "table": "ligacao",
    "texto_header":"Teste de vinculo",
        "fieldKey": "id_teste",
        "myfieldKey": "id"
      }
    }
  },
  "options": {
    "foreign_keys": {
      "teste_id": {
        "table": "teste",
        "field": "nome",
        "label": "Teste mesmo",
        "filter": "id"
      }
    },
    "fields": {
      "image": {
        "plugin": "single_upload",
        "size": "250x220"
      }
    }
  }
}';

       return $default_json;   
     }

 }
 ?>