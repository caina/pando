<?php
/******
STATUS:

1: RASCUNHO
2: PUBLICAR
3: DELETAR

*****/

class Magazine_model extends Dynamic_model {
	var $table_prefix = "";
	var $table = "ma_entries";

	var $magazine;
	var $post_data;
	var $objects;
	var $ci;
	var $exists;

	var $itens_per_page = 5;
	var $itens_found;

	public function __construct() {
		parent::__construct();
		$this->ci =&get_instance();
		$this->ci->load->model('user_model');
	}

	function get($id=false){
		if($id){
			$this->magazine = $this->get_($id);
		}else{
			$this->delete_entries_older();
			$this->get_db()->insert($this->table, array("status"=>1));
			$this->magazine = $this->get_($this->get_db()->insert_id());
		}

		$this->magazine->formated_publish_date = date("d/m/Y",strtotime($this->magazine->publish_date));
		$this->magazine->formated_created_date = date("d/m/Y",strtotime($this->magazine->created_at));
		switch ($this->magazine->status) {
			case 1:
				$this->magazine->status_title = "Rascunho";
				break;
			case 2:
				$this->magazine->status_title = "Publicado";
				break;
			case 3:
				$this->magazine->status_title = "Deletado";
				break;
		}
		
		return $this;
	}

	function get_($id){
		return $this->get_db()->
			select("ma_entries.*,ma_category.name as category_name")->
			from($this->table)->
			join("ma_category","ma_category.id=ma_entries.ma_category_id","LEFT")->
			where("ma_entries.id",$id)->get()->row();
	}

	function delete_entries_older(){
		// implementar
	}

	function run_basic(){

		$basicSQL = <<<EOT
			
		
EOT;

		$qrs = explode(";", $basicSQL);
		foreach ($qrs as $query) {
			$this->get_db()->query($query);
		}
	}

}
?>
